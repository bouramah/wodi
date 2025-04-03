<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouveau transfert') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('transfers.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informations sur l'expéditeur -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Informations sur l\'expéditeur') }}</h3>

                                <div>
                                    <x-input-label for="sender_first_name" :value="__('Prénom')" />
                                    <x-text-input id="sender_first_name" name="sender_first_name" type="text" class="mt-1 block w-full" :value="old('sender_first_name')" required autofocus />
                                    <x-input-error :messages="$errors->get('sender_first_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="sender_last_name" :value="__('Nom')" />
                                    <x-text-input id="sender_last_name" name="sender_last_name" type="text" class="mt-1 block w-full" :value="old('sender_last_name')" required />
                                    <x-input-error :messages="$errors->get('sender_last_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="sender_phone" :value="__('Téléphone')" />
                                    <x-text-input id="sender_phone" name="sender_phone" type="tel" class="mt-1 block w-full" :value="old('sender_phone')" required />
                                    <x-input-error :messages="$errors->get('sender_phone')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Informations sur le destinataire -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Informations sur le destinataire') }}</h3>

                                <div>
                                    <x-input-label for="receiver_first_name" :value="__('Prénom')" />
                                    <x-text-input id="receiver_first_name" name="receiver_first_name" type="text" class="mt-1 block w-full" :value="old('receiver_first_name')" required />
                                    <x-input-error :messages="$errors->get('receiver_first_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="receiver_last_name" :value="__('Nom')" />
                                    <x-text-input id="receiver_last_name" name="receiver_last_name" type="text" class="mt-1 block w-full" :value="old('receiver_last_name')" required />
                                    <x-input-error :messages="$errors->get('receiver_last_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="receiver_phone" :value="__('Téléphone')" />
                                    <x-text-input id="receiver_phone" name="receiver_phone" type="tel" class="mt-1 block w-full" :value="old('receiver_phone')" required />
                                    <x-input-error :messages="$errors->get('receiver_phone')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Informations sur le transfert -->
                        <div class="space-y-4 mt-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informations sur le transfert') }}</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="amount" :value="__('Montant')" />
                                    <x-text-input id="amount" name="amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('amount')" required />
                                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="currency_id" :value="__('Devise')" />
                                    <select id="currency_id" name="currency_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}" {{ $currency->code === 'USD' ? 'selected' : '' }}>
                                                {{ $currency->code }} - {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('currency_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="source_country_id" :value="__('Pays d\'origine')" />
                                    <select id="source_country_id" name="source_country_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">{{ __('Sélectionner un pays') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('source_country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('source_country_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="destination_country_id" :value="__('Pays de destination')" />
                                    <select id="destination_country_id" name="destination_country_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">{{ __('Sélectionner un pays') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('destination_country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('destination_country_id')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Créer le transfert') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
