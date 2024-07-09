
<x-admin-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion des tags') }}
        </h2>
        <x-nav-link class="text-white" :href="route('tags.create')">
            {{ __('Créer un tag') }}
        </x-nav-link>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
                {{ session('success') }}
            </div>
                @elseif (session('error'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
                role="alert">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div>
        <section class="mt-10">
            <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
                <div class="bg-gray-900 text-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-white dark:text-gray-400">
                            <thead class="text-xs text-white uppercase bg-gray-700 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Nom</th>
                                    <th scope="col" class="px-4 py-3">Coefficient de valorisation</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tags as $tag)
                                    <tr class="border-b border-gray-700 dark:border-gray-700">
                                        <th scope="row"
                                            class="px-4 py-3 font-medium text-white whitespace-nowrap dark:text-white">
                                        {{$tag->name}}</th>
                                        <td class="px-4 py-3">{{$tag->valorisation_coeff}}</td>
                                        <td class="px-4 py-3 flex items-center justify-end">
                                            <a href="{{ route('tags.edit', $tag) }}" class="mr-2"><button class="btn btn-success mr-3">Modifier</button></a>
                                            <form action="{{ route('tags.destroy', $tag) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-error mr-3">Supprimer</button>
                                            </form>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-admin-layout>