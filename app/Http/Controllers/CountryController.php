<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CountryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des pays
     */
    public function index()
    {
        $this->authorize('viewAny', Country::class);
        $countries = Country::paginate(10);
        return view('countries.index', compact('countries'));
    }

    /**
     * Affiche le formulaire de création d'un pays
     */
    public function create()
    {
        $this->authorize('create', Country::class);
        return view('countries.create');
    }

    /**
     * Enregistre un nouveau pays
     */
    public function store(Request $request)
    {
        $this->authorize('create', Country::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:countries',
            'code' => 'required|string|max:10|unique:countries',
        ]);

        Country::create($request->all());

        return redirect()->route('countries.index')
            ->with('success', __('Pays créé avec succès'));
    }

    /**
     * Affiche le formulaire d'édition d'un pays
     */
    public function edit(Country $country)
    {
        $this->authorize('update', $country);
        return view('countries.edit', compact('country'));
    }

    /**
     * Met à jour un pays
     */
    public function update(Request $request, Country $country)
    {
        $this->authorize('update', $country);

        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'code' => 'required|string|max:10|unique:countries,code,' . $country->id,
        ]);

        $country->update($request->all());

        return redirect()->route('countries.index')
            ->with('success', __('Pays mis à jour avec succès'));
    }

    /**
     * Supprime un pays
     */
    public function destroy(Country $country)
    {
        $this->authorize('delete', $country);

        // Vérifier si le pays est utilisé
        if ($country->users()->count() > 0) {
            return redirect()->route('countries.index')
                ->with('error', __('Impossible de supprimer ce pays car il est associé à des utilisateurs'));
        }

        $country->delete();

        return redirect()->route('countries.index')
            ->with('success', __('Pays supprimé avec succès'));
    }
}
