<x-app-layout>
    <div class="flex justify-center">
    <div class="grid grid-cols-6 gap-6 w-9/12">
        @forelse ($appartements as $appartement)
            <div class="mt-9 ml-11">
                <article>
                    <img class="rounded-md" src="{{ Storage::url($appartement->images->first()->image) }}" width="200px">
                    <h1 class="text-2xl font-extrabold">{{ $appartement->name }}</h1>
                    <p><span class="font-extrabold">{{ $appartement->price }}€</span> par nuit</p>
                    @foreach ($appartement->tags as $tag)
                        <span class="bg-blue-900 text-blue-300 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-100 dark:text-blue-800">{{$tag->name}}</span>                @endforeach
                </article>
                <div class="flex">
                <a href="{{ route('appart.edit', $appartement) }}" class="mr-2">
                    <x-primary-button>Editer</x-primary-button>
                </a>
                        <form action="{{ route('appart.destroy', $appartement) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-danger-button> Supprimer</x-danger-button>
                        </form>
                    </div>
                <a href="{{ route('reservation.showAll', $appartement) }}" class="mr-2">
                    <x-primary-button>Réservations</x-primary-button>
                </a>
            </div>
            @empty
        </div>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col items-center">
                        <p class="text-center text-gray-600 text-lg">Vous n'avez aucun appartement...</p>
                        <x-primary-button class="mt-4"><a href="{{ route('appart.create') }}" class="font-bold">Mettez votte bien à disposition dès maintenant</a></x-primary-button>
                    </div>
                </div>
            </div>      
        @endforelse
    </div>
    </div>
</x-app-layout>
