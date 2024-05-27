<x-provider-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="table text-white">
                        <thead class="text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">Date de demande</th>
                                <th scope="col" class="px-6 py-3 text-center">Client</th>
                                <th scope="col" class="px-6 py-3 text-center">Date prévue</th>
                                <th scope="col" class="px-6 py-3 text-center">Statut</th>
                                <th scope="col" class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($interventions as $intervention)
                            <tr class="bg-gray-800 border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{\Carbon\Carbon::parse($intervention->created_at)->format('d/m/Y H:i:s')}}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$intervention->user->name}}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y H:i:s')}}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$intervention->statut->nom}}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center"><a href="{{ route('intverventions.show', $intervention->id) }}">
                                    <button class="btn btn-info mr-3">Voir</button></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-provider-layout>

