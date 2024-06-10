<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-6">
            <div class="overflow-x-auto">
                @if(!$interventions->isEmpty())
                    <table class="table border-b">
                        <thead>
                            <tr>
                                @foreach ($interventions->last()->service->parameters as $parameter)
                                    <th scope="col" class="px-6 py-3 text-center">{{ $parameter->name }}</th>
                                @endforeach
                                <th scope="col" class="px-6 py-3 text-center">Date de demande</th>
                                <th scope="col" class="px-6 py-3 text-center">Client</th>
                                <th scope="col" class="px-6 py-3 text-center">Date prévue</th>
                                <th scope="col" class="px-6 py-3 text-center">Statut</th>
                                <th scope="col" class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($interventions as $intervention)
                                <tr class="border-b">
                                    @foreach ($interventions->last()->service->parameters as $parameter)
                                    @php
                                        $paramValue = $intervention->service_parameters->firstWhere('id', $parameter->id)->pivot->value ?? '';
                                    @endphp
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        {{ $paramValue }}
                                    </td>
                                    @endforeach
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        {{ \Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        {{ $intervention->user->name }}
                                    </td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        {{ \Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        {{ $intervention->statut->nom }}
                                    </td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        @if($showHidden)
                                            <button wire:click="showIntervention({{ $intervention->id }})" class="btn btn-success">Réafficher</button>
                                        @else
                                            <button wire:click="hideIntervention({{ $intervention->id }})" class="btn btn-danger">Masquer</button>
                                        @endif
                                        <a href="{{ route('interventions.show', ['id' => $intervention->id]) }}">
                                            <button class="btn btn-info ml-2">Voir</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $interventions->links() }}
                @else
                <p>Il n'y a aucune proposition</p>
                @endif
                <div class="flex justify-end mt-3">
                    <button wire:click="toggleShowHidden" class="btn btn-warning mb-4">
                        {{ $showHidden ? 'Afficher les interventions non masquées' : 'Afficher les interventions masquées' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
