@extends('layouts.app3')

@section('content')
    <!-- Leaflet CSS -->


    <style>
        /* Styles de la boîte modale et du conteneur de la carte */
        /* Styles de la boîte modale et du conteneur de la carte */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content,
        .modal-map {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 70%;
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
    <main id="main">
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: 35%" aria-valuenow="25" aria-valuemin=""
                aria-valuemax="100"></div>
        </div><br>
        @if (session('erreur_date'))
            <center>
                <div class="alert alert-danger">
                    {{ session('erreur_date') }}
                </div>
            </center>
        @endif
        @if (session('erreur_devis'))
            <center>
                <div class="alert alert-danger">
                    {{ session('erreur_devis') }}
                </div>
            </center>
        @endif

        <div class="container">
            <form action="{{ route('devis.redirection') }}" method="POST">
                @csrf
                <h1>Veuillez un insérer les addresses.</h1>
                <div id="form-container">
                    <!-- Premier formulaire -->
                    <div class="form-group">
                        <h5>Adresse de récupération :</h5>
                        <input type="text" name="recuperation" class="form-control" placeholder="Ex: Tetezana BR 125"
                            required>
                    </div>
                    <div class="form-check" style="padding: 5px; margin-left:35px">
                        <label for="recup">Accès Voiture</label>
                        <input class="form-check-input" type="checkbox" name="access_recup" id="recup">
                    </div>
                    <div class="form-group">
                        <h5>Coordonnées du lieu de récupération :</h5>
                        <p><input type="text" id="coordinates_recup" name="coordinates_recup" class="form-control"
                                placeholder="Cliquez sur la carte" required style="cursor: pointer;" readonly>
                        </p>
                        <p><button type="button" class="btn btn-primary" onclick="openMapRecup()">Sélectionner sur la
                                carte</button></p>
                    </div>

                    <div class="form-group">
                        <h5>Addresse de livraison :</h5>
                        <input type="text" name="livraison" class="form-control" placeholder="Ex: Ampitatafika Bis 02"
                            required>
                    </div>
                    <div class="form-check" style="padding: 5px; margin-left:35px">
                        <label for="livr">Accès Voiture</label>
                        <input class="form-check-input" type="checkbox" name="access_livr" id="recup">
                    </div>
                    <div class="form-group">
                        <h5>Coordonnées du lieu de livraison :</h5>
                        <p><input type="text" id="coordinates" name="coordinates" class="form-control"
                                placeholder="Cliquez sur la carte" required style="cursor: pointer;" readonly></p>
                        <p><button type="button" class="btn btn-primary" onclick="openMap()">Sélectionner sur la
                                carte</button></p>
                    </div>
                    <div class="form-group">
                        <h5>Date prévue pour le déménagement:(7 jours après demande minimum)</h5>
                        <input type="date" id="date_picker" name="date_demenagement" class="form-control" required>
                    </div>
                    <!-- Boîte modale pour afficher l'alerte -->
                    <div id="modal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <p>Nous sommes pas en service pour les dimanches. Merci de choisir entre lundi et samedi.</p>
                        </div>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-success">Valider</button>
            </form>
        </div>
        <div id="modalMap" class="modal" style="display: none;">
            <div class="modal-map">
                <span class="close">&times;</span>
                <div id="map" style="height: 500px"></div>
            </div>
        </div>
        <div id="modalMapRecup" class="modal" style="display: none;">
            <div class="modal-map">
                <span class="close" onclick="closeMapRecup()">&times;</span>
                <div id="mapRecup" style="height: 500px"></div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Réinitialisez les champs de coordonnées
            document.getElementById('coordinates').value = '';
            document.getElementById('coordinates_recup').value = '';

            // Réinitialisez les champs de date
            document.getElementById('date_picker').value = '';

            // Supprimez les marqueurs stockés dans le stockage local
            localStorage.removeItem('coordinates');
            localStorage.removeItem('coordinates_recup');
        });
    </script>

    <script>
        // Fonction pour vérifier si une date est un dimanche
        function isSunday(date) {
            return date.getDay() === 0; // 0 correspond à dimanche
        }

        // Calculer la date actuelle plus 3 jours
        var todayPlus3Days = new Date();
        todayPlus3Days.setDate(todayPlus3Days.getDate() + 7);

        // Formater la date pour l'attribut value du champ de saisie de date
        var formattedDatePlus3Days = todayPlus3Days.toISOString().split('T')[0];

        // Définir la date calculée comme valeur par défaut
        document.getElementById('date_picker').setAttribute('value', formattedDatePlus3Days);

        // Désactiver les dates antérieures à la date actuelle plus 3 jours et les dimanches
        var today = new Date();
        today.setDate(today.getDate() + 7);
        var minDate = today.toISOString().split('T')[0];

        // Définir la date minimale autorisée (la date actuelle plus 3 jours)
        document.getElementById('date_picker').setAttribute('min', minDate);

        // Gestionnaire d'événements pour afficher la boîte modale si la date est un dimanche
        document.getElementById('date_picker').addEventListener('input', function() {
            var selectedDate = new Date(this.value);
            if (isSunday(selectedDate)) {
                // Afficher la boîte modale
                document.getElementById('modal').style.display = 'block';
                // Effacer la date si c'est un dimanche
                this.value = '';
            }
        });

        // Gestionnaire d'événements pour fermer la boîte modale
        document.getElementById('modal').getElementsByClassName('close')[0].addEventListener('click', function() {
            document.getElementById('modal').style.display = 'none';
        });
    </script>

    <script>
        var map;

        function openMap() {
            // Vérifier si la carte est déjà initialisée
            if (map) {
                console.log('La carte est déjà ouverte.');
                return;
            }


            map = L.map('map').setView([-18.90888487914695, 47.587352032427894], 9);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            map.invalidateSize();

            // Ajouter un seul marqueur si les coordonnées de récupération sont déjà présentes
            var recCoordinates = document.getElementById('coordinates_recup').value;
            if (recCoordinates) {
                var recMarker = L.marker(recCoordinates.split(',')).addTo(map);
            }

            var marker = null;

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                document.getElementById('coordinates').value = e.latlng.lat + ', ' + e.latlng.lng;

                // Supprimer le marqueur précédent s'il existe

            });
            document.getElementById('modalMap').style.display = 'block';

        }

        document.getElementById('modalMap').getElementsByClassName('close')[0].onclick = function() {
            map.remove();
            map = null;
            document.getElementById('modalMap').style.display = 'none';
        };
    </script>

    <script>
        var mapRecup;

        function openMapRecup() {
            if (mapRecup) {
                console.log('La carte de récupération est déjà ouverte.');
                return;
            }

            mapRecup = L.map('mapRecup').setView([-18.90888487914695, 47.587352032427894], 9);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(mapRecup);
            mapRecup.invalidateSize();

            // Récupérer les coordonnées existantes et ajouter le marqueur
            var recupCoordinates = document.getElementById('coordinates_recup').value;
            if (recupCoordinates) {
                var recupMarker = L.marker(recupCoordinates.split(',')).addTo(mapRecup);
            }
            var marker = null;

            mapRecup.on('click', function(e) {
                document.getElementById('coordinates_recup').value = e.latlng.lat + ', ' + e.latlng.lng;

                // Supprimer le marqueur précédent s'il existe
                if (recupMarker) {
                    mapRecup.removeLayer(recupMarker);
                }

                if (marker) {
                    mapRecup.removeLayer(marker);
                }

                marker = L.marker(e.latlng).addTo(mapRecup);
            });
            document.getElementById('modalMapRecup').style.display = 'block';

        }

        function closeMapRecup() {
            mapRecup.remove();
            mapRecup = null;
            document.getElementById('modalMapRecup').style.display = 'none';
        }
    </script>
@endsection
