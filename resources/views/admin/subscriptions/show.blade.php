<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">{{$subscription->name}}</h2>
                    <img src="{{ Storage::url($subscription->logo) }}" width="200" alt="logo de l'abonnement">
                    <p><strong>Tarif mensuel :</strong> {{ $subscription->monthly_price }}€</p>
                    <p><strong>Tarif annuel :</strong> {{ $subscription->annual_price }}€</p>
                    <p><strong>Montant de la remise permanante :</strong> {{ $subscription->permanent_discount }}€</p>
                    <p><strong>Montant du bonus de renouvellement :</strong> {{ $subscription->renewal_bonus }}€</p>

                    <p><strong>Créé le:</strong> {{ \Carbon\Carbon::parse($subscription->created_at)->format('d/m/Y H:i') }}</p>
                    <p><strong>Edité le:</strong> {{ \Carbon\Carbon::parse($subscription->updated_at)->format('d/m/Y H:i') }}</p>

                    <a href="{{route('subscriptions.edit', $subscription)}}">
                        <button class="btn btn-success mt-5">Modifier</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
