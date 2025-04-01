<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $user = auth()->user();

        // Récupérer les clients avec pagination
        $clients = User::whereDoesntHave('roles')->orWhereHas('roles', function($query) {
            $query->where('name', 'client');
        })->paginate(10, ['*'], 'clients');

        // Pour les admins, récupérer aussi les agents et admins avec pagination
        $agents = null;
        if ($user->hasRole('admin')) {
            $agents = User::role(['agent', 'admin'])->paginate(10, ['*'], 'agents');
        }

        return view('users.index', compact('clients', 'agents'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        $user = Auth::user();
        $roles = collect();
        $countries = \App\Models\Country::all();

        if ($user->hasRole('admin')) {
            $roles = \Spatie\Permission\Models\Role::all();
        } elseif ($user->hasRole('agent')) {
            $roles = \Spatie\Permission\Models\Role::where('name', 'client')->get();
        }

        return view('users.create', compact('roles', 'countries'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
                'roles' => ['required', 'array'],
                'roles.*' => ['exists:roles,name'],
                'country_id' => ['required', 'exists:countries,id'],
            ]);
        } elseif ($user->hasRole('agent')) {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:255', 'unique:users'],
                'country_id' => ['required', 'exists:countries,id'],
            ]);
        }

        $newUser = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make('00000000'),
            'country_id' => $request->country_id,
        ]);

        if ($user->hasRole('admin') && $request->has('roles')) {
            $newUser->syncRoles($request->roles);
        } else {
            $newUser->assignRole('client');
        }

        return redirect()->route('users.index')
            ->with('success', __('Utilisateur créé avec succès.'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = \Spatie\Permission\Models\Role::all();
        $countries = \App\Models\Country::all();

        return view('users.edit', compact('user', 'roles', 'countries'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validationRules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:users,phone_number,' . $user->id],
            'country_id' => ['required', 'exists:countries,id'],
        ];

        if (auth()->user()->hasRole('admin')) {
            $validationRules['roles'] = ['required', 'array'];
            $validationRules['roles.*'] = ['exists:roles,name'];
        }

        $request->validate($validationRules);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'country_id' => $request->country_id,
        ]);

        if (auth()->user()->hasRole('admin') && $request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')
            ->with('success', __('Utilisateur mis à jour avec succès.'));
    }

    public function resetPassword(User $user)
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Seuls les administrateurs peuvent réinitialiser les mots de passe.');
        }

        $user->update([
            'password' => Hash::make('00000000')
        ]);

        return redirect()->route('users.index')
            ->with('success', __('Mot de passe réinitialisé avec succès.'));
    }

    public function transfers(User $user)
    {
        $this->authorize('view', $user);

        $transfers = Transfer::where(function($query) use ($user) {
            $query->where('sending_agent_id', $user->id)
                  ->orWhere('paying_agent_id', $user->id);
        })->with(['sender', 'receiver', 'currency'])->latest()->paginate(10);

        return view('users.transfers', compact('user', 'transfers'));
    }
}
