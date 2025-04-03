<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\User;
use App\Models\Currency;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Exports\TransfersExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class TransferController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transfer::with(['sender', 'receiver', 'sendingAgent', 'payingAgent', 'currency']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('code', 'like', "%{$search}%")
                ->orWhereHas('sender', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                })
                ->orWhereHas('receiver', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                });
        }

        // Filtrage par statut
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filtrage par date
        if ($request->has('filter_type')) {
            if ($request->filter_type === 'date_range') {
                if ($request->filled('start_date')) {
                    $query->whereDate('transfer_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('transfer_date', '<=', $request->end_date);
                }
            } elseif ($request->filter_type === 'month') {
                if ($request->filled('month')) {
                    $date = \Carbon\Carbon::createFromFormat('Y-m', $request->month);
                    $query->whereYear('transfer_date', $date->year)
                          ->whereMonth('transfer_date', $date->month);
                }
            }
        }

        if ($user->hasRole('admin')) {
            $transfers = $query->latest()->get();
        } elseif ($user->hasRole('agent')) {
            $transfers = $query->latest()->get();
        }

        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $this->authorize('create', Transfer::class);

        $currencies = Currency::all();
        $countries = Country::all();
        return view('transfers.create', compact('currencies', 'countries'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Transfer::class);

        $validated = $request->validate([
            'sender_first_name' => 'required|string|max:255',
            'sender_last_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'receiver_first_name' => 'required|string|max:255',
            'receiver_last_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'source_country_id' => 'required|exists:countries,id',
            'destination_country_id' => 'required|exists:countries,id',
        ]);

        // Créer ou récupérer le sender
        $sender = User::firstOrCreate(
            ['phone_number' => $validated['sender_phone']],
            [
                'first_name' => $validated['sender_first_name'],
                'last_name' => $validated['sender_last_name'],
                'password' => Hash::make(Str::random(10)),
            ]
        );

        // Attribuer le rôle "client" si c'est un nouvel utilisateur
        if(!$sender->hasRole('client')) {
            $sender->assignRole('client');
        }

        // Créer ou récupérer le receiver
        $receiver = User::firstOrCreate(
            ['phone_number' => $validated['receiver_phone']],
            [
                'first_name' => $validated['receiver_first_name'],
                'last_name' => $validated['receiver_last_name'],
                'password' => Hash::make(Str::random(10)),
            ]
        );

        // Attribuer le rôle "client" si c'est un nouvel utilisateur
        if(!$receiver->hasRole('client')) {
            $receiver->assignRole('client');
        }

        // Générer le code de transfert
        $code = $this->generateTransferCode();

        // Créer le transfert
        $transfer = Transfer::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'sending_agent_id' => Auth::user()->id,
            'amount' => $validated['amount'],
            'currency_id' => $validated['currency_id'],
            'source_country_id' => $validated['source_country_id'],
            'destination_country_id' => $validated['destination_country_id'],
            'code' => $code,
            'status' => 'pending',
            'transfer_date' => now(),
        ]);

        return redirect()->route('transfers.show', $transfer)
            ->with('success', 'Transfert créé avec succès.');
    }

    public function show(Transfer $transfer)
    {
        $this->authorize('view', $transfer);
        return view('transfers.show', compact('transfer'));
    }

    public function updateStatus(Request $request, Transfer $transfer)
    {
        $this->authorize('update', $transfer);

        $validated = $request->validate([
            'status' => 'required|in:paid,cancelled',
        ]);

        // Vérifier si l'utilisateur est un agent
        $isAgent = Auth::user()->hasRole('agent');
        if (!$isAgent) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        // Vérifier si l'utilisateur est l'agent qui a créé le transfert
        $isSendingAgent = $transfer->sending_agent_id === Auth::user()->id;

        // Vérifier si l'utilisateur est un autre agent
        $isOtherAgent = Auth::user()->hasRole('agent') && !$isSendingAgent;

        if ($validated['status'] === 'paid' && !$isOtherAgent) {
            return back()->with('error', 'Seul un autre agent peut marquer le transfert comme payé.');
        }

        if ($validated['status'] === 'cancelled' && !$isSendingAgent) {
            return back()->with('error', 'Seul l\'agent qui a créé le transfert peut l\'annuler.');
        }

        $transfer->update([
            'status' => $validated['status'],
            'payment_date' => $validated['status'] === 'paid' ? now() : null,
            'paying_agent_id' => $validated['status'] === 'paid' ? Auth::user()->id : null,
        ]);

        return redirect()->route('transfers.show', $transfer)
            ->with('success', $validated['status'] === 'paid'
                ? 'Le transfert a été marqué comme payé avec succès.'
                : 'Le transfert a été annulé avec succès.');
    }

    private function generateTransferCode()
    {
        $prefix = 'A';
        $lastTransfer = Transfer::orderBy('id', 'desc')->first();

        if (!$lastTransfer) {
            return $prefix . '1';
        }

        // Extraire le numéro de la partie numérique du code
        $lastCode = $lastTransfer->code;
        $lastNumber = (int) substr($lastCode, strlen($prefix));

        // Incrémenter et retourner le nouveau code
        return $prefix . ($lastNumber + 1);
    }

    public function exportExcel()
    {
        $this->authorize('viewAny', Transfer::class);

        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('agent')) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à exporter les données.');
        }

        $query = Transfer::with(['sender', 'receiver', 'sendingAgent', 'payingAgent', 'currency', 'sourceCountry', 'destinationCountry']);

        if ($user->hasRole('admin')) {
            $transfers = $query->latest()->get();
        } elseif ($user->hasRole('agent')) {
            $transfers = $query->where(function($q) use ($user) {
                $q->where('sending_agent_id', $user->id)
                  ->orWhere('paying_agent_id', $user->id);
            })->latest()->get();
        } else {
            $transfers = collect(); // Collection vide si l'utilisateur n'a pas les rôles requis
        }

        return Excel::download(new TransfersExport($transfers), 'transferts.xlsx');
    }

    public function exportCsv()
    {
        $this->authorize('viewAny', Transfer::class);

        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('agent')) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à exporter les données.');
        }

        $query = Transfer::with(['sender', 'receiver', 'sendingAgent', 'payingAgent', 'currency', 'sourceCountry', 'destinationCountry']);

        if ($user->hasRole('admin')) {
            $transfers = $query->latest()->get();
        } elseif ($user->hasRole('agent')) {
            $transfers = $query->where(function($q) use ($user) {
                $q->where('sending_agent_id', $user->id)
                  ->orWhere('paying_agent_id', $user->id);
            })->latest()->get();
        } else {
            $transfers = collect(); // Collection vide si l'utilisateur n'a pas les rôles requis
        }

        return Excel::download(new TransfersExport($transfers), 'transferts.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $this->authorize('viewAny', Transfer::class);

        $user = Auth::user();
        if (!$user->hasRole('admin') && !$user->hasRole('agent')) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à exporter les données.');
        }

        $query = Transfer::with(['sender', 'receiver', 'sendingAgent', 'payingAgent', 'currency', 'sourceCountry', 'destinationCountry']);

        if ($user->hasRole('admin')) {
            $transfers = $query->latest()->get();
        } elseif ($user->hasRole('agent')) {
            $transfers = $query->where(function($q) use ($user) {
                $q->where('sending_agent_id', $user->id)
                  ->orWhere('paying_agent_id', $user->id);
            })->latest()->get();
        } else {
            $transfers = collect(); // Collection vide si l'utilisateur n'a pas les rôles requis
        }

        $pdf = PDF::loadView('transfers.pdf', compact('transfers'));
        return $pdf->download('transferts.pdf');
    }

    public function receipt(Transfer $transfer)
    {
        $this->authorize('view', $transfer);
        return view('transfers.receipt', compact('transfer'));
    }
}
