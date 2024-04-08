<x-app-layout>
    @if (session('success'))
        <x-slot name="header">
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600"
                role="alert">
                {{ session('success') }}
            </div>
        </x-slot>
    @endif


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900">
                    {{ __('Bienvenue sur Paris CareTaker Services') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
