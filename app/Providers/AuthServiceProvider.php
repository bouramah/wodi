<?php

namespace App\Providers;

use App\Models\Transfer;
use App\Policies\TransferPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Country;
use App\Models\Currency;
use App\Policies\CountryPolicy;
use App\Policies\CurrencyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string|null>
     */
    protected $policies = [
        Transfer::class => TransferPolicy::class,
        User::class => UserPolicy::class,
        Country::class => CountryPolicy::class,
        Currency::class => CurrencyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
