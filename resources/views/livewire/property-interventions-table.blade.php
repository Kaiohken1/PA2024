<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Interventions effectuées sur le logement') }} {{$interventions->first()->appartement->name}}
        </h2>
    </x-slot>

    <section class="mt-10">
        <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
            <div class="bg-white text-black relative shadow-md sm:rounded-lg overflow-hidden border border-gray-300">
                <div class="flex items-center justify-between p-4 border-b border-gray-300">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500"
                                    fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input 
                                wire:model.live.debounce.300ms="search"
                                type="text"
                                class="bg-gray-100 border border-gray-300 text-black text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2"
                                placeholder="Rechercher" required>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-black">
                        <thead class="text-xs text-black uppercase bg-gray-100">
                            <tr>
                                @include('livewire.includes.table-sort', ['name' => 'id', 'displayName' => 'ID'])
                                <th scope="col" class="px-4 py-3">Service</th>
                                <th scope="col" class="px-4 py-3">Client</th>
                                <th scope="col" class="px-4 py-3">Prestataire</th>
                                @include('livewire.includes.table-sort', ['name' => 'planned_date', 'displayName' => 'EFFECTUEE LE'])
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($interventions as $intervention)
                                <tr wire:key="{{$intervention->id}}" class="border-b border-gray-300">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-black whitespace-nowrap">
                                    {{$intervention->id}}</th>
                                    <td class="px-4 py-3">{{$intervention->services->getModel()->name}}</td>
                                    <td class="px-4 py-3">{{$intervention->user->name}} {{$intervention->user->first_name}}</td>
                                    <td class="px-4 py-3">@if(!$intervention->provider) Pas encore attribué @else {{$intervention->provider->name}}@endif</td>
                                    <td class="px-4 py-3">{{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i')}}</td>
                                    <td class="px-4 py-3 flex items-center justify-end"><a href="{{ route('admin.interventions.show', $intervention->id) }}">
                                        @if($intervention->fiche)
                                        <a href="{{Storage::url($intervention->fiche)}}" target="_blank"><button class="btn btn-info">
                                            Fiche d'intervention</button>
                                        </a>
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
                            <label class="w-32 text-sm font-medium text-black">Par Page</label>
                            <select
                                wire:model.live='perPage'
                                class="bg-gray-100 border border-gray-300 text-black text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5">
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
