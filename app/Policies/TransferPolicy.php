<?php

namespace App\Policies;

use App\Models\Transfer;
use App\Models\User;

class TransferPolicy
{
    /**
     * Determine whether the user can view any transfers.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine whether the user can view the transfer.
     */
    public function view(User $user, Transfer $transfer): bool
    {
        // Les administrateurs peuvent voir tous les transferts
        if ($user->hasRole('admin')) {
            return true;
        }

        // Les agents peuvent voir tous les transferts en attente
        if ($user->hasRole('agent') && $transfer->status === 'pending') {
            return true;
        }

        // Les agents peuvent voir les transferts où ils sont impliqués
        return $transfer->sending_agent_id === $user->id ||
               $transfer->paying_agent_id === $user->id;
    }

    /**
     * Determine whether the user can create transfers.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine whether the user can update the transfer.
     */
    public function update(User $user, Transfer $transfer): bool
    {
        // Si l'utilisateur est admin, il peut tout faire
        if ($user->hasRole('admin')) {
            return true;
        }

        // Si l'utilisateur est l'agent qui a créé le transfert, il peut l'annuler
        if ($transfer->sending_agent_id === $user->id && $transfer->status === 'pending') {
            return true;
        }

        // Si l'utilisateur est un autre agent, il peut marquer le transfert comme payé
        if ($user->hasRole('agent') &&
            $transfer->sending_agent_id !== $user->id &&
            $transfer->status === 'pending') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the transfer.
     */
    public function delete(User $user, Transfer $transfer): bool
    {
        return $user->hasRole('admin');
    }
}
