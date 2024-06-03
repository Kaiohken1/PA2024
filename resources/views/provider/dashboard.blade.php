<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Espace Prestataire') }}
        </h2>

        @if (session('success'))
        <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-yellow-50  dark:text-green-600"
            role="alert">
            {{ session('success') }}
        </div>
    @endif
    </x-slot>

    
    
</x-app-layout>