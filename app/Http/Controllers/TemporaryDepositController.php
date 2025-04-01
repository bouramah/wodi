<?php

namespace App\Http\Controllers;

use App\Models\TemporaryDeposit;
use App\Models\DepositPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemporaryDepositController extends Controller
{
    /**
     * Display a listing of the deposits.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = TemporaryDeposit::with(['depositor', 'agent', 'payments']);

        // Filtrer selon le rôle
        if ($user->hasRole('agent')) {
            $query->where('agent_id', $user->id);
        }

        // Filtrer par nom de déposant si recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->whereHas('depositor', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $deposits = $query->orderBy('deposit_date', 'desc')->paginate(10);

        return view('deposits.index', compact('deposits'));
    }

    /**
     * Show the form for creating a new deposit.
     */
    public function create()
    {
        $clients = User::role('client')->get();
        return view('deposits.create', compact('clients'));
    }

    /**
     * Store a newly created deposit in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'depositor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'deposit_date' => 'required|date'
        ]);

        $deposit = new TemporaryDeposit([
            'depositor_id' => $request->depositor_id,
            'amount' => $request->amount,
            'remaining_amount' => $request->amount, // Au départ, le montant restant est égal au montant total
            'agent_id' => Auth::user()->id,
            'deposit_date' => $request->deposit_date
        ]);

        $deposit->save();

        return redirect()->route('deposits.show', $deposit)
            ->with('success', 'Dépôt enregistré avec succès.');
    }

    /**
     * Display the specified deposit.
     */
    public function show(TemporaryDeposit $deposit)
    {
        $deposit->load(['depositor', 'agent', 'payments']);
        return view('deposits.show', compact('deposit'));
    }

    /**
     * Show the form for editing the specified deposit.
     */
    public function edit(TemporaryDeposit $deposit)
    {
        $clients = User::role('client')->get();
        return view('deposits.edit', compact('deposit', 'clients'));
    }

    /**
     * Update the specified deposit in storage.
     */
    public function update(Request $request, TemporaryDeposit $deposit)
    {
        $request->validate([
            'depositor_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:' . ($deposit->amount - $deposit->remaining_amount),
            'deposit_date' => 'required|date'
        ]);

        // Calculer la différence pour ajuster le montant restant
        $amountDifference = $request->amount - $deposit->amount;
        $newRemainingAmount = $deposit->remaining_amount + $amountDifference;

        $deposit->depositor_id = $request->depositor_id;
        $deposit->amount = $request->amount;
        $deposit->remaining_amount = $newRemainingAmount;
        $deposit->deposit_date = $request->deposit_date;

        $deposit->save();

        return redirect()->route('deposits.show', $deposit)
            ->with('success', 'Dépôt mis à jour avec succès.');
    }

    /**
     * Show form to add a payment.
     */
    public function addPayment(TemporaryDeposit $deposit)
    {
        return view('deposits.add-payment', compact('deposit'));
    }

    /**
     * Store a new payment.
     */
    public function storePayment(Request $request, TemporaryDeposit $deposit)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0|max:' . $deposit->remaining_amount,
            'payment_date' => 'required|date'
        ]);

        $payment = new DepositPayment([
            'temporary_deposit_id' => $deposit->id,
            'amount_paid' => $request->amount_paid,
            'payment_date' => $request->payment_date
        ]);

        $payment->save();

        // Mettre à jour le montant restant
        $deposit->remaining_amount -= $request->amount_paid;
        $deposit->save();

        return redirect()->route('deposits.show', $deposit)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Remove the specified deposit from storage.
     */
    public function destroy(TemporaryDeposit $deposit)
    {
        // Vérifier s'il y a des paiements associés
        if($deposit->payments()->count() > 0) {
            return redirect()->route('deposits.index')
                ->with('error', 'Impossible de supprimer ce dépôt car il a des paiements associés.');
        }

        $deposit->delete();

        return redirect()->route('deposits.index')
            ->with('success', 'Dépôt supprimé avec succès.');
    }
}
