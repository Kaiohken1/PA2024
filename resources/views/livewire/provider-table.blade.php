<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-gray-900 text-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-700">
                <div class="flex items-center justify-between p-4 border-b border-gray-700">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-400 dark:text-gray-400"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input  
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-primary-500 block w-full pl-10 p-2"
                                placeholder="Rechercher" required>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-white">Statut :</label>
                            <select 
                                wire:model.live="statut"
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                <option value="">Tout</option>
                                <option value="En attente">En attente</option>
                                <option value="Validé">Validé</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-white dark:text-gray-400">
                        <thead class="text-xs text-white uppercase bg-gray-700 dark:bg-gray-700">
                            <tr>
                                @include('livewire.includes.table-sort', ['name' => 'id', 'displayName' => 'ID'])
                                <th scope="col" class="px-4 py-3">Nom</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                <th scope="col" class="px-4 py-3">Statut</th>
                                @include('livewire.includes.table-sort', ['name' => 'created_at', 'displayName' => 'COMPTE CREE LE'])
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($providers as $provider)
                                <tr wire:key="{{$provider->id}}" class="border-b border-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-white whitespace-nowrap dark:text-white">
                                    {{$provider->id}}</th>
                                    <td class="px-4 py-3">{{$provider->name}}</td>
                                    <td class="px-4 py-3">{{$provider->email}}</td>
                                    <td class="px-4 py-3
                                        {{ $provider->statut == 'En attente' ? 'text-yellow-500' : '' }}
                                        {{ $provider->statut == 'Validé' ? 'text-green-500' : '' }}
                                        {{ $provider->statut == 'Refusé' ? 'text-red-500' : '' }}">
                                        {{ $provider->statut }}
                                    </td>
                                    <td class="px-4 py-3">{{\Carbon\Carbon::parse($provider->created_at)->format('d/m/Y H:i:s')}}
                                    <td class="px-4 py-3 flex items-center justify-end"><a href="{{ route('admin.providers.show', $provider->id) }}">
                                        <button class="btn btn-info mr-3">Voir</button></a>

                                        <livewire:users :userId="$provider->user->id">
                                        <button onclick="confirm('Etes vous sûr de vouloir supprimer le prestataire {{$provider->name}}') ? '' : event.stopImmediatePropagation()" wire:click="delete({{$provider->id}})" class="btn btn-error mr-3">X</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="py-4 px-3">
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-white">Par Page</label>
                            <select
                                wire:model.live='perPage'
                                class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{$providers->links()}}
                </div>
            </div>
        </div>
    </section>
</div>
