@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app2')

@section('content')
    <style>
        /* Styles de la boîte modale et du conteneur de la carte */

        .modal-map {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -40%);
            background-color: #fefefe;
            padding: 5px;
            width: 350px;
            text-align: center;
        }

        #map {
            height: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
    <div id="modalMap" class="modal" style="display: none;">
        <div class="modal-map">
            <span class="close">&times;</span>
            <div id="map" style="height: 550px"></div>
        </div>
    </div>
    <main id="main">
        @if (session('success_updt_price'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_updt_price') }}
                </div>
            </center>
        @endif
        @if (session('null_prix'))
            <center>
                <div class="alert alert-danger">
                    {{ session('null_prix') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Demande de devis de:</h5>
                <!-- Dark Table -->
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-dark">
                            <thead class="thead">
                                <tr class="text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Devis du:</th>
                                    <th scope="col">Déménagement pour le: </th>
                                    <th scope="col">Récupération: </th>
                                    <th scope="col">Livraison: </th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @php
                                    if ($utilisateur->acces_recup == 0) {
                                        $style_recup = 'red';
                                        $recup = 'Non accessible';
                                    } else {
                                        $style_recup = 'green';
                                        $recup = 'Accessible';
                                    }
                                    if ($utilisateur->acces_livr == 0) {
                                        $style_livr = 'red';
                                        $livr = 'Non accessible';
                                    } else {
                                        $style_livr = 'green';
                                        $livr = 'Accessible';
                                    }
                                @endphp
                                <tr>
                                    <td scope="row">{{ $utilisateur->id }}</td>
                                    <td scope="row"><a href ="#" data-bs-toggle="modal"
                                            data-bs-target="#ModalUtilisateur"
                                            style="color: rgb(11, 162, 238)">{{ $utilisateur->nom }}
                                            {{ $utilisateur->prenom }}</a></td>
                                    <td scope="row">
                                        {{ Carbon::parse($utilisateur->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    <td scope="row">
                                        {{ Carbon::parse($utilisateur->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    <td scope="row"><a href="#" id="mapLink" data-bs-toggle="modal"
                                            data-bs-target="#ModalRecup" style="color: rgb(11, 162, 238)"
                                            data-recup="{{ $utilisateur->coord_recup }}" data-livr="{{ $utilisateur->coord_livr }}" onclick="openMap()">
                                            {{ $utilisateur->recuperation }}
                                        </a><strong style="color:{{ $style_recup }}">({{ $recup }})</strong></td>
                                    <td scope="row"><a href="#" id="mapLinkLivr" data-bs-toggle="modal"
                                            data-bs-target="#ModalLivr" style="color: rgb(11, 162, 238)"
                                            data-recup="{{ $utilisateur->coord_recup }}" data-livr="{{ $utilisateur->coord_livr }}" onclick="openMapLivr()">
                                        {{ $utilisateur->livraison }}</a> <strong
                                            style="color:{{ $style_livr }}">({{ $livr }})</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>
        <!-- Modal delete -->
        <div class="modal fade" id="ModalUtilisateur" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="card">
                        <div class="card-body">
                            <!-- Dark Table -->
                            <div class="container">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Détails du client:</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <label for="nom" class="form-label">Nom:</label>
                                <input id="nom" type="text" class="form-control"
                                    value="{{ $detailUtilisateur->nom }}" disabled="">
                                <label for="prenom" class="form-label">Prénom:</label>
                                <input id="prenom" type="text" class="form-control"
                                    value="{{ $detailUtilisateur->prenom }}" disabled="">
                                <label for="numero" class="form-label">N° Téléphone:</label>
                                <input id="numero" type="text" class="form-control"
                                    value="{{ $detailUtilisateur->numero }}" disabled="">
                                <label for="email" class="form-label">Email:</label>
                                <input id="email" type="email" class="form-control"
                                    value="{{ $detailUtilisateur->email }}" disabled="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Dark Table -->
                <div class="container">
                    <hr>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead">
                                <tr class="text-center">
                                    <th scope="col">Objet </th>
                                    <th scope="col">Catégorie </th>
                                    <th scope="col">Taille </th>
                                    <th scope="col">Quantité </th>
                                    <th scope="col">Poids U.</th>
                                    <th scope="col">Prix U.</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">###</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                {{-- @foreach ($objets as $objet)
                                    <tr>
                                        <td scope="row">{{ $objet->nom }}</td>
                                        <td scope="row">{{ $objet->type_objet }}</td>
                                        <td scope="row">{{ $objet->taille }}</td>
                                        <td scope="row">{{ $objet->quantite }}</td>
                                        <td scope="row">{{ $objet->kilo }} Kg</td>
                                        <td scope="row">{{ number_format($objet->prix, 2, ',', ' ') }} Ar</td>
                                        <td scope="row">
                                            <strong>{{ number_format($objet->total, 2, ',', ' ') }} Ar</strong>
                                        </td>
                                        @if ($objet->prix == 0 && $objet->total == 0)
                                            <td scope="row"><a
                                                    href="{{ route('devisb.getObjetAttente', ['id' => $objet->id]) }}"
                                                    class="btn btn-success" id="btnSupprimer">Ajouter Prix</a></td>
                                        @else
                                            <td scope="row"><a
                                                    href="{{ route('devisb.getObjetAttente', ['id' => $objet->id]) }}"
                                                    class="btn btn-warning" id="btnSupprimer">Modifier Prix</a></td>
                                        @endif
                                    </tr>
                                @endforeach --}}
                                @foreach ($objets as $objet)
                                    <tr>
                                        <td scope="row">{{ $objet->nom }}</td>
                                        <td scope="row">{{ $objet->type_objet }}</td>
                                        <td scope="row">{{ $objet->taille }}</td>
                                        <td scope="row">{{ $objet->quantite }}</td>
                                        <td scope="row">{{ $objet->kilo }} Kg</td>
                                        <td scope="row">
                                            <form action="{{ route('devisb.addPriceToObjet') }}" method="POST">
                                                @csrf
                                                @method('patch')
                                                <div class="form-group">
                                                    <input type="hidden" name="id" value="{{ $objet->id }}">
                                                    <input type="text" class="form-control" style="text-align:center"
                                                        name="prix" value="{{ $objet->prix }}">
                                                </div>
                                        </td>
                                        <td scope="row">
                                            <strong>{{ number_format($objet->total, 2, ',', ' ') }} Ar</strong>
                                        </td>
                                        @if ($objet->prix == 0 && $objet->total == 0)
                                            <td scope="row">
                                                <button type="submit" class="btn btn-success">Ajouter Prix</button>
                                                </form>
                                            </td>
                                        @else
                                            <td scope="row">
                                                <button type="submit" class="btn btn-warning">Enregistrer</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr>
                                    <td scope="row">---------------</td>
                                    <td scope="row">---------------</td>
                                    <td scope="row">---------------</td>
                                    <td scope="row">---------------</td>
                                    <td scope="row">---------------</td>
                                    <td scope="row">---------------</td>
                                    <td scope="row">---------------</td>
                                    <td scope="row">---------------</td>
                                </tr>
                                <tr>
                                    <td scope="row"><strong>#</strong></td>
                                    <td scope="row"><strong>#</strong></td>
                                    <td scope="row"><strong>#</strong></td>
                                    <td scope="row"><strong>TOTAL:</strong></td>
                                    <td scope="row"><strong>{{ $sum->somme_poids }} KG</strong></td>
                                    <td scope="row"><strong>---</strong></td>
                                    <td scope="row" style="font-family:Impact">
                                        <strong>{{ number_format($sum->somme_total, 2, ',', ' ') }} Ar
                                    </td>
                                    <td scope="row"></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- End Dark Table -->
                <form
                    action="{{ route('devisb.sendDevis', ['id_devis' => $utilisateur->id, 'id_utilisateur' => $utilisateur->id_utilisateur]) }}"
                    method="get">
                    <button type="submit" class="btn btn-success">Envoyer devis</button>
                </form>
            </div>
        </div>
    </main>


    <script>
        var map;

        function openMap() {
            // Vérifier si la carte est déjà initialisée
            if (map) {
                console.log('La carte est déjà ouverte.');
                return;
            }

            var link = document.getElementById('mapLink');
            var coordinates = link.getAttribute('data-recup').split(',').map(coord => parseFloat(coord.trim()));
            var coordinates_livr = link.getAttribute('data-livr').split(',').map(coord => parseFloat(coord.trim()));

            map = L.map('map').setView(coordinates, 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Ajouter un seul marqueur si les coordonnées de récupération sont déjà présentes

            var marker = null;

            // map.on('click', function(e) {
            //     if (marker) {
            //         map.removeLayer(marker);
            //     }
            marker = L.marker(coordinates).addTo(map).bindPopup('Récupération');
            marker = L.marker(coordinates_livr).addTo(map).bindPopup('Livraison');

            // Supprimer le marqueur précédent s'il existe

            // });

            document.getElementById('modalMap').style.display = 'block';
        }

        document.getElementById('modalMap').getElementsByClassName('close')[0].onclick = function() {
            map.remove();
            map = null;
            document.getElementById('modalMap').style.display = 'none';
        };
    </script>

<script>
    var map;

    function openMapLivr() {
        // Vérifier si la carte est déjà initialisée
        if (map) {
            console.log('La carte est déjà ouverte.');
            return;
        }

        var link = document.getElementById('mapLinkLivr');
        var coordinates = link.getAttribute('data-livr').split(',').map(coord => parseFloat(coord.trim()));
        var coordinates_recup = link.getAttribute('data-recup').split(',').map(coord => parseFloat(coord.trim()));

        map = L.map('map').setView(coordinates, 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Ajouter un seul marqueur si les coordonnées de récupération sont déjà présentes

        var marker = null;

        // map.on('click', function(e) {
        //     if (marker) {
        //         map.removeLayer(marker);
        //     }
        marker = L.marker(coordinates).addTo(map).bindPopup('Livraison');
        marker = L.marker(coordinates_recup).addTo(map).bindPopup('Récupération');

        // Supprimer le marqueur précédent s'il existe

        // });

        document.getElementById('modalMap').style.display = 'block';
    }

    document.getElementById('modalMap').getElementsByClassName('close')[0].onclick = function() {
        map.remove();
        map = null;
        document.getElementById('modalMap').style.display = 'none';
    };
</script>
@endsection
