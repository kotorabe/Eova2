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
        <p style="padding: 5px">Nous nous excusons si notre prix ne vous a pas satisfait. <br>
            Toutefois, votre devis sera toujours disponible jusqu'à 3 jours avant la date de déménagement. Le devis sera automatiquement supprimé après ce délai.
            <br>
        <p>
            <center><strong>Nous espérons que vous nous reviendrez ! </strong></center>
        </p>
    </div>

</body>

</html>
