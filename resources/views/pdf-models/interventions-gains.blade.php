@php
$totalGlobal = 0;
@endphp
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
    
    table {
        table-layout: fixed;
        width: 100%;
    }

    th, td {
        width: 20%;
    }
</style>
</head>
<body>

  <div>
    <table class="w-full mb-6 container">
      <tr>
        <td valign="top"><img width="150" class="mb-1" src="{{ 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('logo/logo3.svg'))) }}"></td>        <td class="text-right">
          <h3 class="text-xl">Paris CareTaker Services</h3>
          <pre>
            23 rue Montrogueil
            75002, Paris
            0612345678
          </pre>
        </td>
      </tr>
    </table>

    <div class="mb-6">
      <p class="text-xl">FACTURE - INTERVENTION</p>
      <p class="text-lg">DATE DE DÉLIVRANCE : <span class="font-normal">{{\Carbon\Carbon::now()->format('d/m/Y')}}</p>
      <p><span  class="text-lg">À L'INTENTION DE :</span> {{$interventions->first()->provider->name}}</p>
    </div>

    <table class="w-full bg-white border">
      <thead class="bg-gray-200">
        <tr>
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Effectué le</th>
          <th class="border px-4 py-2">Prix TTC</th>
          <th class="border px-4 py-2">Prix HT</th>
          <th class="border px-4 py-2">Commission</th>
        </tr>
      </thead>
      <tbody>
        @foreach($interventions as $intervention)
            @php
                $commission = $intervention->commission;
                $prix = $intervention->price;  
                $prixTVA = $intervention->price + ($intervention->price*0.20);
                $total = $prixTVA - $commission;
                $totalGlobal += $total;
            @endphp
            <tr class="bg-white">
            <th scope="row" class="border px-4 py-2">{{$intervention->id}}</th>
            <td class="border px-4 py-2 text-center">{{\Carbon\Carbon::parse($intervention->planned_date)->format('d/m/Y à H:i:s')}} </td>
            <td class="border px-4 py-2 text-right">{{$intervention->price + ($intervention->price*0.20)}}€</td>
            <td class="border px-4 py-2 text-right">{{$intervention->price}}€</td>
            <td class="border px-4 py-2 text-right">{{$intervention->commission}}€</td>
            </tr>
        @endforeach
        <tr>
            <td class="border px-4 py-2 text-right font-bold" colspan="5">Total à verser</td>
            <td class="border px-4 py-2 text-right font-bold">{{ $totalGlobal }} €</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="flex justify-end">
    <img width="300" class="mb-1 ml" src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo/signature.png'))) }}">  
</div>

</body>
</html>
