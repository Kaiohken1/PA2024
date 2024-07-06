<x-admin-layout>
    <livewire:provider-calendar :provider_id="request()->route('id')">
    @stack('scripts')
</x-admin-layout>