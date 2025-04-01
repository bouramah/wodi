<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CurrencyController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des devises
     */
    public function index()
    {
        $this->authorize('viewAny', Currency::class);
        $currencies = Currency::paginate(10);
        return view('currencies.index', compact('currencies'));
    }

    /**
     * Affiche le formulaire de création d'une devise
     */
    public function create()
    {
        $this->authorize('create', Currency::class);
        return view('currencies.create');
    }

    /**
     * Enregistre une nouvelle devise
     */
    public function store(Request $request)
    {
        $this->authorize('create', Currency::class);

        $request->validate([
            'name' => 'required|string|max:255|unique:currencies',
            'code' => 'required|string|max:10|unique:currencies',
            'symbol' => 'required|string|max:10'
        ]);

        Currency::create($request->all());

        return redirect()->route('currencies.index')
            ->with('success', __('Devise créée avec succès'));
    }

    /**
     * Affiche le formulaire d'édition d'une devise
     */
    public function edit(Currency $currency)
    {
        $this->authorize('update', $currency);
        return view('currencies.edit', compact('currency'));
    }

    /**
     * Met à jour une devise
     */
    public function update(Request $request, Currency $currency)
    {
        $this->authorize('update', $currency);

        $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name,' . $currency->id,
            'code' => 'required|string|max:10|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string|max:10'
        ]);

        $currency->update($request->all());

        return redirect()->route('currencies.index')
            ->with('success', __('Devise mise à jour avec succès'));
    }

    /**
     * Supprime une devise
     */
    public function destroy(Currency $currency)
    {
        $this->authorize('delete', $currency);

        // Vérifier si la devise est utilisée
        if ($currency->transfers()->count() > 0) {
            return redirect()->route('currencies.index')
                ->with('error', __('Impossible de supprimer cette devise car elle est associée à des transferts'));
        }

        $currency->delete();

        return redirect()->route('currencies.index')
            ->with('success', __('Devise supprimée avec succès'));
    }
}
