<div>
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white text-gray-900 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-200">
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2"
                                placeholder="Rechercher" required>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900">Statut :</label>
                            <select 
                                wire:model.live="statut"
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                <option value="">Tout</option>
                                <option value="1">En attente</option>
                                <option value="5">Payée</option>
                                <option value="3">Terminée</option>
                                <option value="4">Refusée</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-900">
                        <thead class="text-xs text-gray-900 uppercase bg-gray-100">
                            <tr>
                                @include('livewire.includes.table-sort', ['name' => 'id', 'displayName' => 'ID'])
                                <th scope="col" class="px-4 py-3">Statut</th>
                                @include('livewire.includes.table-sort', ['name' => 'created_at', 'displayName' => 'DATE DE DEMANDE'])
                                <th scope="col" class="px-4 py-3">Client</th>
                                @include('livewire.includes.table-sort', ['name' => 'planned_date', 'displayName' => 'DATE PREVUE'])
                                @include('livewire.includes.table-sort', ['name' => 'price', 'displayName' => 'PRIX'])
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interventions as $intervention)
                                <tr wire:key="{{$intervention->id}}" class="border-b border-gray-200">
                                    <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                        {{$intervention->id}}
                                    </th>
                                    <td class="px-4 py-3 
                                        {{ $intervention->statut->id == 1 ? 'text-yellow-500' : '' }}
                                        {{ $intervention->statut->id == 3 ? 'text-green-500' : '' }}
                                        {{ $intervention->statut->id == 4 ? 'text-red-500' : '' }}
                                        {{ $intervention->statut->id == 5 ? 'text-yellow-500' : '' }}">
                                        {{ $intervention->statut->nom }}
                                    </td>
                                    <td class="px-4 py-3">{{\Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i')}}</td>
                                    <td class="px-4 py-3">{{$intervention->user->name}} {{$intervention->user->first_name}}</td>
                                    <td class="px-4 py-3">{{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i')}}</td>
                                    <td class="px-4 py-3">@if($intervention->price){{$intervention->price + ($intervention->price*0.20)}}€@endif</td>
                                    <td class="px-4 py-3 flex items-center justify-end">
                                        @if($intervention->statut_id == 5 || $intervention->statut_id == 3)
                                        <a href="{{ route('interventions-provider.show', $intervention->id) }}">
                                            <button class="btn btn-info mr-3">Voir</button>
                                        </a>
                                        @else
                                        <a href="{{ route('interventions.show', $intervention->id) }}">
                                            <button class="btn btn-info mr-3">Voir</button>
                                        </a>
                                        @endif
                                        @if($intervention->statut_id == 5)
                                        <a href="{{route('interventions.chat', ['intervention' => $intervention->id, 'user' => $intervention->user_id])}}"><button class="btn btn-success mr-3">Accèder au chat</button>
                                        @endif                                    
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="py-4 px-3">
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Par Page</label>
                            <select
                                wire:model.live='perPage'
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{$interventions->links()}}
                </div>
            </div>
        </div>
    </section>
</div>
