<?php

namespace App\Policies;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurrencyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine si l'utilisateur peut voir la liste des devises.
     */
    public function viewAny(User $user): bool
    {
        // Les admins et les agents peuvent voir la liste des devises
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine si l'utilisateur peut voir une devise spécifique.
     */
    public function view(User $user, Currency $currency): bool
    {
        // Les admins et les agents peuvent voir les détails d'une devise
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine si l'utilisateur peut créer des devises.
     */
    public function create(User $user): bool
    {
        // Seuls les admins peuvent créer des devises
        return $user->hasRole('admin');
    }

    /**
     * Determine si l'utilisateur peut mettre à jour une devise.
     */
    public function update(User $user, Currency $currency): bool
    {
        // Seuls les admins peuvent mettre à jour des devises
        return $user->hasRole('admin');
    }

    /**
     * Determine si l'utilisateur peut supprimer une devise.
     */
    public function delete(User $user, Currency $currency): bool
    {
        // Seuls les admins peuvent supprimer des devises
        return $user->hasRole('admin');
    }
}
