<x-app-layout>
    @if (session('success'))
        <x-slot name="header">
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
                {{ session('success') }}
            </div>
        </x-slot>
    @endif

    <div class="flex flex-col sm:p-8 bg-white shadow sm:rounded-lg absolute w-1/9">
        @include('appartements.partials.appartement-sorting')
        @include('appartements.partials.tag-filter-appartement')
    </div>

    <div class="flex justify-center mt-5">
        <livewire:search-property />
    </div>

    <div class="flex flex-col py-9 items-center" id="base">
        <h2 class="text-3xl font-extrabold mt-4 mb-4">Les plus réservés</h2>
        <div class="grid grid-cols-4 gap-6 w-11/12 mx-auto sm:p-8 bg-white shadow sm:rounded-lg">
            @foreach ($mostReserved as $appartement)
                @include('appartements.partials.appartement-card', ['appartement' => $appartement])
            @endforeach
        </div>

        <h2 class="text-3xl font-extrabold mt-8 mb-4">Les mieux notés</h2>
        <div class="grid grid-cols-4 gap-6 w-11/12 mx-auto sm:p-8 bg-white shadow sm:rounded-lg">
            @foreach ($bestRated as $appartement)
                @include('appartements.partials.appartement-card', ['appartement' => $appartement])
            @endforeach
        </div>

        <h2 class="text-3xl font-extrabold mt-8 mb-4">Tous les appartements</h2>
        <div class="grid grid-cols-4 gap-6 w-11/12 mx-auto sm:p-8 bg-white shadow sm:rounded-lg">
            @forelse ($appartements as $appartement)
                @include('appartements.partials.appartement-card', ['appartement' => $appartement])
            @empty
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col items-center">
                            <p class="text-center text-gray-600 text-lg">
                                {{ __('Aucun appartement disponible...') }}</p>
                            <x-primary-button class="mt-4"><a href="{{ route('property.create') }}">{{ __('Et si vous proposiez le vôtre ?') }}</a></x-primary-button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $appartements->links() }}
        </div>
    </div>
</x-app-layout>



<script>
    document.addEventListener('livewire:init', () => {
       Livewire.on('search', (event) => {
           document.getElementById("base").style.display = "none";
       });
    });
</script>