<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier une catégorie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <form method="POST" action="{{ route('ticket-categories.update', $ticketCategory) }}" enctype="multipart/form-data">
                        @method('patch')    
                        @csrf

                        <div>
                            <x-input-label for="category" :value="__('Catégorie')" />
                            <x-text-input id="category" class="form-input block mt-1 w-full" type="text" name="category" :value="old('category', $ticketCategory->category)"/>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

            

                        <x-primary-button class="ms-3 mt-5 ml-0">
                            {{ __('Modifier') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>