<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du transfert') }} - {{ $transfer->code }}
            </h2>
            <a href="{{ route('transfers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Informations sur l'expéditeur -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informations sur l\'expéditeur') }}</h3>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Nom complet') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->sender->first_name }} {{ $transfer->sender->last_name }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Téléphone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->sender->phone_number }}
                                </dd>
                            </div>
                        </div>

                        <!-- Informations sur le destinataire -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informations sur le destinataire') }}</h3>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Nom complet') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->receiver->first_name }} {{ $transfer->receiver->last_name }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Téléphone') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->receiver->phone_number }}
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur le transfert -->
                    <div class="mt-8 space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Informations sur le transfert') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Montant') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ number_format($transfer->amount, 2) }} {{ $transfer->currency->code }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Pays d\'origine') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->sourceCountry ? $transfer->sourceCountry->name : 'Non spécifié' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Pays de destination') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->destinationCountry ? $transfer->destinationCountry->name : 'Non spécifié' }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Statut') }}</dt>
                                <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($transfer->status === 'paid') bg-green-100 text-green-800
                                        @elseif($transfer->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ __('transfers.status.' . $transfer->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Agent d\'envoi') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->sendingAgent->first_name }} {{ $transfer->sendingAgent->last_name }}<br>
                                    <span class="text-xs text-gray-500">{{ $transfer->sendingAgent->phone_number }}</span>
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Agent payeur') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    @if($transfer->payingAgent)
                                        {{ $transfer->payingAgent->first_name }} {{ $transfer->payingAgent->last_name }}
                                    @else
                                        <span class="text-gray-500">{{ __('Non assigné') }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Date de transfert') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    {{ $transfer->transfer_date->format('d/m/Y') }}
                                </dd>
                            </div>
                            @if($transfer->payment_date)
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Date de paiement') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                        {{ $transfer->payment_date->format('d/m/Y') }}
                                    </dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($transfer->status === 'pending' && auth()->user()->hasRole('agent'))
                        <div class="mt-8 flex justify-end space-x-4">
                            @if(auth()->user()->id !== $transfer->sending_agent_id)
                                <button type="button"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'confirm-payment')"
                                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Marquer comme payé') }}
                                </button>
                            @endif

                            @if(auth()->user()->id === $transfer->sending_agent_id)
                                <button type="button"
                                        x-data
                                        x-on:click="$dispatch('open-modal', 'confirm-cancellation')"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Annuler le transfert') }}
                                </button>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('transfers.receipt', $transfer) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Imprimer le reçu') }}
                        </a>
                        <a href="{{ route('transfers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Retour à la liste') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de paiement -->
    @if(auth()->user()->hasRole('agent'))
    <x-modal name="confirm-payment" :show="false" focusable>
        <form method="POST" action="{{ route('transfers.update-status', $transfer) }}" class="p-6">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="completed">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Confirmer le paiement') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Êtes-vous sûr de vouloir marquer ce transfert comme payé ?') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Confirmer le paiement') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Modal de confirmation d'annulation -->
    <x-modal name="confirm-cancellation" :show="false" focusable>
        <form method="POST" action="{{ route('transfers.update-status', $transfer) }}" class="p-6">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="cancelled">

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Confirmer l\'annulation') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Êtes-vous sûr de vouloir annuler ce transfert ?') }}
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuler') }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __('Confirmer l\'annulation') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
    @endif
</x-app-layout>
