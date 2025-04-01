<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cartes de statistiques générales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @if(auth()->user()->hasRole('admin'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.99998 11.2H21L22.4 23.8H5.59998L6.99998 11.2Z" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    <path d="M9.79999 8.4C9.79999 6.08041 11.6804 4.2 14 4.2C16.3196 4.2 18.2 6.08041 18.2 8.4V11.2H9.79999V8.4Z" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">{{ $data['total_transfers'] ?? 0 }}</h4>
                                <div class="text-gray-500">{{ __('Transferts') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 2.33333C7.54413 2.33333 2.33333 7.54413 2.33333 14C2.33333 20.4558 7.54413 25.6667 14 25.6667C20.4558 25.6667 25.6667 20.4558 25.6667 14C25.6667 7.54413 20.4558 2.33333 14 2.33333Z" fill="currentColor" stroke="currentColor" stroke-width="2"/>
                                    <path d="M14 8.16667V14L17.5 17.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">{{ $data['recent_transfers'] ?? 0 }}</h4>
                                <div class="text-gray-500">{{ __('Transferts récents (7j)') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.66667 9.33333C4.66667 7.13333 6.46667 5.33333 8.66667 5.33333H19.3333C21.5333 5.33333 23.3333 7.13333 23.3333 9.33333V18.6667C23.3333 20.8667 21.5333 22.6667 19.3333 22.6667H8.66667C6.46667 22.6667 4.66667 20.8667 4.66667 18.6667V9.33333Z" fill="currentColor" stroke="currentColor" stroke-width="2"/>
                                    <path d="M14 15.1667C15.3807 15.1667 16.5 14.0474 16.5 12.6667C16.5 11.286 15.3807 10.1667 14 10.1667C12.6193 10.1667 11.5 11.286 11.5 12.6667C11.5 14.0474 12.6193 15.1667 14 15.1667Z" stroke="white" stroke-width="2"/>
                                    <path d="M19.8333 15.1667H19.8425" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.16667 15.1667H8.17583" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">{{ $data['total_clients'] ?? 0 }}</h4>
                                <div class="text-gray-500">{{ __('Clients') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <br>

            @if(auth()->user()->hasRole('admin'))
            <!-- Seconde rangée de cartes de statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                                <svg class="h-8 w-8 text-white" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 14C17.866 14 21 10.866 21 7C21 3.13401 17.866 0 14 0C10.134 0 7 3.13401 7 7C7 10.866 10.134 14 14 14Z" fill="currentColor"/>
                                    <path d="M14 16.3333C7.3637 16.3333 2 21.6971 2 28.3333H26C26 21.6971 20.6363 16.3333 14 16.3333Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">{{ $data['total_agents'] ?? 0 }}</h4>
                                <div class="text-gray-500">{{ __('Agents') }}</div>
                            </div>
                        </div>
                    </div>
                </div>


                    <!-- Section Admin -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Statistiques de transferts -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Statistiques des transferts') }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('En attente') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-amber-600">{{ $data['pending_transfers'] ?? 0 }}</h4>
                                            <span class="ml-2 text-sm text-gray-600">({{ number_format($data['pending_amount'] ?? 0, 2, ',', ' ') }})</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Payés') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-green-600">{{ $data['paid_transfers'] ?? 0 }}</h4>
                                            <span class="ml-2 text-sm text-gray-600">({{ number_format($data['paid_amount'] ?? 0, 2, ',', ' ') }})</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Annulés') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-red-600">{{ $data['cancelled_transfers'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Clients') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-blue-600">{{ $data['total_clients'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques des dépôts -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Statistiques des dépôts') }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Total dépôts') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-indigo-600">{{ $data['total_deposits'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Dépôts actifs') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-green-600">{{ $data['active_deposits'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Montant total') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-blue-600">{{ number_format($data['total_deposit_amount'] ?? 0, 2, ',', ' ') }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Montant restant') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-amber-600">{{ number_format($data['remaining_deposit_amount'] ?? 0, 2, ',', ' ') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top agents -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Top 5 des agents les plus actifs') }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Agent') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Transferts envoyés') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Transferts payés') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Total') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($data['top_agents'] ?? [] as $agent)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $agent->first_name }} {{ $agent->last_name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $agent->sent_count }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $agent->paid_count }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-900">{{ $agent->total_count }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    {{ __('Aucune donnée disponible') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques mensuelles -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Statistiques des 6 derniers mois') }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Mois') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Total') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Payés') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('En attente') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Annulés') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Montant') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($data['monthly_stats'] ?? [] as $month)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $month['label'] }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $month['total'] }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-green-600">{{ $month['paid'] }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-amber-600">{{ $month['pending'] }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-red-600">{{ $month['cancelled'] }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-gray-900">{{ number_format($month['amount'], 2, ',', ' ') }}</div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    {{ __('Aucune donnée disponible') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif(auth()->user()->hasRole('agent'))
                    <!-- Section Agent -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Statistiques des transferts de l'agent -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Mes transferts') }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Transferts envoyés') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-blue-600">{{ $data['my_total_sent'] ?? 0 }}</h4>
                                            <span class="ml-2 text-sm text-gray-600">({{ number_format($data['my_sent_amount'] ?? 0, 2, ',', ' ') }})</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Transferts payés') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-green-600">{{ $data['my_total_paid'] ?? 0 }}</h4>
                                            <span class="ml-2 text-sm text-gray-600">({{ number_format($data['my_paid_amount'] ?? 0, 2, ',', ' ') }})</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('En attente de paiement') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-amber-600">{{ $data['my_pending_transfers'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Récents (7j)') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-indigo-600">{{ $data['my_recent_transfers'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques des dépôts de l'agent -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Mes dépôts clients') }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Total dépôts') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-indigo-600">{{ $data['my_deposits'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Dépôts actifs') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-green-600">{{ $data['my_active_deposits'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Montant total') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-blue-600">{{ number_format($data['my_deposit_amount'] ?? 0, 2, ',', ' ') }}</h4>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <span class="text-sm text-gray-600">{{ __('Montant restant') }}</span>
                                        <div class="flex items-center mt-1">
                                            <h4 class="text-xl font-bold text-amber-600">{{ number_format($data['my_remaining_deposit_amount'] ?? 0, 2, ',', ' ') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-semibold mb-4 border-b pb-2">{{ __('Actions rapides') }}</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <a href="{{ route('transfers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
                                    {{ __('Nouveau transfert') }}
                                </a>
                                <a href="{{ route('deposits.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                                    {{ __('Nouveau dépôt') }}
                                </a>
                                <a href="{{ route('transfers.index') }}" class="bg-amber-500 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded text-center">
                                    {{ __('Voir transferts') }}
                                </a>
                                <a href="{{ route('deposits.index') }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded text-center">
                                    {{ __('Voir dépôts') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
