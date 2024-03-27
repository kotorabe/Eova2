@extends('layouts.app2')

@section('content')
    <main id="main">
        @if (session('ajout_success'))
            <center>
                <div class="alert alert-success">
                    {{ session('ajout_success') }}
                </div>
            </center>
        @endif
        @if (session('error'))
            <center>
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </center>
        @endif
        @if (session('updt_success'))
            <center>
                <div class="alert alert-warning">
                    {{ session('updt_success') }}
                </div>
            </center>
        @endif
        @if (session('success_dlt'))
            <center>
                <div class="alert alert-warning">
                    {{ session('success_dlt') }}
                </div>
            </center>
        @endif
        <div class="content-backdrop fade"></div>
        <!-- Modal delete -->
        <div class="modal fade" id="Modaldelete" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('equipe.dltEquipe') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Delete Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id">
                            <div class="alert alert-danger" role="alert">Are you sure , you want to
                                delete this team ?</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-outline-danger">Validate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-12">
                    <div class="card recent-sales overflow-auto">

                        <div class="card-body">
                            <h5 class="card-title">Liste équipe</span></h5>
                            <br>
                            <div class="col-lg-8">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <select name="categorie" class="form-control">
                                            @foreach ($categories as $categorie)
                                                <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Rechercher">
                                    </div>
                                </div>
                            </div>

                            <table class="table table-borderless">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Team</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Inclure jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            var data; // Déclarer la variable data à un niveau plus élevé

            function updateTable(filteredData) {
                var tbody = $('table tbody');
                tbody.empty();

                if (Array.isArray(filteredData) && filteredData.length > 0) {
                    $.each(filteredData, function(index, equipe) {
                        var row = '<tr class="text-center">' +
                            '<th scope="row"><a href="{{ route('equipe.getEquipe', '') }}/' + equipe.id +
                            '">Team ' + equipe.id +
                            '</a></th>' +
                            '<td>' + equipe.nom + '</td>' +
                            '<td>' + equipe.email + '</td>' +
                            '<td></td>' +
                            '<td><button data-bs-toggle="modal" data-bs-target="#Modaldelete" data-id="' +
                            equipe.id + '" class="btn btn-danger deleteclass">Supprimer</button></td>'
                        // '<td><a href="{{ route('equipe.dltEquipe', '') }}/' + equipe.id +
                        // '" class="btn btn-danger">Supprimer</a></td>' +
                        // '</tr>';

                        tbody.append(row);
                    });
                } else {
                    console.log('Aucune équipe trouvée pour cette catégorie.');
                }
            }

            $('select[name="categorie"]').on('change', function() {
                var categorie = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('equipe.getEquipeCategorie') }}',
                    data: {
                        categorie: categorie
                    },
                    success: function(response) {
                        data = response; // Assigner les données à la variable data
                        console.log(data);
                        updateTable(data);
                    }
                });
            }).change();

            // Ajoutez la fonctionnalité de recherche
            $('#searchInput').on('input', function() {
                var searchTerm = $(this).val().toLowerCase();

                // Filtrez les résultats en fonction du terme de recherche
                var filteredData = data.filter(function(equipe) {
                    return equipe.nom.toLowerCase().includes(searchTerm);
                });

                // Mettez à jour le tableau avec les résultats filtrés
                updateTable(filteredData);
            });
        });

        $(document).on("click", ".deleteclass", function() {
            var postId = $(this).data('id');
            console.log(postId);
            $("#Modaldelete .modal-body #id").val($(this).data('id'));
        });
    </script>
    <script>
        var collapse = document.querySelector('.nav-link.collapsed.equipe');
        var show = document.querySelector('.nav-content.collapse.equipe');
        var active = document.querySelector('.list.equipe');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.equipe .demande');
        // listdemande.classList.add("active");
    </script>

    {{-- <script>
        $(document).ready(function() {
            // Définir l'événement change sur le menu déroulant
            $('select[name="categorie"]').on('change', function() {
                var categorie = $(this).val();

                // Faire la requête Ajax pour obtenir les données de la catégorie sélectionnée
                $.ajax({
                    type: 'GET',
                    url: '{{ route('equipe.getEquipeCategorie') }}',
                    data: {
                        categorie: categorie
                    },
                    success: function(data) {
                        console.log(data);
                        // Mettez à jour le tableau avec les données reçues
                        var tbody = $('table tbody');
                        tbody.empty();

                        if (Array.isArray(data) && data.length > 0) {
                            $.each(data, function(index, equipe) {
                                var row = '<tr class="text-center">' +
                                    '<th scope="row"><a href="{{ route('equipe.getEquipe', '' ) }}/' + equipe.id + '">Team ' + equipe.id +
                                    '</a></th>' +
                                    '<td>' + equipe.nom + '</td>' +
                                    '<td>' + equipe.email + '</td>' +
                                    '<td></td>' +
                                    '<td><a href="#" class="btn btn-danger">Supprimer</a></td>' +
                                    // Ajoutez d'autres colonnes en fonction de votre modèle
                                    '</tr>';

                                tbody.append(row);
                            });
                        } else {
                            // Si le tableau est vide, affichez un message ou effectuez une action appropriée
                            console.log('Aucune équipe trouvée pour cette catégorie.');
                        }
                    }
                });
            }).change(); // Déclencher manuellement l'événement change après le chargement de la page
        });
    </script> --}}
@endsection
