<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-5">

                <p class="text-white">{{$intervention->planned_date}}</p>


                <table class="w-full text-sm text-left text-gray-800">
                    <thead class="border-b bg-gray-800 text-white">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">Nom</th>
                            <th scope="col" class="px-6 py-3 text-center">Email</th>
                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 text-white">
                        @foreach ($providers as $provider)
                            <tr class="border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $provider->name }}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $provider->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">

                                    <button class="btn btn-success">Attribuer</button>

                                    
                                    <a href="{{ route('admin.providers.show', $provider) }}">
                                        <button class="btn btn-info">Voir le profil</button>                                    
                                    </a>

                                    <a href="{{ route('test', $provider) }}">
                                        <button class="btn btn-active btn-primary">Voir le calendrier</button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>                
            </div>
        </div>
    </div>
</x-admin-layout>