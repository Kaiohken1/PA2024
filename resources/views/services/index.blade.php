<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion des services') }}
        </h2>
            <x-nav-link :href="route('services.create')">
                {{ __('Ajouter un nouveau service') }}
            </x-nav-link>

            <x-nav-link :href="route('documents.index')">
                {{ __('Voir les types de documents') }}
            </x-nav-link>

        @if (session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600" role="alert">
                {{ session('success') }}
            </div>
                @elseif (session('error'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600"
                role="alert">
                {{ session('error') }}
            </div>
        @endif
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full text-sm text-left text-white">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">Nom</th>
                            <th scope="col" class="px-6 py-3 text-center">Prix</th>
                            <th scope="col" class="px-6 py-3 text-center">Statut</th>
                            <th scope="col" class="px-6 py-3 text-center">Catégorie</th>
                            <th scope="col" class="px-6 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                            <tr class="bg-gray-800 border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $service->name }}</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center"> @if(!$service->flexPrice){{ $service->price }}€ @else {{_('Variable')}} @endif</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    {{$service->active_flag ? 'Activé' : 'Desactivé'}}
                                </td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{ $service->category->name }}</td>
                                <td class="flex justify-center mt-3 mb-3">
                                    <a href="{{ route('services.show', $service) }}">
                                    <button class="btn btn-info mr-3">Voir</button>
                                    </a>
                                    <a href="{{ route('services.edit', $service) }}">
                                        <button class="btn btn-success mr-3">Editer</button>
                                    </a>
                                    <form method="POST" action="{{ route('services.updateActive', $service->id) }}">
                                        @csrf
                                        @method('patch')
                                        <input type="hidden" name="active_flag" value="{{$service->active_flag}}">
                                        @if($service->active_flag)
                                            <button type="submit" class="btn btn-active btn-accent mr-3" onclick="return confirm('Êtes-vous sûr de vouloir désactiver ce service ?')">Désactiver</button>
                                        @else
                                            <button type="submit" class="btn btn-active btn-accent mr-3" onclick="return confirm('Êtes-vous sûr de vouloir activer ce service ?')">Activer</button>
                                        @endif
                                    </form>
                                    <form method="POST" action="{{ route('services.destroy', $service) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-error" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
