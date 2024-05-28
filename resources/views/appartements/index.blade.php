<x-app-layout>
    @include('appartements.partials.appartement-sorting')
    @if (session('success'))
        <x-slot name="header">
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600"
                role="alert">
                {{ session('success') }}
            </div>
        </x-slot>
    @endif
    <div class="flex justify-center">
        <div class="grid grid-cols-6 gap-6 w-9/12">
            @forelse ($appartements as $appartement)

                <div class="mt-9">
                    <a href="{{ route('property.show', $appartement) }}" class="block">
                        <article>
                            @if ($appartement->images->isNotEmpty())
                                <img class="rounded-md" src="{{ Storage::url($appartement->images->first()->image) }}"
                                    width="100%" style="height: 250px !important">
                            @else
                                <p>Aucune image disponible</p>
                            @endif
                            
                                    <h1 class="text-2xl font-extrabold">{{ $appartement->name }}</h1>
                                    <p>{{ $appartement->address }}</p>
                                    <p>Loué par {{ $appartement->user->name }}</p>
                                    
                                        
                                    <p><span class="font-extrabold">{{ $appartement->price }}€</span> par nuit</p>
                                
                                    <span class="text-xl font-extrabold size-max inline-flex"><x-ri-star-fill class="size-1/12"/>{{ $appartement->overall_rating }} | {{ $appartement->avis_count }} Avis</span>
                                    
                            @foreach ($appartement->tags as $tag)
                                <span
                                    class="bg-blue-900 text-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{ $tag->name }}</span>
                            @endforeach
                        </article>
                    </a>
                </div>
            @empty
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col items-center">
                            <p class="text-center text-gray-600 text-lg">Aucun appartement disponible...</p>
                            <x-primary-button class="mt-4"><a href="{{ route('property.create') }}">Et si vous
                                    proposiez le vôtre ?</a></x-primary-button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    @include('appartements.partials.tag-filter-appartement')
</x-app-layout>