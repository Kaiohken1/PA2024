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
        }
        .plan-price {
            font-size: 1.2em;
            font-weight: bold;
            color: #007bff;
        }
        .plan-feature {
            text-align: center;
        }
        .form-container {
            max-width: auto;
            margin: 0 auto;
            padding: 20px;
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

        <form action="{{ route('subscribe') }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="plan" id="plan">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th class="plan-header">Free</th>
                        <th class="plan-header">Bag Packer</th>
                        <th class="plan-header">Explorator</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="plan-price">Gratuit</th>
                        <th class="plan-price">9,90€/mois ou 113€/an</th>
                        <th class="plan-price">19€/mois ou 220€/an</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Présence de publicités dans le contenu consulté</td>
                        <td class="plan-feature">✔️</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">❌</td>
                    </tr>
                    <tr>
                        <td>Commenter, publier des avis</td>
                        <td class="plan-feature">✔️</td>
                        <td class="plan-feature">✔️</td>
                        <td class="plan-feature">✔️</td>
                    </tr>
                    <tr>
                        <td>Réduction permanente de 5% sur les prestations</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">✔️</td>
                    </tr>
                    <tr>
                        <td>Prestations offertes</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">1 par an dans la limite d’une prestation d’un montant inférieur à 80€</td>
                        <td class="plan-feature">1 par semestre, sans limitation du montant</td>
                    </tr>
                    <tr>
                        <td>Accès prioritaire à certaines prestations et aux prestations VIP</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">✔️</td>
                    </tr>
                    <tr>
                        <td>Bonus renouvellement de l’abonnement</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">❌</td>
                        <td class="plan-feature">Réduction de 10% du montant de l’abonnement en cas de renouvellement, valable uniquement sur le tarif annuel.</td>
                    </tr>
                </tbody>
            </table>

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

            $('.plan-feature').click(function() {
                $('.plan-feature').removeClass('selected');
                $(this).addClass('selected');
                $('#plan').val($(this).data('plan'));
            });
        });
    </script>
</body>
</html>
