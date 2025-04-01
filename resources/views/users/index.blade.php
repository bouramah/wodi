<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Utilisateurs') }}
            </h2>
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('agent'))
                <a href="{{ route('users.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Nouvel utilisateur') }}
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

                    <div x-data="{ activeTab: 'clients' }">
                        <div class="mb-4">
                            <div class="border-b border-gray-200">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <button @click="activeTab = 'clients'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'clients', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'clients' }"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                        {{ __('Clients') }}
                                    </button>
                                    @if (auth()->user()->hasRole('admin'))
                                        <button @click="activeTab = 'agents'"
                                            :class="{ 'border-blue-500 text-blue-600': activeTab === 'agents', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'agents' }"
                                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                            {{ __('Agents & Admins') }}
                                        </button>
                                    @endif
                                </nav>
                            </div>
                        </div>

                        <div x-show="activeTab === 'clients'">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Nom') }}</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Téléphone') }}</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Rôles') }}</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                {{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($clients as $user)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->first_name }} {{ $user->last_name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $user->phone_number }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">
                                                        {{ $user->getRoleNames()->implode(', ') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('users.edit', $user) }}"
                                                        class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                        {{ __('Modifier') }}
                                                    </a>
                                                    @if (auth()->user()->hasRole('admin'))
                                                        <button type="button"
                                                            x-data
                                                            x-on:click="$dispatch('open-modal', 'confirm-reset-{{ $user->id }}')"
                                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                            {{ __('Réinitialiser mot de passe') }}
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $clients->links() }}
                            </div>
                        </div>

                        @if (auth()->user()->hasRole('admin') && $agents)
                            <div x-show="activeTab === 'agents'">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Nom') }}</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Téléphone') }}</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Rôles') }}</th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($agents as $user)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $user->first_name }} {{ $user->last_name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $user->phone_number }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $user->getRoleNames()->implode(', ') }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('users.edit', $user) }}"
                                                            class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                            {{ __('Modifier') }}
                                                        </a>
                                                        @if ($user->hasRole('agent'))
                                                            <a href="{{ route('users.transfers', $user) }}"
                                                                class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2">
                                                                {{ __('Voir les transferts') }}
                                                            </a>
                                                        @endif
                                                        @if (auth()->user()->hasRole('admin'))
                                                            <button type="button"
                                                                x-data
                                                                x-on:click="$dispatch('open-modal', 'confirm-reset-{{ $user->id }}')"
                                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                                                {{ __('Réinitialiser mot de passe') }}
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4">
                                    {{ $agents->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals de confirmation de réinitialisation de mot de passe -->
    @foreach($clients as $user)
        <x-modal name="confirm-reset-{{ $user->id }}" :show="false" focusable>
            <form method="POST" action="{{ route('users.reset-password', $user) }}" class="p-6">
                @csrf

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Réinitialiser le mot de passe') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Êtes-vous sûr de vouloir réinitialiser le mot de passe ? Le mot de passe sera réinitialisé à "00000000".') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Annuler') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        {{ __('Réinitialiser') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach

    @if(isset($agents))
        @foreach($agents as $user)
            <x-modal name="confirm-reset-{{ $user->id }}" :show="false" focusable>
                <form method="POST" action="{{ route('users.reset-password', $user) }}" class="p-6">
                    @csrf

                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Réinitialiser le mot de passe') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Êtes-vous sûr de vouloir réinitialiser le mot de passe ? Le mot de passe sera réinitialisé à "00000000".') }}
                    </p>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Annuler') }}
                        </x-secondary-button>

                        <x-danger-button class="ml-3">
                            {{ __('Réinitialiser') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        @endforeach
    @endif
</x-app-layout>
