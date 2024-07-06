<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion des factures') }}
        </h2>
    </x-slot>
    <livewire:admin-invoices-table>
</x-admin-layout>