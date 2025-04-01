<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine si l'utilisateur peut voir la liste des pays.
     */
    public function viewAny(User $user): bool
    {
        // Les admins et les agents peuvent voir la liste des pays
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine si l'utilisateur peut voir un pays spécifique.
     */
    public function view(User $user, Country $country): bool
    {
        // Les admins et les agents peuvent voir les détails d'un pays
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    /**
     * Determine si l'utilisateur peut créer des pays.
     */
    public function create(User $user): bool
    {
        // Seuls les admins peuvent créer des pays
        return $user->hasRole('admin');
    }

    /**
     * Determine si l'utilisateur peut mettre à jour un pays.
     */
    public function update(User $user, Country $country): bool
    {
        // Seuls les admins peuvent mettre à jour des pays
        return $user->hasRole('admin');
    }

    /**
     * Determine si l'utilisateur peut supprimer un pays.
     */
    public function delete(User $user, Country $country): bool
    {
        // Seuls les admins peuvent supprimer des pays
        return $user->hasRole('admin');
    }
}
