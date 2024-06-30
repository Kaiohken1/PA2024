<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
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
                                <option value="1">En attente</option>
                                <option value="11">Validé</option>
                                <option value="12">Supprimé</option>
                            </select>
                        </div>
                        <button wire:click="exportCsv" class="btn btn-warning" @if($appartements->isEmpty()) disabled @endif>Exporter CSV</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-white dark:text-gray-400">
                        <thead class="text-xs text-white uppercase bg-gray-700 dark:bg-gray-700">
                            <tr>
                                @include('livewire.includes.table-sort', ['name' => 'id', 'displayName' => 'ID'])
                                <th scope="col" class="px-4 py-3">Nom</th>
                                <th scope="col" class="px-4 py-3">adresse</th>
                                <th scope="col" class="px-4 py-3">ville</th>
                                <th scope="col" class="px-4 py-3">proprietaire</th>
                                <th scope="col" class="px-4 py-3">disponibilité</th>
                                @include('livewire.includes.table-sort', ['name' => 'price', 'displayName' => 'PRIX PAR NUIT'])
                                @include('livewire.includes.table-sort', ['name' => 'created_at', 'displayName' => 'DATE DE CREATION'])
                                <th scope="col" class="px-4 py-3">statut</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appartements as $appartement)
                                <tr wire:key="{{$appartement->id}}" class="border-b border-gray-700 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-white whitespace-nowrap dark:text-white">
                                    #{{$appartement->id}}</th>
                                    <td class="px-4 py-3">{{$appartement->name}}</td>
                                    <td class="px-4 py-3">
                                        {{ $appartement->address }}
                                    </td>
                                    <td class="px-4 py-3">{{$appartement->city}}</td>
                                    <td class="px-4 py-3">{{$appartement->user->name}} {{$appartement->user->first_name}}</td>
                                    <td class="px-4 py-3                                        
                                        {{ $appartement->active_flag ? 'text-green-500' : 'text-red-500' }}">
                                         @if($appartement->active_flag)Ouvert @else Fermé @endif
                                    </td>
                                    <td class="px-4 py-3">{{$appartement->price}}€</td>
                                    <td class="px-4 py-3">{{\Carbon\Carbon::parse($appartement->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="px-4 py-3 
                                        {{ $appartement->statut->id == 1 ? 'text-yellow-500' : '' }}
                                        {{ $appartement->statut->id == 11 ? 'text-green-500' : '' }}
                                         {{ $appartement->statut->id == 12 ? 'text-red-500' : '' }}">
                                    {{ $appartement->statut->nom }}
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        <a href="{{ route('admin.property.show', $appartement->id) }}"> 
                                        <button class="btn btn-info mr-3">Voir</button></a>

                                        <a href="{{ route('admin.interventions.create', $appartement->id) }}"> 
                                            <button class="btn btn-success mr-3">Intervention</button></a>
                                        <button onclick="confirm('Etes vous sûr de vouloir supprimer l\'appartement #{{$appartement->id}}') ? '' : event.stopImmediatePropagation()" wire:click="delete({{$appartement->id}})" class="btn btn-error mr-3 {{$appartement->deleted_at === NULL ? 'visible' : 'invisible'}}">X</button>
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
                    {{$appartements->links()}}
                </div>
            </div>
        </div>
    </section>
</div>
