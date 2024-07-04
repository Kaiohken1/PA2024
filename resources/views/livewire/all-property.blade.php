<div>
    <h2 class="text-3xl font-extrabold mt-8 mb-4 text-center">Tous les appartements</h2>
    <div class="grid grid-cols-4 gap-6 w-11/12 mx-auto sm:p-8 bg-white shadow sm:rounded-lg">
        @forelse ($appartements as $appartement)
            @php
            $mainImages = $appartement->images()->where('is_main', true)->take(4)->get();
            
            $rest = 4 - $mainImages->count();
            
            $otherImages = $appartement->images()->where('is_main', false)->take($rest)->get();
            
            $propertyImages = $mainImages->merge($otherImages);
            
            @endphp
            <div>
                <a href="{{ route('property.show', $appartement) }}" class="block">
                    <article>
                        @if ($appartement->images->isNotEmpty())
                            <img class="rounded-md w-full aspect-square" src="{{ Storage::url($propertyImages->first()->image) }}">
                        @else
                            <img src="https://a0.muscache.com/im/pictures/miso/Hosting-44651637/original/f5dd7acf-bd3c-42bc-9ee9-16826d8f6b5f.jpeg?im_w=720" class="rounded-md w-full aspect-square">
                        @endif
            
                        <h1 class="text-2xl font-extrabold">{{ $appartement->name }}</h1>
                        <p>{{ $appartement->address }}</p>
                        <p>{{ __('Loué par') }} {{ $appartement->user->name }}</p>
                        <p><span class="font-extrabold">{{ $appartement->price }}€</span> {{ __('par nuit') }}</p>
                        <span class="font-extrabold size-max inline-flex">
                            <x-ri-star-fill class="size-6" />
                            {{ $appartement->overall_rating }} | {{ $appartement->avis_count }} {{ __('Avis') }}
                        </span>
                        <div>
                            @foreach ($appartement->tags as $tag)
                                <span class="bg-blue-900 text-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </article>
                </a>
            </div>
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
    <div class="flex justify-center">
        <button class="btn btn-warning mt-5 " wire:click="loadMore">Afficher plus</button>
    </div>
</div>
