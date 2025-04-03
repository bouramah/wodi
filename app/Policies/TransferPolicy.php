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
        // Les admins et agents peuvent voir la liste des transferts
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine whether the user can view the transfer.
     */
    public function view(User $user, Transfer $transfer): bool
    {
        // Les administrateurs et les agents peuvent voir tous les transferts
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine whether the user can create transfers.
     */
    public function create(User $user): bool
    {
        // Seuls les agents peuvent créer des transferts, même s'ils sont aussi admin
        return $user->hasRole('agent');
    }

    /**
     * Determine whether the user can update the transfer.
     */
    public function update(User $user, Transfer $transfer): bool
    {
        // Seuls les agents peuvent mettre à jour les transferts
        if (!$user->hasRole('agent')) {
            return false;
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
        // Personne ne peut supprimer un transfert, même pas un admin
        return false;
    }
}
