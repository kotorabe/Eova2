@extends('layouts.app2')

@section('content')
    <main id="main">
        @if (session('add_success'))
            <center>
                <div class="alert alert-success">
                    {{ session('add_success') }}
                </div>
            </center>
        @endif
        @if (session('failed_add'))
            <center>
                <div class="alert alert-success">
                    {{ session('failed_add') }}
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
        @if (session('dlt_success'))
            <center>
                <div class="alert alert-danger">
                    {{ session('dlt_success') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ajout catégorie</h5>
                <form action="{{ route('categorie.addCategorie') }}" method="POST">
                    @csrf
                    <div id="form-container">
                        <!-- Premier formulaire -->
                        <div class="form-group">
                            <h5>Catégorie :</h5>
                            <input type="text" name="nom" class="form-control" placeholder="Ex:3 véhicules" required>
                        </div>
                        <div class="form-group">
                            <h5>Poids suppoter :</h5>
                            <input type="number" name="poids_total" class="form-control" min="1000" placeholder="En Kg"
                                required>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </form>
            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste catégorie</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="dark-thead">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Catégorie</th>
                                <th scope="col">Poids supporter</th>
                                <th scope="col">#</th>
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse ($categories as $categorie)
                                <tr>
                                    <td scope="row">{{ $categorie->id }}</td>
                                    <td scope="row">{{ $categorie->nom }}</td>
                                    <td scope="row">{{ number_format($categorie->poids_total, 0, '', ' ') }} Kg</td>
                                    <td scope="row"><a
                                            href="{{ route('categorie.getCategorie', ['id' => $categorie->id]) }}"
                                            class="btn btn-warning">Modifier</a></td>
                                    <td scope="row"><a
                                            href="{{ route('categorie.dltCategorie', ['id' => $categorie->id]) }}"
                                            class="btn btn-danger">Supprimer</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td>---------</td>
                                    <td>---------</td>
                                    <td>---------</td>
                                    <td>---------</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        var collapse = document.querySelector('.nav-link.collapsed.categorie');
        var show = document.querySelector('.nav-content.collapse.categorie');
        var active = document.querySelector('.list.categorie');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.equipe .demande');
        // listdemande.classList.add("active");
    </script>
@endsection
