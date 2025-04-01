<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pays') }}
            </h2>
            @if (auth()->user()->hasRole('admin'))
                <a href="{{ route('countries.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Nouveau pays') }}
                </a>
            @endif
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Nom') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Code') }}</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($countries as $country)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $country->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $country->code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if (auth()->user()->hasRole('admin'))
                                                <a href="{{ route('countries.edit', $country) }}"
                                                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                    {{ __('Modifier') }}
                                                </a>
                                                <form action="{{ route('countries.destroy', $country) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        x-data
                                                        x-on:click="$dispatch('open-modal', 'confirm-delete-country-{{ $country->id }}')"
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                        {{ __('Supprimer') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $countries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals de confirmation -->
    @foreach ($countries as $country)
        <x-modal name="confirm-delete-country-{{ $country->id }}" :show="false" focusable>
            <form method="POST" action="{{ route('countries.destroy', $country) }}" class="p-6">
                @csrf
                @method('DELETE')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Confirmer la suppression') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Êtes-vous sûr de vouloir supprimer ce pays ?') }}
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
