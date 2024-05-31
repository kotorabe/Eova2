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

        .modal-map {
            width: 70%;
        }

        #map {
            height: 70vh;
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
            <div id="map" style="height: 500px"></div>
        </div>
    </div>
    <main id="main">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Suivi livraison de:</h5>
                <!-- Dark Table -->
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-dark">
                            <thead class="thead">
                                <tr class="text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Déménagement pour le: </th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Equipe en charge </th>
                                    <th scope="col">### </th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr>
                                    <td scope="row">{{ $detail->id }}</td>
                                    <td scope="row"><a href ="#" data-bs-toggle="modal"
                                            data-bs-target="#ModalUtilisateur"
                                            style="color: rgb(11, 162, 238)">{{ $detail->client_nom }}
                                            {{ $detail->client_prenom }}</a></td>
                                    <td scope="row">
                                        {{ Carbon::parse($detail->date_livraison)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    @php
                                        $statut = '';
                                        $couleur = '';
                                    @endphp
                                    @if ($detail->etat == 2)
                                        @php
                                            $statut = 'Vers Récupération';
                                            $couleur = 'green';
                                        @endphp
                                    @elseif ($detail->etat == 3)
                                        @php
                                            $statut = 'Livraison';
                                            $couleur = 'green';
                                        @endphp
                                    @endif
                                    @if ($detail->etat == 3)
                                        <td scope="row"><a href="#" data-bs-toggle="modal"
                                                data-bs-target="#ModalImage"
                                                style="color:{{ $couleur }}">{{ $statut }}</a></td>
                                    @else
                                        <td scope="row" style="color:{{ $couleur }}">{{ $statut }}</td>
                                    @endif
                                    <td scope="row">
                                        <a href="#" id="mapLink" data-bs-toggle="modal" data-bs-target="#ModalRecup"
                                            style="color: rgb(11, 162, 238)" data-recup="{{ $coordonnee->coord_recup }}"
                                            data-livr="{{ $coordonnee->coord_livr }}" data-equipe="{{ $detail->position }}"
                                            recuperation="{{ $coordonnee->recuperation }}"
                                            livraison="{{ $coordonnee->livraison }}" onclick="openMap()">
                                            {{ $detail->equipe }}</a>
                                    </td>
                                    <td scope="row">---</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if ($detail->etat == 3)
            <div class="modal fade" id="ModalImage" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="card">
                            <div class="card-body">
                                <!-- Dark Table -->
                                <div class="container">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Récupération:</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $detail->img_recup) }}" alt="Image récupération"
                                            class="img-fluid" style="max-width: 100%; height: auto;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div class="content-backdrop fade"></div>
        <!-- Modal delete -->
        <div class="modal fade" id="ModalUtilisateur" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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

        <style>
            .chiffre-container {
                text-align: center;
            }

            .chiffre-bas {
                font-size: 16px;
                font-weight: bold;
            }

            .chiffre-top {
                font-size: 14px;
                font-weight: inherit;
                margin-bottom: -8px;
            }

            .custom-marker {
                margin-left: -10px;
                margin-top: -10px;
                position: absolute;
            }
        </style>
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
                                    <th scope="col">Prix U.@if ($coordonnee->reduction != 0)
                                            <strong class="bi bi-arrow-down" style="color:rgb(255, 163, 4)">-
                                                {{ $coordonnee->reduction }}%</strong>
                                        @endif
                                    </th>
                                    <th scope="col">Total @if ($coordonnee->reduction != 0)
                                            <strong class="bi bi-arrow-down" style="color:rgb(255, 163, 4)">-
                                                {{ $coordonnee->reduction }}%</strong>
                                        @endif
                                    </th>
                                    <th scope="col">###</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($objets as $objet)
                                    <tr>
                                        <td scope="row">{{ $objet->nom }}</td>
                                        <td scope="row">{{ $objet->type_objet }}</td>
                                        <td scope="row">{{ $objet->taille }}</td>
                                        <td scope="row">{{ $objet->quantite }}</td>
                                        <td scope="row">{{ $objet->kilo }} Kg</td>
                                        @if ($coordonnee->reduction == 0)
                                            <td scope="row">{{ number_format($objet->prix, 2, ',', ' ') }} Ar</td>
                                        @else
                                            <td scope="row">
                                                <div class="chiffre-container">
                                                    <div class="chiffre-top">
                                                        {{ number_format($objet->prix, 2, ',', ' ') }}
                                                        Ar</div>
                                                    <div class="chiffre-bas">
                                                        {{ number_format($objet->prix - $objet->prix * ($coordonnee->reduction / 100), 2, ',', ' ') }}
                                                        Ar</div>
                                                </div>
                                            </td>
                                        @endif
                                        @if ($coordonnee->reduction == 0)
                                            <td scope="row"><strong>{{ number_format($objet->total, 2, ',', ' ') }}
                                                    Ar</strong></td>
                                        @else
                                            <td scope="row">
                                                <div class="chiffre-container">
                                                    <div class="chiffre-top">
                                                        {{ number_format($objet->total, 2, ',', ' ') }}
                                                        Ar</div>
                                                    <div class="chiffre-bas">
                                                        {{ number_format($objet->total - $objet->total * ($coordonnee->reduction / 100), 2, ',', ' ') }}
                                                        Ar</div>
                                                </div>
                                            </td>
                                        @endif
                                        <td scope="row">----</td>
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
                                    @if ($coordonnee->reduction == 0)
                                        <td scope="row" style="font-family:Impact">
                                            <strong>{{ number_format($sum->somme_total, 2, ',', ' ') }} Ar</strong>
                                        </td>
                                    @else
                                        <td scope="row">
                                            <div class="chiffre-container">
                                                <div class="chiffre-top">
                                                    {{ number_format($sum->somme_total, 2, ',', ' ') }}
                                                    Ar</div>
                                                <div class="chiffre-bas">
                                                    {{ number_format($sum->somme_total - $sum->somme_total * ($coordonnee->reduction / 100), 2, ',', ' ') }}
                                                    Ar</div>
                                            </div>
                                        </td>
                                    @endif
                                    <td scope="row"></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- End Dark Table -->
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
            var position_equipe = link.getAttribute('data-equipe').split(',').map(coord => parseFloat(coord.trim()));
            var lieu_recup = link.getAttribute('recuperation');
            var lieu_livraison = link.getAttribute('livraison');

            map = L.map('map').setView(coordinates, 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            // Ajouter un seul marqueur si les coordonnées de récupération sont déjà présentes

            var marker = null;

            // map.on('click', function(e) {
            //     if (marker) {
            //         map.removeLayer(marker);
            //     }
            marker = L.marker(coordinates, {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background-color: green; width: 20px; height: 20px; border-radius: 50%;"></div>'
                })
            }).addTo(map).bindPopup('Récupération: ' + lieu_recup);
            marker = L.marker(coordinates_livr, {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background-color: red; width: 20px; height: 20px; border-radius: 50%;"></div>'
                })
            }).addTo(map).bindPopup('Livraison: ' + lieu_livraison);
            marker = L.marker(position_equipe).addTo(map).bindPopup('Position de l\'équipe');

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
        var collapse = document.querySelector('.nav-link.collapsed.livraison');
        var show = document.querySelector('.nav-content.collapse.livraison');
        var active = document.querySelector('.list.livraison');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var list = document.querySelector('.nav-item.livraison .voir');
        // list.classList.add("active");
    </script>
@endsection
