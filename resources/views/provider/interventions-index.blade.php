<x-provider-layout>
    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    @if(!$interventions->isEmpty())
                        <table class="table">
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
                                            <a href="{{ route('interventions-provider.show', ['id' => $intervention->id]) }}">
                                                <button class="btn btn-info mr-3">Voir</button>
                                            </a>
                                            @if($intervention->statut_id == 5)
                                                <a href="{{route('interventions.chat', ['intervention' => $intervention->id, 'user' => $intervention->user_id])}}"><button class="btn mt-3">Accèder au chat</button>
                                            @endif
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            {{$interventions->links()}}
        </div>
    </div> --}}


    <livewire:provider-interventions-table>
    </x-provider-layout>
