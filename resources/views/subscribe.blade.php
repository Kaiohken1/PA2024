<x-app-layout>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Formules d'abonnement VIP</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <style>
            .plan-header {
                font-weight: bold;
                text-align: center;
                background-color: #007bff;
                color: #fff;
                padding: 10px;
            }
            .plan-price {
                font-size: 1.2em;
                font-weight: bold;
                color: #007bff;
                padding: 10px 0;
            }
            .plan-feature {
                text-align: center;
                padding: 10px;
            }
            .form-container {
                max-width: auto;
                margin: 0 auto;
                padding: 20px;
            }
            .btn-select-plan {
                display: block;
                width: 100%;
                margin-top: 10px;
            }
            .card-selection {
                cursor: pointer;
                transition: all 0.3s ease-in-out;
                margin-bottom: 20px;
            }
            .card-selection:hover {
                transform: scale(1.05);
            }
            .card-selection.selected {
                border: 2px solid #007bff;
            }
            .advantage-table {
                margin-top: 30px;
            }
            .advantage-table th, .advantage-table td {
                border: none;
                text-align: center;
                padding: 10px;
            }
            .advantage-table thead {
                background-color: #f8f9fa;
            }
            .advantage-table tbody tr:nth-child(odd) {
                background-color: #e9ecef;
            }
        </style>
    </head>
    <body>
        <div class="container form-container">
            <h2 class="text-center">Formules d'abonnement VIP</h2>

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

            @if ($subscription && $subscription->stripe_status === 'active')
                <div class="alert alert-info text-center">
                    <h5 class="text-center mb-4">Vous êtes actuellement abonné à la formule <strong>{{ $plan['name'] }}</strong>.</h5>
                    @if (in_array($plan['name'], ['Bag Packer', 'Explorator']))
                        <table class="table advantage-table mt-5">
                            <thead>
                                <tr>
                                    <th>Avantages</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($plan['name'] === 'Bag Packer')
                                    {{-- <tr>
                                        <td>Présence de publicités dans le contenu consulté</td>
                                        <td class="plan-feature">❌</td>
                                    </tr> --}}
                                    <tr>
                                        <td>Commenter, publier des avis</td>
                                        <td class="plan-feature">✔️</td>
                                    </tr>
                                    <tr>
                                        <td>Réduction permanente de 5% sur les prestations</td>
                                        <td class="plan-feature">❌</td>
                                    </tr>
                                    <tr>
                                        <td>Prestations offertes</td>
                                        <td class="plan-feature">1 par an dans la limite d’une prestation d’un montant inférieur à 80€</td>
                                    </tr>
                                    <tr>
                                        <td>Accès prioritaire à certaines prestations et aux prestations VIP</td>
                                        <td class="plan-feature">❌</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus renouvellement de l’abonnement</td>
                                        <td class="plan-feature">❌</td>
                                    </tr>
                                @elseif ($plan['name'] === 'Explorator')
                                    {{-- <tr>
                                        <td>Présence de publicités dans le contenu consulté</td>
                                        <td class="plan-feature">❌</td>
                                    </tr> --}}
                                    <tr>
                                        <td>Commenter, publier des avis</td>
                                        <td class="plan-feature">✔️</td>
                                    </tr>
                                    <tr>
                                        <td>Réduction permanente de 5% sur les prestations</td>
                                        <td class="plan-feature">✔️</td>
                                    </tr>
                                    <tr>
                                        <td>Prestations offertes</td>
                                        <td class="plan-feature">1 par semestre, sans limitation du montant</td>
                                    </tr>
                                    <tr>
                                        <td>Accès prioritaire à certaines prestations et aux prestations VIP</td>
                                        <td class="plan-feature">✔️</td>
                                    </tr>
                                    <tr>
                                        <td>Bonus renouvellement de l’abonnement</td>
                                        <td class="plan-feature">Réduction de 10% du montant de l'abonnement en cas de renouvellement, valable uniquement sur le tarif annuel.</td>
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

                    <div class="row text-center">
                        <div class="col-md-6">
                            <div class="card card-selection" data-plan="basic_plan">
                                <div class="card-body">
                                    <h5 class="card-title">Bag Packer</h5>
                                    <p class="card-text">Prestations offertes<br>1 par an dans la limite d’une prestation d’un montant inférieur à 80€</p>
                                    <p class="card-price"><strong>9,90€/mois ou 113€/an</strong></p>
                                    <button type="button" class="btn btn-outline-primary btn-select-plan" data-plan="basic_plan">Select Bag Packer</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-selection" data-plan="premium_plan">
                                <div class="card-body">
                                    <h5 class="card-title">Explorator</h5>
                                    <p class="card-text">Prestations offertes<br>1 par semestre, sans limitation du montant</p>
                                    <p class="card-price"><strong>19€/mois ou 220€/an</strong></p>
                                    <button type="button" class="btn btn-outline-primary btn-select-plan" data-plan="premium_plan">Select Explorator</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <label for="interval">Choisir l'intervalle de facturation :</label>
                        <select name="interval" id="interval" class="form-control w-50 mx-auto">
                            <option value="monthly">Mensuel</option>
                            <option value="yearly">Annuel</option>
                        </select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Souscrire</button>
                    </div>
                </form>

                <table class="table advantage-table mt-5">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="plan-header">Bag Packer</th>
                            <th class="plan-header">Explorator</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Présence de publicités dans le contenu consulté</td>
                            <td class="plan-feature">❌</td>
                            <td class="plan-feature">❌</td>
                        </tr>
                        <tr>
                            <td>Commenter, publier des avis</td>
                            <td class="plan-feature">✔️</td>
                            <td class="plan-feature">✔️</td>
                        </tr>
                        <tr>
                            <td>Réduction permanente de 5% sur les prestations</td>
                            <td class="plan-feature">❌</td>
                            <td class="plan-feature">✔️</td>
                        </tr>
                        <tr>
                            <td>Prestations offertes</td>
                            <td class="plan-feature">1 par an dans la limite d’une prestation d’un montant inférieur à 80€</td>
                            <td class="plan-feature">1 par semestre, sans limitation du montant</td>
                        </tr>
                        <tr>
                            <td>Accès prioritaire à certaines prestations et aux prestations VIP</td>
                            <td class="plan-feature">❌</td>
                            <td class="plan-feature">✔️</td>
                        </tr>
                        <tr>
                            <td>Bonus renouvellement de l’abonnement</td>
                            <td class="plan-feature">❌</td>
                            <td class="plan-feature">Réduction de 10% du montant de l'abonnement en cas de renouvellement, valable uniquement sur le tarif annuel.</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
    </html>
</x-app-layout>
