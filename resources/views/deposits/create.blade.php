<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nouveau dépôt') }}
            </h2>
            <a href="{{ route('deposits.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Retour') }}
            </a>
        </div>
    </x-slot>

    <!-- Ajout de Select2 CSS -->
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 42px;
            padding: 6px;
            border-color: rgb(209 213 219);
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('deposits.store') }}">
                        @csrf

                        <!-- Client -->
                        <div class="mb-4">
                            <x-input-label for="depositor_id" :value="__('Client')" />
                            <select id="depositor_id" name="depositor_id" required
                                class="select2 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">{{ __('Sélectionner un client') }}</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" @selected(old('depositor_id') == $client->id)>
                                        {{ $client->first_name }} {{ $client->last_name }} ({{ $client->phone_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('depositor_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Montant -->
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Montant')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" min="0.01"
                                name="amount" :value="old('amount')" required />
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de dépôt -->
                        <div class="mb-4">
                            <x-input-label for="deposit_date" :value="__('Date de dépôt')" />
                            <x-text-input id="deposit_date" class="block mt-1 w-full" type="date"
                                name="deposit_date" :value="old('deposit_date', date('Y-m-d'))" required />
                            @error('deposit_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Enregistrer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ajout de Select2 JS -->
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "{{ __('Rechercher un client...') }}",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
    @endpush
</x-app-layout>
