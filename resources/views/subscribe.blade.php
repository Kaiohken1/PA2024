<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-selection.selected {
            border: 2px solid #3b82f6; /* Border color for selected card */
        }
    </style>
    <body class="bg-gray-100">
        <div class="container mx-auto p-8">
            <h2 class="text-2xl font-bold text-center mb-8">Formules d'abonnement VIP</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if ($subscription)
                <div class="bg-blue-100 p-6 rounded-lg shadow-md text-center mb-8">
                    <h5 class="text-lg mb-4">Vous êtes actuellement abonné à la formule <strong>{{ $subscription->name }}</strong>.</h5>
                    @if (in_array($subscription->name, ['Bag Packer', 'Explorator']))
                        <table class="table-auto w-full mt-5">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Avantages</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($subscription->name === 'Bag Packer')
                                    <tr class="bg-gray-200">
                                        <td class="border px-4 py-2">Commenter, publier des avis</td>
                                        <td class="border px-4 py-2 text-green-500">✔️</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-4 py-2">Réduction permanente de 5% sur les prestations</td>
                                        <td class="border px-4 py-2 text-red-500">❌</td>
                                    </tr>
                                    <tr class="bg-gray-200">
                                        <td class="border px-4 py-2">Prestations offertes</td>
                                        <td class="border px-4 py-2">1 par an dans la limite d’une prestation d’un montant inférieur à 80€</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-4 py-2">Accès prioritaire à certaines prestations et aux prestations VIP</td>
                                        <td class="border px-4 py-2 text-red-500">❌</td>
                                    </tr>
                                    <tr class="bg-gray-200">
                                        <td class="border px-4 py-2">Bonus renouvellement de l’abonnement</td>
                                        <td class="border px-4 py-2 text-red-500">❌</td>
                                    </tr>
                                @elseif ($subscription->name === 'Explorator')
                                    <tr class="bg-gray-200">
                                        <td class="border px-4 py-2">Commenter, publier des avis</td>
                                        <td class="border px-4 py-2 text-green-500">✔️</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-4 py-2">Réduction permanente de 5% sur les prestations</td>
                                        <td class="border px-4 py-2 text-green-500">✔️</td>
                                    </tr>
                                    <tr class="bg-gray-200">
                                        <td class="border px-4 py-2">Prestations offertes</td>
                                        <td class="border px-4 py-2">1 par semestre, sans limitation du montant</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-4 py-2">Accès prioritaire à certaines prestations et aux prestations VIP</td>
                                        <td class="border px-4 py-2 text-green-500">✔️</td>
                                    </tr>
                                    <tr class="bg-gray-200">
                                        <td class="border px-4 py-2">Bonus renouvellement de l’abonnement</td>
                                        <td class="border px-4 py-2">Réduction de 10% du montant de l'abonnement en cas de renouvellement, valable uniquement sur le tarif annuel.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                </div>
            @else
                <form action="{{ route('subscribe') }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="plan" id="plan">

                    <div class="flex justify-center space-x-6 mb-6">
                        <div class="max-w-sm rounded overflow-hidden shadow-lg card-selection cursor-pointer" data-plan="basic_plan">
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2">Bag Packer</div>
                                <p class="text-gray-700 text-base">Prestations offertes<br>1 par an dans la limite d’une prestation d’un montant inférieur à 80€</p>
                                <p class="font-bold text-lg text-blue-500 mt-4">9,90€/mois ou 113€/an</p>
                                <button type="button" class="btn-select-plan bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4" data-plan="basic_plan">Select Bag Packer</button>
                            </div>
                        </div>

                        <div class="max-w-sm rounded overflow-hidden shadow-lg card-selection cursor-pointer" data-plan="premium_plan">
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2">Explorator</div>
                                <p class="text-gray-700 text-base">Prestations offertes<br>1 par semestre, sans limitation du montant</p>
                                <p class="font-bold text-lg text-blue-500 mt-4">19€/mois ou 220€/an</p>
                                <button type="button" class="btn-select-plan bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4" data-plan="premium_plan">Select Explorator</button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-6">
                        <label for="interval" class="block text-gray-700 font-bold mb-2">Choisir l'intervalle de facturation :</label>
                        <select name="interval" id="interval" class="form-control block appearance-none w-1/3 bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline mx-auto">
                            <option value="monthly">Mensuel</option>
                            <option value="yearly">Annuel</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Souscrire</button>
                    </div>
                </form>

                <table class="table-auto w-full mt-10">
                    <thead>
                        <tr>
                            <th class="px-4 py-2"></th>
                            <th class="px-4 py-2 plan-header">Bag Packer</th>
                            <th class="px-4 py-2 plan-header">Explorator</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-gray-200">
                            <td class="border px-4 py-2">Présence de publicités dans le contenu consulté</td>
                            <td class="border px-4 py-2 text-center">❌</td>
                            <td class="border px-4 py-2 text-center">❌</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Commenter, publier des avis</td>
                            <td class="border px-4 py-2 text-center">✔️</td>
                            <td class="border px-4 py-2 text-center">✔️</td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td class="border px-4 py-2">Réduction permanente de 5% sur les prestations</td>
                            <td class="border px-4 py-2 text-center">❌</td>
                            <td class="border px-4 py-2 text-center">✔️</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Prestations offertes</td>
                            <td class="border px-4 py-2 text-center">1 par an dans la limite d’une prestation d’un montant inférieur à 80€</td>
                            <td class="border px-4 py-2 text-center">1 par semestre, sans limitation du montant</td>
                        </tr>
                        <tr class="bg-gray-200">
                            <td class="border px-4 py-2">Accès prioritaire à certaines prestations et aux prestations VIP</td>
                            <td class="border px-4 py-2 text-center">❌</td>
                            <td class="border px-4 py-2 text-center">✔️</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Bonus renouvellement de l’abonnement</td>
                            <td class="border px-4 py-2 text-center">❌</td>
                            <td class="border px-4 py-2 text-center">Réduction de 10% du montant de l'abonnement en cas de renouvellement, valable uniquement sur le tarif annuel.</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            $(document).ready(function() {
                @if(session('error'))
                    toastr.error("{{ session('error') }}", 'Erreur de paiement');
                @endif

                @if(session('success'))
                    toastr.success("{{ session('success') }}", 'Succès');
                @endif

                $('.btn-select-plan').click(function() {
                    $('.btn-select-plan').removeClass('selected');
                    $(this).addClass('selected');
                    $('#plan').val($(this).data('plan'));
                });

                $('.card-selection').click(function() {
                    $('.card-selection').removeClass('selected');
                    $(this).addClass('selected');
                    $('#plan').val($(this).data('plan'));
                });
            });
        </script>
    </body>
</x-app-layout>
