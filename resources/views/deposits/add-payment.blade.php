<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ajouter un paiement') }}
            </h2>
            <a href="{{ route('deposits.show', $deposit) }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Informations sur le dépôt -->
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">{{ __('Informations du dépôt') }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Client') }}</p>
                                <p class="font-medium">{{ $deposit->depositor->first_name }} {{ $deposit->depositor->last_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Montant initial') }}</p>
                                <p class="font-medium">{{ number_format($deposit->amount, 2, ',', ' ') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Montant restant') }}</p>
                                <p class="font-medium text-green-600">{{ number_format($deposit->remaining_amount, 2, ',', ' ') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Date de dépôt') }}</p>
                                <p class="font-medium">{{ $deposit->deposit_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('deposits.store-payment', $deposit) }}">
                        @csrf

                        <!-- Montant du paiement -->
                        <div class="mb-4">
                            <x-input-label for="amount_paid" :value="__('Montant du paiement')" />
                            <x-text-input id="amount_paid" class="block mt-1 w-full" type="number" step="0.01"
                                min="0.01" max="{{ $deposit->remaining_amount }}"
                                name="amount_paid" :value="old('amount_paid')" required autofocus />
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Montant maximum autorisé:') }} {{ number_format($deposit->remaining_amount, 2, ',', ' ') }}
                            </p>
                            @error('amount_paid')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date du paiement -->
                        <div class="mb-4">
                            <x-input-label for="payment_date" :value="__('Date du paiement')" />
                            <x-text-input id="payment_date" class="block mt-1 w-full" type="date"
                                name="payment_date" :value="old('payment_date', date('Y-m-d'))" required />
                            @error('payment_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Enregistrer le paiement') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
