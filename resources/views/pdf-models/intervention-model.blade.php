<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: white;
        padding: 24px;
    }
    .container {
        width: 100%;
        margin-bottom: 24px;
    }
    .text-right {
        text-align: right;
    }
    .text-xl {
        font-size: 1.125rem;
        font-weight: bold;
    }
    .text-lg {
        font-size: 1rem;
        font-weight: bold;
    }
    .font-bold {
        font-weight: bold;
    }
    .font-normal {
        font-weight: normal;
    }
    .border {
        border: 1px solid #ccc;
    }
    .px-4 {
        padding-left: 16px;
        padding-right: 16px;
    }
    .py-2 {
        padding-top: 8px;
        padding-bottom: 8px;
    }
    .mb-1 {
        margin-bottom: 4px;
    }
    .mb-6 {
        margin-bottom: 24px;
    }
    .ml {
        margin-left:25em;
    }
    .bg-gray-200 {
        background-color: #e2e8f0;
    }
    .bg-white {
        background-color: white;
    }
    .w-full {
        width: 100%;
    }
    .flex {
        display: flex;
    }
    .justify-end {
        justify-content: flex-end;
    }
    .text-center {
        text-align: center;
    }
    .signature-box {
        width: 150px;
        height: 45px;
        background-color: white;
        margin-top: 16px;
        border: 1px solid black;
    }
    .section-title {
        font-size: 1rem;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .section-content {
        border: 1px solid #eeb90c;
        padding: 16px;
        min-height: 65px;
    }
</style>
</head>
<body>

<div>
    <table class="w-full mb-6 container">
        <tr>
            <td valign="top">
                <img width="150" class="mb-1" src="{{ 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('logo/logo3.svg'))) }}">
            </td>
            <td class="text-right">
                <h3 class="text-xl">Paris CareTaker Services</h3>
                <pre>
                    23 rue Montrogueil
                    75002, Paris
                    0612345678
                </pre>
            </td>
        </tr>
    </table>

    <!-- Facture Information -->
    <div class="mb-6">
        <p class="text-xl">FICHE D'INTERVENTION</p>
        <p><span class="text-lg">INTERVENTION EFFECTUÉE PAR :</span> {{$intervention->provider->name}}</p>
        <p><span class="text-lg">À L'INTENTION DE :</span> {{$intervention->user->name}} {{$intervention->user->first_name}}</p>
        <p><span class="text-lg">SERVICE :</span> {{$intervention->service->name}}</p>
        <p><span class="text-lg">DEBUT D'INTERVENTION :</span> {{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y à H:i:s')}}</p>
        <p><span class="text-lg">FIN D'INTERVENTION :</span> </p>
    </div>

    <div class="container">
        <div class="section">
            <div class="section-title">Observations</div>
            <div class="section-content">{{ $intervention->observations }}</div>
        </div>
        <div class="section">
            <div class="section-title">Description</div>
            <div class="section-content"></div>
        </div>
    </div>
</div>

<div class="flex justify-end">
    <img width="300" class="ml" src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo/signature.png'))) }}">  
</div>

<div class="flex justify-end">
    <div class="signature-box"></div>
    <div>
        <p>Date et lieu de signature : </p>
    </div>
</div>

</body>
</html>
