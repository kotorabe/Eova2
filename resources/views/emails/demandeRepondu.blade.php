@php
    use Carbon\Carbon;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <title>Document</title>
</head>

<body style="margin: 2%">
    <div class="container">
        <header style="text-align: center;">
            <h4 style="color: #418fe2;">Service Client Eova Trano</h4>
        </header>
        <br>
            <p>Bonjour {{ $data['nom'] }} {{ $data['prenom'] }},</p>
        <p style="padding: 5px">Ceci est une notification pour vous dire que vôtre demande de devis du: <strong>{{ Carbon::parse($data['date_devis'])->locale('fr')->isoFormat('DD MMMM YYYY') }}</strong>
            ,pour un déménagement le: <strong>{{ Carbon::parse($data['demenagement'])->locale('fr')->isoFormat('DD MMMM YYYY') }}</strong> a été répondu.</p>
        <p>
            <center><strong>Merci d'avoir choisi notre services ! </strong></center>
        </p>
    </div>

</body>

</html>
