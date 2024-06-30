<x-admin-layout>
    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">
                @if(!$providers->isEmpty())
                    <table class="w-full text-sm text-left text-gray-800">
                        <thead class="border-b bg-gray-800 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">Nom</th>
                                <th scope="col" class="px-6 py-3 text-center">Email</th>
                                <th scope="col" class="px-6 py-3 text-center">Tarif</th>
                                <th scope="col" class="px-6 py-3 text-center">Date de fin prévue</th>
                                <th scope="col" class="px-6 py-3 text-center">Devis</th>
                                <th scope="col" class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 text-white">
                            @foreach ($providers as $provider)
                                <tr class="border-b">
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $provider->name }}</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $provider->email }}</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $provider->estimations()->where('intervention_id', $intervention->id)->first()->price}}€</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ \Carbon\Carbon::parse($provider->estimations()->where('intervention_id', $intervention->id)->first()->end_time)->format('d/m/Y à H:i:s')}}</td>
                                    <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                        <a href="{{ Storage::url($provider->estimations->first()->estimate) }}" target="_blank">
                                            <button class="btn btn-primarty">Télécharger le devis</button>                                    
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form method="POST" action="{{route('admin.interventions.update', $intervention->id)}}">
                                            @csrf

                                            @method('PATCH')

                                            <input type="hidden" name="price" value="{{$provider->estimations()->where('intervention_id', $intervention->id)->first()->price}}">
                                            <input type="hidden" name="commission" value="{{$provider->estimations()->where('intervention_id', $intervention->id)->first()->commission}}">
                                            <input type="hidden" name="provider_id" value="{{$provider->id}}">
                                            <input type="hidden" name="planned_end_date" value="{{$provider->estimations()->where('intervention_id', $intervention->id)->first()->end_time}}">

                                            <button class="btn btn-success">Attribuer</button>
                                        </form>
                                        <a href="{{ route('admin.providers.show', $provider) }}">
                                            <button class="btn btn-info">Voir le profil</button>                                    
                                        </a>

                                        <a href="{{ route('admin.providers.calendar', $provider->id) }}">
                                            <button class="btn btn-active btn-primary">Voir le calendrier</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-white text-center">Aucun prestataire disponible</p>     
                @endif           
            </div>
        </div>
    </div> --}}

    <livewire:availability-table :interventionId="$intervention->id">
</x-admin-layout>