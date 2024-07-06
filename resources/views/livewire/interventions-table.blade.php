<div>
    @php
    $hasAdress = false;
    @endphp
    <section class="mt-10">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white text-gray-900 relative shadow-md sm:rounded-lg overflow-hidden border border-gray-300">
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
                                class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2"
                                placeholder="Rechercher" required>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="flex space-x-3 items-center">
                            <label class="w-40 text-sm font-medium text-gray-900">Devis :</label>
                            <select wire:model.live="hasEstimate" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Tout</option>
                                <option value="1">Oui</option>
                                <option value="0">Non</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    @if(!$interventions->isEmpty())
                        <table class="w-full text-sm text-left text-gray-900">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    @include('livewire.includes.table-sort', ['name' => 'id', 'displayName' => 'ID'])
                                    @foreach ($interventions->last()->service->parameters as $parameter)
                                        <th scope="col" class="px-4 py-3">{{ $parameter->name }}</th>
                                        @if($parameter->data_type_id == 1)
                                            @php
                                                $hasAdress = true;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @if($hasAdress == FALSE)<th scope="col" class="px-4 py-3">Adresse de l'appartement</th>@endif
                                    <th scope="col" class="px-4 py-3">Ville</th>
                                    <th scope="col" class="px-4 py-3">Client</th>
                                    @include('livewire.includes.table-sort', ['name' => 'planned_date', 'displayName' => 'DATE PREVUE'])
                                    @include('livewire.includes.table-sort', ['name' => 'created_at', 'displayName' => 'DATE DE DEMANDE'])
                                    <th scope="col" class="px-4 py-3">Statut</th>
                                    <th scope="col" class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($interventions as $intervention)
                                    <tr class="border-b border-gray-300">
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            #{{ $intervention->id }}
                                        </td>
                                        @foreach ($interventions->last()->service->parameters as $parameter)
                                            @php
                                                $paramValue = $intervention->service_parameters->firstWhere('id', $parameter->id)->pivot->value ?? '';
                                            @endphp
                                            <td class="px-4 py-3 font-medium whitespace-nowrap">
                                                {{ $paramValue }}
                                            </td>
                                        @endforeach
                                        @if($hasAdress == FALSE)
                                            <td class="px-4 py-3 font-medium whitespace-nowrap">
                                                {{ $intervention->appartement->address}}
                                            </td>
                                        @endif
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            {{ $intervention->appartement->city}}
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            @if($intervention->user->isAdmin())
                                            PCS
                                            @else
                                            {{ $intervention->user->name }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            @if($intervention->estimations->where('provider_id', Auth::user()->provider->id)->isNotEmpty())
                                                @if($intervention->estimations->where('provider_id', Auth::user()->provider->id)->first()->statut_id != 8)
                                                    Devis envoyé
                                                @else
                                                    Devis refusé
                                                @endif
                                            @else
                                                {{ $intervention->statut->nom }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                                            <a href="{{ route('proposals.show', ['id' => $intervention->id]) }}">
                                                <button class="btn btn-info mr-2">Voir</button>
                                            </a>
                                            @if($showHidden)
                                                <button wire:click="showIntervention({{ $intervention->id }})" class="btn btn-success">Réafficher</button>
                                            @else
                                                @if($intervention->estimations->where('provider_id', Auth::user()->provider->id)->isEmpty()) <button wire:click="hideIntervention({{ $intervention->id }})" class="btn btn-danger">Masquer</button>@endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $interventions->links() }}
                    @else
                        <p class="p-4">Il n'y a aucune proposition</p>
                    @endif
                </div>

                <div class="py-4 px-3">
                    <div class="flex justify-end mt-3">
                        <button wire:click="toggleShowHidden" class="btn btn-warning mb-4">
                            {{ $showHidden ? 'Afficher les interventions non masquées' : 'Afficher les interventions masquées' }}
                        </button>
                    </div>
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Par Page</label>
                            <select
                                wire:model.live='perPage'
                                class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
