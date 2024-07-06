<div x-data="{ showModal: false }">
    <h2 class="text-3xl font-extrabold mt-8 mb-4 text-center">{{__('Tous les logements')}}</h2>

    <div class="flex justify-center mb-4 mt-4 space-x-4">
        <div class="flex items-center">
            <span class="mr-2 text-lg font-bold">{{ __('Filtrage') }} :</span> 
            <select wire:model="sortType" wire:change="search" class="select select-warning max-w-xs">
                <option value="latest" selected>{{__('Tri par défaut')}}</option>
                <option value="price_asc">{{__('Prix croissant')}}</option>
                <option value="price_desc">{{__('Prix décroissant')}}</option>
                <option value="surface_asc">{{__('Surface croissant')}}</option>
                <option value="surface_desc">{{__('Surface décroissant')}}</option>
                <option value="guest_count_asc">{{__('Voyageur croissant')}}</option>
                <option value="guest_count_desc">{{__('Voyageur décroissant')}}</option>
                {{-- <option value="avis_asc">{{__('Avis croissant')}}</option> --}}
                <option value="avis_desc">{{__('Avis décroissant')}}</option>
            </select>
        </div>

        <div class="flex items-center">
            <button class="btn btn-warning" @click="showModal = true">{{__('Filtrer par tags')}}</button>
        </div>
    </div>
    

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
                                <span class="text-blue-900 bg-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{ $tag->name }}</span>
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
        @if($hasMorePages)
            <button class="btn btn-warning mt-5" wire:click="loadMore">{{__('Afficher plus')}}</button>
        @endif
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="fixed z-10 inset-0 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{__('Filtrer par tags')}}</h3>
                            <div class="mt-2">
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach ($tags as $tag)
                                        <div class="flex items-center">
                                            <input type="checkbox" wire:click="toggleTag('{{ $tag->name }}')" @if(in_array($tag->name, $selectedTags)) checked @endif class="checkbox checkbox-warning">
                                            <label class="ml-2 text-sm text-gray-700">{{ $tag->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{-- <button type="button" class="btn btn-warning ml-3" wire:click="search" @click="showModal = false">Appliquer</button> --}}
                    <button type="button" class="btn btn-error" @click="showModal = false">{{__('Fermer')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
