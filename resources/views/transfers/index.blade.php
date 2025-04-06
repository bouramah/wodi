<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transferts') }}
            </h2>
            @if(auth()->user()->hasRole('agent'))
            <a href="{{ route('transfers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Nouveau transfert') }}
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @elseif(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Formulaire de filtrage -->
                    <form method="GET" action="{{ route('transfers.index') }}" class="mb-6 bg-white p-4 rounded-lg shadow" x-data="{ filterType: '{{ request('filter_type', 'date_range') }}' }">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Champ de recherche -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('Rechercher') }}</label>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Code, nom ou téléphone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('Type de filtre') }}</label>
                                <select name="filter_type" x-model="filterType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="date_range" {{ request('filter_type') === 'date_range' ? 'selected' : '' }}>{{ __('Période') }}</option>
                                    <option value="month" {{ request('filter_type') === 'month' ? 'selected' : '' }}>{{ __('Mois') }}</option>
                                </select>
                            </div>

                            <!-- Champs pour la période -->
                            <div x-show="filterType === 'date_range'" class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Date début') }}</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">{{ __('Date fin') }}</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- Champ pour le mois -->
                            <div x-show="filterType === 'month'">
                                <label class="block text-sm font-medium text-gray-700">{{ __('Mois') }}</label>
                                <input type="month" name="month" value="{{ request('month') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end space-x-4">
                            <a href="{{ route('transfers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Réinitialiser') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Filtrer') }}
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <div class="flex justify-end mb-4 space-x-4">
                            @if(auth()->user()->hasRole('agent') || auth()->user()->hasRole('admin'))
                            <a href="{{ route('transfers.export.excel') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Excel') }}
                            </a>
                            <a href="{{ route('transfers.export.csv') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('CSV') }}
                            </a>
                            <a href="{{ route('transfers.export.pdf') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('PDF') }}
                            </a>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                        <table class="w-full table-fixed divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th class="w-1/8 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expéditeur</th>
                                    <th class="w-1/8 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinataire</th>
                                    <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="w-1/10 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">De</th>
                                    <th class="w-1/10 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vers</th>
                                    <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="w-1/12 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="w-1/6 px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transfers as $transfer)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-3 text-sm font-medium text-gray-900 truncate">
                                            {{ $transfer->code }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500 truncate">
                                            {{ $transfer->sender->first_name }} {{ $transfer->sender->last_name }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500 truncate">
                                            {{ $transfer->receiver->first_name }} {{ $transfer->receiver->last_name }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500 truncate">
                                            {{ number_format($transfer->amount, 2) }} {{ $transfer->currency->code }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500 truncate">
                                            {{ $transfer->sourceCountry ? $transfer->sourceCountry->name : 'N/A' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500 truncate">
                                            {{ $transfer->destinationCountry ? $transfer->destinationCountry->name : 'N/A' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($transfer->status === 'paid') bg-green-100 text-green-800
                                                @elseif($transfer->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ __('transfers.status.' . $transfer->status) }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-500">
                                            {{ $transfer->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-3 py-3 text-right text-sm font-medium flex flex-wrap gap-1 justify-end">
                                            <a href="{{ route('transfers.show', $transfer) }}" class="inline-flex items-center px-2 py-1 bg-indigo-100 text-indigo-600 hover:bg-indigo-200 rounded-md">{{ __('Voir') }}</a>
                                            <a href="{{ route('transfers.receipt', $transfer) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-md">{{ __('Reçu') }}</a>
                                            @if($transfer->status === 'pending' && auth()->user()->hasRole('agent'))
                                                @if(auth()->user()->id !== $transfer->sending_agent_id)
                                                    <button type="button"
                                                            x-data
                                                            x-on:click="$dispatch('open-modal', 'confirm-payment-{{ $transfer->id }}')"
                                                            class="inline-flex items-center px-2 py-1 bg-green-100 text-green-600 hover:bg-green-200 rounded-md">
                                                        {{ __('Payer') }}
                                                    </button>
                                                @endif

                                                @if(auth()->user()->id === $transfer->sending_agent_id)
                                                    <button type="button"
                                                            x-data
                                                            x-on:click="$dispatch('open-modal', 'confirm-cancellation-{{ $transfer->id }}')"
                                                            class="inline-flex items-center px-2 py-1 bg-red-100 text-red-600 hover:bg-red-200 rounded-md">
                                                        {{ __('Annuler') }}
                                                    </button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de paiement -->
    @foreach($transfers as $transfer)
        @if($transfer->status === 'pending' && auth()->user()->hasRole('agent') && auth()->user()->id !== $transfer->sending_agent_id)
            <x-modal name="confirm-payment-{{ $transfer->id }}" :show="false" focusable>
                <form method="POST" action="{{ route('transfers.update-status', $transfer) }}" class="p-6">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="paid">

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
        @endif

        @if($transfer->status === 'pending' && auth()->user()->hasRole('agent') && auth()->user()->id === $transfer->sending_agent_id)
            <x-modal name="confirm-cancellation-{{ $transfer->id }}" :show="false" focusable>
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
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.querySelector('form[method="GET"]');

            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const queryString = new URLSearchParams(formData).toString();

                // Rediriger vers la même page avec les paramètres de filtrage
                window.location.href = `${window.location.pathname}?${queryString}`;
            });
        });
    </script>

</x-app-layout>
