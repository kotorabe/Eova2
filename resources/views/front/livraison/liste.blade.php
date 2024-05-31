@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app3')

@section('content')
    <main id="main">
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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Livraison</h5>

                <!-- Dark Table -->
                <div class="table-responsive">
                    <table class="table">
                        <thead class="dark-thead">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Déménagement le :</th>
                                <th scope="col">Statut</th>
                                <th scope="col">Equipe en charge </th>
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if ($livraison != null)
                                @php
                                    $statut = '';
                                    $couleur = '';
                                @endphp
                                @if ($livraison->etat == 1)
                                    @php
                                        $statut = 'Planifier';
                                        $couleur = 'green';
                                    @endphp
                                @elseif ($livraison->etat == 2)
                                    @php
                                        $statut = 'Vers Récupération';
                                        $couleur = 'green';
                                    @endphp
                                @elseif ($livraison->etat == 3)
                                    @php
                                        $statut = 'Livraison';
                                        $couleur = 'green';
                                    @endphp
                                @endif
                                <tr>
                                    <td scope="row">#</td>
                                    <td scope="row">
                                        {{ Carbon::parse($livraison->date_livraison)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    @if ($livraison->etat == 1)
                                        <td scope="row" style="color:{{ $couleur }}">{{ $statut }}</td>
                                    @else
                                        <td scope="row" style="color:{{ $couleur }}"><a href="#"
                                                id="mapLink" data-bs-toggle="modal" data-bs-target="#ModalRecup"
                                                style="color: {{ $couleur }}"
                                                data-recup="{{ $utilisateur->coord_recup }}"
                                                data-livr="{{ $utilisateur->coord_livr }}"
                                                data-equipe="{{ $livraison->position }}"
                                                recuperation="{{ $utilisateur->recuperation }}"
                                                livraison="{{ $utilisateur->livraison }}"
                                                onclick="openMap()">{{ $statut }}</a></td>
                                    @endif
                                    <td scope="row">{{ $livraison->equipe }}</td>
                                    <td scope="row"><a href="#" data-bs-toggle="modal" data-bs-target="#Modallist"
                                            class="btn btn-info">Voir les objets</a></td>
                                </tr>
                            @else
                            @endif

                        </tbody>
                    </table>
                </div>
                <!-- End Dark Table -->

            </div>
        </div>

        <div class="content-backdrop fade"></div>
        <!-- Modal delete -->
        <div class="modal fade" id="Modallist" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
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
                                                <th scope="col">Prix U.@if ($utilisateur->reduction != 0)
                                                        <strong class="bi bi-arrow-down" style="color:rgb(255, 163, 4)">-
                                                            {{ $utilisateur->reduction }}%</strong>
                                                    @endif
                                                </th>
                                                <th scope="col">Total @if ($utilisateur->reduction != 0)
                                                        <strong class="bi bi-arrow-down" style="color:rgb(255, 163, 4)">-
                                                            {{ $utilisateur->reduction }}%</strong>
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
                                                    @if ($utilisateur->reduction == 0)
                                                        <td scope="row">
                                                            {{ number_format($objet->prix, 2, ',', ' ') }} Ar</td>
                                                    @else
                                                        <td scope="row">
                                                            <div class="chiffre-container">
                                                                <div class="chiffre-top">
                                                                    {{ number_format($objet->prix, 2, ',', ' ') }}
                                                                    Ar</div>
                                                                <div class="chiffre-bas">
                                                                    {{ number_format($objet->prix - $objet->prix * $reduction, 2, ',', ' ') }}
                                                                    Ar</div>
                                                            </div>
                                                        </td>
                                                    @endif
                                                    @if ($utilisateur->reduction == 0)
                                                        <td scope="row">
                                                            <strong>{{ number_format($objet->total, 2, ',', ' ') }}
                                                                Ar</strong>
                                                        </td>
                                                    @else
                                                        <td scope="row">
                                                            <div class="chiffre-container">
                                                                <div class="chiffre-top">
                                                                    {{ number_format($objet->total, 2, ',', ' ') }}
                                                                    Ar</div>
                                                                <div class="chiffre-bas">
                                                                    {{ number_format($objet->total - $objet->total * $reduction, 2, ',', ' ') }}
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
                                                @if ($utilisateur->reduction == 0)
                                                    <td scope="row" style="font-family:Impact">
                                                        <strong>{{ number_format($sum->somme_total, 2, ',', ' ') }}
                                                            Ar</strong>
                                                    </td>
                                                @else
                                                    <td scope="row">
                                                        <div class="chiffre-container">
                                                            <div class="chiffre-top">
                                                                {{ number_format($sum->somme_total, 2, ',', ' ') }}
                                                                Ar</div>
                                                            <div class="chiffre-bas">
                                                                {{ number_format($sum->somme_total - $sum->somme_total * $reduction, 2, ',', ' ') }}
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
                </div>
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
        var collapse = document.querySelector('.nav-link.collapsed.livr');
        var show = document.querySelector('.nav-content.collapse.livr');
        var active = document.querySelector('.list.voir');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.devis .demande');
        // listdemande.classList.add("active");
    </script>
@endsection
