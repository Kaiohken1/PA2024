@php
    $tauxTVA = 0.20; 
    $totalAVerserGlobal = 0;
@endphp

<!doctype html>
<html lang="fr">
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
        margin-left: 25em;
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
    .overflow-hidden {
        overflow: hidden;
    }
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }
    .table-wrapper table {
        width: 100%;
        table-layout: fixed;
    }
    th, td {
        word-wrap: break-word;
    }
    .date-column {
        min-width: 90px;
    }
</style>
</head>
<body>

  <div>
    <table class="w-full mb-6 container">
      <tr>
        <td valign="top"><img width="150" class="mb-1" src="{{ 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('logo/logo3.svg'))) }}"></td>
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

    <div class="mb-6">
      <p class="text-xl">FACTURE GAINS RESERVATIONS - {{ \Carbon\Carbon::now()->format('m/Y') }}</p>
      <p class="text-lg">DATE DE DÉLIVRANCE : <span class="font-normal">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span></p>
      <p><span class="text-lg">À L'INTENTION DE :</span> {{$reservations->first()->appartement->user->name}} {{$reservations->first()->appartement->user->first_name}}</p>
    </div>

    <div class="table-wrapper">
      <table class="bg-white border">
        <thead class="bg-gray-200">
          <tr>
            <th class="border px-4 py-2">Bien</th>
            <th class="border px-4 py-2 date-column">Du</th>
            <th class="border px-4 py-2 date-column">Au</th>
            <th class="border px-4 py-2 text-right">Prix TTC</th>
            <th class="border px-4 py-2 text-right">Prix HT</th>
            <th class="border px-4 py-2 text-right">Frais PCS</th>
            <th class="border px-4 py-2 text-right">Total à verser</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reservations as $reservation)
          @php
              $commission = $reservation->commission; 
              $prixHT = $reservation->prix / (1 + $tauxTVA); 
              $prixNetHT = $prixHT - $commission; 
              $totalAVerser = $prixNetHT; 
              $totalAVerserGlobal += $totalAVerser;
          @endphp
              <tr class="bg-white">
              <td class="border px-4 py-2 text-center">{{ $reservation->appartement->name }} <br> {{$reservation->appartement->address}}</td>
              <td class="border px-4 py-2 text-center date-column">{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m') }}</td>
              <td class="border px-4 py-2 text-center date-column">{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m') }}</td>
              <td class="border px-4 py-2 text-right">{{ number_format($reservation->prix) }} €</td>
              <td class="border px-4 py-2 text-right">{{ number_format($prixHT) }} €</td>                  
              <td class="border px-4 py-2 text-right">{{ number_format($commission) }} €</td>  
              <td class="border px-4 py-2 text-right">{{ number_format($totalAVerser) }} €</td>
              </tr>
          @endforeach
          <tr>
              <td class="border px-4 py-2 text-right font-bold" colspan="6">Total à verser</td>
              <td class="border px-4 py-2 text-right font-bold">{{ number_format($totalAVerserGlobal) }} €</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="flex justify-end">
    <img width="300" class="mb-1 ml" src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('logo/signature.png'))) }}">  
  </div>

</body>
</html>
