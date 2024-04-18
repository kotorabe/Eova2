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
            <h4 style="color: #418fe2;">Admin E-ova Trano</h4>
        </header>
        <br>
            <p>Bonjour team {{ $data['nom'] }},</p>
        <p style="padding: 5px">Vos nouvelles identifications sont: <br>
             email : {{ $data['email'] }} ,<br> Mdp : {{ $data['password'] }}
        <p>
            <center><strong>Equipe E-ova Trano</strong></center>
        </p>
    </div>

</body>

</html>
