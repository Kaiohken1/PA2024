<div>
    <div class="flex justify-center">
        <input type="text" wire:model="city" placeholder="Ville" />
        <input type="text" wire:model="start_time" id="start_time" placeholder="Date d'arrivée" />
        <input type="text" wire:model="end_time" id="end_time" placeholder="Date de départ"/>
        <input type="number" wire:model="guestCount" placeholder="Voyageurs"/>
        <button wire:click="search">Rechercher</button>
    </div>

    @if ($appartements)
        <div class="flex justify-around mt-10">
            <div class="grid grid-cols-4 gap-6 w-8/12 sm:p-8 bg-white shadow sm:rounded-lg ">
                @forelse ($appartements as $appartement)
                    <div>
                        <a href="{{ route('property.show', $appartement) }}" class="block">
                            <article>
                                @if ($appartement->images->isNotEmpty())
                                    <img class="rounded-md w-full aspect-square"
                                        src="{{ Storage::url($appartement->images->first()->image) }}">
                                @else
                                    <p>{{ __('Aucune image disponible') }}</p>
                                @endif

                                <h1 class="text-2xl font-extrabold">{{ $appartement->name }}</h1>
                                <p>{{ $appartement->address }}</p>
                                <p>{{ __('Loué par') }} {{ $appartement->user->name }}</p>


                                <p><span class="font-extrabold">{{ $appartement->price }}€</span> {{ __('par nuit') }}
                                </p>

                                <span class="font-extrabold size-max inline-flex"><x-ri-star-fill
                                        class="size-6" />{{ $appartement->overall_rating }} |
                                    {{ $appartement->avis_count }} {{ __('Avis') }}</span>
                                <div>
                                    @foreach ($appartement->tags as $tag)
                                        <span
                                            class="bg-blue-900 text-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{ $tag->name }}</span>
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
                                <x-primary-button class="mt-4"><a
                                        href="{{ route('property.create') }}">{{ __('Et si vous
                                                                            proposiez le vôtre ?') }}</a></x-primary-button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

    @endif
</div>

<script>
    document.addEventListener('livewire:init', function () {
        flatpickr("#start_time", {
            mode: "range",
            dateFormat: "d-m-Y",
            minDate: "today",
            showMonths: 2,
            locale: "fr",
            onClose: function(selectedDates) {
                if (selectedDates.length === 2) {
                    var start = selectedDates[0];
                    var end = selectedDates[1];
                    document.getElementById("start_time").value = formatDate(start);
                    document.getElementById("end_time").value = formatDate(end, true);
                    @this.set('end_time', formatDate(end));
                } else if (selectedDates.length === 1) {
                    var start = selectedDates[0];
                    document.getElementById("start_time").value = formatDate(start);
                    document.getElementById("end_time").value = '';
                } else {
                    document.getElementById("start_time").value = '';
                    document.getElementById("end_time").value = '';
                }
            }
        });

        function formatDate(date, endOfDay = false) {
            if (endOfDay) {
                date.setHours(23, 59, 59, 999);
            }
            var year = date.getFullYear();
            var month = (date.getMonth() + 1).toString().padStart(2, "0");
            var day = date.getDate().toString().padStart(2, "0");
            return day + "-" + month + "-" + year;
        }
    });
</script>

