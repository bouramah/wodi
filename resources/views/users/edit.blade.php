<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier l\'utilisateur') }}: {{ $user->first_name }} {{ $user->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="first_name" :value="__('Prénom')" />
                            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $user->first_name)" required autofocus />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="last_name" :value="__('Nom')" />
                            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $user->last_name)" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone_number" :value="__('Numéro de téléphone')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $user->phone_number)" required />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="country_id" :value="__('Pays')" />
                            <select id="country_id" name="country_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ (old('country_id', $user->country_id) == $country->id) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>

                        @if(auth()->user()->hasRole('admin'))
                            <div>
                                <x-input-label for="roles" :value="__('Rôles')" />
                                <div class="mt-2 space-y-2">
                                    @foreach($roles as $role)
                                        <div class="flex items-center">
                                            <input id="role_{{ $role->id }}" name="roles[]" type="checkbox" value="{{ $role->name }}"
                                                {{ (is_array(old('roles')) && in_array($role->name, old('roles')))
                                                    || (!is_array(old('roles')) && $user->hasRole($role->name)) ? 'checked' : '' }}
                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="role_{{ $role->id }}" class="ml-2 block text-sm text-gray-900">
                                                {{ ucfirst($role->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Annuler') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
