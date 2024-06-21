<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'figtree', sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .logo {
            display: block;
            margin: 20px auto;
            max-width: 200px;
            height: auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contrat de Prestation de Service</h1>
        <p>Nom : {{ $provider->name }}</p>
        <p>Email : {{ $provider->email }}</p>
        <p>Date d'inscription : {{ \Carbon\Carbon::parse($provider->created_at)->format('d/m/Y') }}</p>
    </div>
</body>
</html>
