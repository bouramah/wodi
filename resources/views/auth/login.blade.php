<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="text-center text-2xl font-semibold text-gray-800 mb-6">
        {{ __('Connexion') }}
    </h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Phone Number -->
        <div class="mb-4">
            <x-input-label for="phone_number" :value="__('Numéro de téléphone')" />
            <x-text-input id="phone_number"
                class="block mt-1 w-full"
                type="text"
                name="phone_number"
                :value="old('phone_number')"
                required autofocus autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mb-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mb-4">
            {{-- @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif --}}

            <x-primary-button class="btn-login">
                {{ __('Se connecter') }}
            </x-primary-button>
        </div>
    </form>

    <div class="text-center text-gray-600 text-sm">
        {{ __('Besoin d\'aide pour vous connecter?') }} <br>
        {{ __('Contactez votre administrateur') }}
    </div>
</x-guest-layout>
