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
        <p style="padding: 5px">Nous vous informons que nous sommes entrain de finalisés la planification de votre déménagement. <br>
            Un autre email vous sera envoyé pour vous informer de l'équipe qui sera en charge de votre déménagement, vous recevrez ce mail au plus tard 2 jours avant la date de déménagement qui est le:
            <strong>{{ Carbon::parse($data['demenagement'])->locale('fr')->isoFormat('DD MMMM YYYY') }}</strong>.
        <p>
            <center><strong>Encore merci d' avoir choisi nos service ! </strong></center>
        </p>
    </div>

</body>

</html>
