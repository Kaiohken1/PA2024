<x-admin-layout>
    @foreach ($interventions as $intervention)
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="table text-white">
                            <thead class="text-white">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center">Service</th>
                                    <th scope="col" class="px-6 py-3 text-center">Prix</th>
                                    <th scope="col" class="px-6 py-3 text-center">Date de demande</th>
                                    <th scope="col" class="px-6 py-3 text-center">Client</th>
                                    <th scope="col" class="px-6 py-3 text-center">Prestataire</th>
                                    <th scope="col" class="px-6 py-3 text-center">Statut</th>
                                    @foreach ($intervention->service_parameters as $parameter)
                                        <th scope="col" class="px-6 py-3 text-center">{{$parameter->name}}</th>
                                    @endforeach
                                    <th scope="col" class="px-6 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-gray-800 border-b">
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        <a class="hover:underline" href="{{route('services.show', $intervention->service->id)}}">
                                        {{$intervention->service->name}}</a>
                                    </td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$intervention->service->price}}€</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{\Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$intervention->user->name}}</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">@if(!$intervention->provider) Pas encore attribué @else {{$intervention->provider->name}}@endif</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$intervention->statut->nom}}</td>
                                    @foreach ($intervention->service_parameters as $parameter)
                                    <th scope="col" class="px-6 py-3 text-center">{{$parameter->pivot->value}}</th>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</x-admin-layout>

