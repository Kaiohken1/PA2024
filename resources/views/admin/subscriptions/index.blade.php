<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion des abonnements') }}
        </h2>

        @if(session('success'))
            <div class="p-4 mb-3 mt-3 text-center text-sm text-yellow-800 rounded-lg bg-yellow-50  dark:text-yellow-600" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="table text-white">
                        <thead class="text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center">Nom</th>
                                <th scope="col" class="px-6 py-3 text-center">Prix Mensuel</th>
                                <th scope="col" class="px-6 py-3 text-center">Prix annuel</th>                                
                                <th scope="col" class="px-6 py-3 text-center">Remise permanante</th>
                                <th scope="col" class="px-6 py-3 text-center">Bonus de renouvellement</th>
                                <th scope="col" class="px-6 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscriptions as $subscription)
                            <tr class="bg-gray-800 border-b">
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">
                                    <a class="hover:underline" href="{{route('subscriptions.show', $subscription->id)}}">
                                    {{$subscription->name}}</a>
                                </td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$subscription->monthly_price}}€</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$subscription->annual_price}}€</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$subscription->permanent_discount}}€</td>
                                <td class="px-6 py-4 font-medium whitespace-nowrap text-center">{{$subscription->renewal_bonus}}€</td>

                                <td class="flex justify-center mt-3 mb-3"><a href="{{ route('subscriptions.show', $subscription->id) }}">
                                    <button class="btn btn-info mr-3">Voir</button></a>

                                    <a href="{{ route('subscriptions.edit', $subscription) }}">
                                        <button class="btn btn-success mr-3">Editer</button>
                                    </a>

                                    <form method="POST" action="{{ route('subscriptions.destroy', $subscription) }}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-error" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement ?')">Supprimer</button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>

