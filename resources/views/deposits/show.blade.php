<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du dépôt') }}
            </h2>
            <div>
                <a href="{{ route('deposits.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Retour') }}
                </a>
                @if ($deposit->remaining_amount > 0)
                    <a href="{{ route('deposits.add-payment', $deposit) }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                        {{ __('Ajouter paiement') }}
                    </a>
                @endif
                <a href="{{ route('deposits.edit', $deposit) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                    {{ __('Modifier') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Informations sur le dépôt -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                                {{ __('Informations du dépôt') }}
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Client') }}</p>
                                    <p class="text-base">{{ $deposit->depositor->first_name }} {{ $deposit->depositor->last_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $deposit->depositor->phone_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Agent') }}</p>
                                    <p class="text-base">{{ $deposit->agent->first_name }} {{ $deposit->agent->last_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Montant initial') }}</p>
                                    <p class="text-base font-bold">{{ number_format($deposit->amount, 2, ',', ' ') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Date de dépôt') }}</p>
                                    <p class="text-base">{{ $deposit->deposit_date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Montant restant') }}</p>
                                    <p class="text-base font-bold {{ $deposit->remaining_amount > 0 ? 'text-green-600' : 'text-gray-700' }}">
                                        {{ number_format($deposit->remaining_amount, 2, ',', ' ') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">{{ __('Montant utilisé') }}</p>
                                    <p class="text-base font-bold text-blue-600">
                                        {{ number_format($deposit->amount - $deposit->remaining_amount, 2, ',', ' ') }}
                                        <span class="text-sm font-normal text-gray-500">
                                            ({{ number_format(($deposit->amount - $deposit->remaining_amount) / $deposit->amount * 100, 0) }}%)
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Graphique ou statistiques -->
                        <div class="bg-gray-50 p-6 rounded-lg shadow">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">
                                {{ __('Progression') }}
                            </h3>
                            <div class="mb-6">
                                <div class="relative pt-1">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-blue-600 bg-blue-200">
                                                {{ __('Utilisation du dépôt') }}
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold inline-block text-blue-600">
                                                {{ number_format(($deposit->amount - $deposit->remaining_amount) / $deposit->amount * 100, 0) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                                        <div style="width: {{ ($deposit->amount - $deposit->remaining_amount) / $deposit->amount * 100 }}%"
                                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-2">{{ __('Résumé des paiements') }}</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-indigo-100 p-3 rounded-lg">
                                        <p class="text-xs font-medium text-indigo-800">{{ __('Nombre de paiements') }}</p>
                                        <p class="text-xl font-bold text-indigo-800">{{ $deposit->payments->count() }}</p>
                                    </div>
                                    <div class="bg-green-100 p-3 rounded-lg">
                                        <p class="text-xs font-medium text-green-800">{{ __('Dernier paiement') }}</p>
                                        <p class="text-xl font-bold text-green-800">
                                            @if($deposit->payments->isNotEmpty())
                                                {{ $deposit->payments->sortByDesc('payment_date')->first()->payment_date->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des paiements -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ __('Historique des paiements') }}
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Montant payé') }}
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Solde après paiement') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($deposit->payments->sortByDesc('payment_date') as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $payment->payment_date->format('d/m/Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ number_format($payment->amount_paid, 2, ',', ' ') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @php
                                                        $runningTotal = $deposit->amount;
                                                        foreach ($deposit->payments->sortBy('payment_date') as $p) {
                                                            $runningTotal -= $p->amount_paid;
                                                            if ($p->id === $payment->id) {
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    {{ number_format($runningTotal, 2, ',', ' ') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                                {{ __('Aucun paiement trouvé.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
