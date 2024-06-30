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
                                <option value="7">Refusé</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-white dark:text-gray-400">
                        <thead class="text-xs text-white uppercase bg-gray-700 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3">Nom</th>
                                <th scope="col" class="px-4 py-3">Email</th>
                                @include('livewire.includes.table-sort', ['name' => 'price', 'displayName' => 'TARIF'])
                                @include('livewire.includes.table-sort', ['name' => 'end_time', 'displayName' => 'DATE DE FIN PREVUE'])
                                <th scope="col" class="px-4 py-3">Devis</th>
                                <th scope="col" class="px-4 py-3">Statut</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estimations as $estimation)
                                <tr wire:key="{{$estimation->id}}" class="border-b border-gray-700 dark:border-gray-700">
                                    <td class="px-4 py-3">{{$estimation->provider->name}}</td>
                                    <td class="px-4 py-3">{{$estimation->provider->email}}</td>
                                    <td class="px-4 py-3">{{$estimation->price}}€</td>
                                    <td class="px-4 py-3">{{\Carbon\Carbon::parse($estimation->end_time)->format('d/m/Y H:i:s')}}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ Storage::url($estimation->estimate) }}" target="_blank">
                                            <button class="btn btn-warning">Télécharger le devis</button>                                    
                                        </a>
                                    </td>
                                    <td class="px-4 py-3
                                        {{ $estimation->statut_id == 1 ? 'text-yellow-500' : '' }}
                                        {{ $estimation->statut_id == 7 ? 'text-red-500' : '' }}">
                                        {{ $estimation->statut->nom }}
                                    </td>
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        @if($intervention->user->isAdmin())
                                            <form method="POST" action="{{route('admin.interventions.plan', $intervention->id)}}">
                                                @csrf

                                                <input type="hidden" name="price" value="{{$estimation->price}}">
                                                <input type="hidden" name="commission" value=0>
                                                <input type="hidden" name="provider_id" value="{{$estimation->provider->id}}">
                                                <input type="hidden" name="planned_end_date" value="{{$estimation->end_time}}">

                                                <button class="btn btn-success mr-2">Planifier</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{route('admin.interventions.update', $intervention->id)}}">
                                                @csrf

                                                @method('PATCH')

                                                <input type="hidden" name="price" value="{{$estimation->price}}">
                                                <input type="hidden" name="commission" value="{{$estimation->commission}}">
                                                <input type="hidden" name="provider_id" value="{{$estimation->provider->id}}">
                                                <input type="hidden" name="planned_end_date" value="{{$estimation->end_time}}">

                                                <button class="btn btn-success mr-2">Attribuer</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.providers.show', $estimation->provider) }}">
                                            <button class="btn btn-info mr-2">Voir le profil</button>                                    
                                        </a>

                                        <a href="{{ route('admin.providers.calendar', $estimation->provider->id) }}">
                                            <button class="btn btn-active btn-warning">Voir le calendrier</button>
                                        </a>
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
                    {{$estimations->links()}}
                </div>
            </div>
        </div>
    </section>
</div>
