<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dépôts temporaires') }}
            </h2>
            <a href="{{ route('deposits.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Nouveau dépôt') }}
            </a>
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

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Formulaire de recherche -->
                    <div class="mb-4">
                        <form action="{{ route('deposits.index') }}" method="GET" class="flex">
                            <input type="text" name="search" placeholder="Rechercher un client..."
                                value="{{ request('search') }}"
                                class="flex-1 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <button type="submit"
                                class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Rechercher') }}
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Client') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Montant initial') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Montant restant') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date de dépôt') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Agent') }}
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($deposits as $deposit)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $deposit->depositor->first_name }} {{ $deposit->depositor->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $deposit->depositor->phone_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ number_format($deposit->amount, 2, ',', ' ') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm {{ $deposit->remaining_amount > 0 ? 'text-green-600' : 'text-gray-900' }}">
                                                {{ number_format($deposit->remaining_amount, 2, ',', ' ') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ number_format(($deposit->amount - $deposit->remaining_amount) / $deposit->amount * 100, 0) }}% utilisé
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $deposit->deposit_date->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $deposit->agent->first_name }} {{ $deposit->agent->last_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('deposits.show', $deposit) }}"
                                                class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                                {{ __('Détails') }}
                                            </a>
                                            @if ($deposit->remaining_amount > 0)
                                                <a href="{{ route('deposits.add-payment', $deposit) }}"
                                                    class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                                    {{ __('Ajouter paiement') }}
                                                </a>
                                            @endif
                                            <a href="{{ route('deposits.edit', $deposit) }}"
                                                class="inline-block bg-gray-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                                {{ __('Modifier') }}
                                            </a>
                                            @if ($deposit->payments->isEmpty())
                                                <form action="{{ route('deposits.destroy', $deposit) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        x-data
                                                        x-on:click="$dispatch('open-modal', 'confirm-delete-deposit-{{ $deposit->id }}')"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                        {{ __('Supprimer') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('Aucun dépôt trouvé.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $deposits->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals de confirmation de suppression -->
    @foreach ($deposits as $deposit)
        <x-modal name="confirm-delete-deposit-{{ $deposit->id }}" :show="false" focusable>
            <form method="POST" action="{{ route('deposits.destroy', $deposit) }}" class="p-6">
                @csrf
                @method('DELETE')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Confirmer la suppression') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Êtes-vous sûr de vouloir supprimer ce dépôt ?') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Annuler') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        {{ __('Supprimer') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-app-layout>
