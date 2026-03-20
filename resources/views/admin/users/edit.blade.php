<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="post" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email (Cannot be changed)')" />
                            <x-text-input id="email" type="email" class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed" :value="$user->email" disabled />
                        </div>

                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @if(auth()->id() === $user->id)
                                <p class="text-sm text-gray-500 mt-1">You cannot change your own role.</p>
                            @endif
                        </div>

                        @if($user->role !== 'admin')
                        <div>
                            <x-input-label for="is_active" :value="__('Status')" />
                            <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="is_active" value="1">
                            <div>
                                <x-input-label for="is_active_display" :value="__('Status')" />
                                <x-text-input id="is_active_display" type="text" class="mt-1 block w-full bg-gray-100 text-gray-500 cursor-not-allowed" value="Active (Admins are always active)" disabled />
                            </div>
                        @endif

                        <div class="flex items-center gap-4 mt-4">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            @if(auth()->id() !== $user->id)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mt-6">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-red-600 mb-4">Danger Zone</h3>
                    <form method="post" action="{{ route('admin.users.destroy', $user->id) }}">
                        @csrf
                        @method('delete')
                        <x-danger-button onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            {{ __('Delete User') }}
                        </x-danger-button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>