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
        @if (session('dlt_success'))
            <center>
                <div class="alert alert-danger">
                    {{ session('dlt_success') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ajout équipe</h5>
                <form action="{{ route('equipe.addEquipe') }}" method="POST">
                    @csrf
                    <div id="form-container">
                        <!-- Premier formulaire -->
                        <div class="form-group">
                            <h5>Nom :</h5>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <h5>Email :</h5>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <h5>Mot de passe :</h5>
                            <input type="text" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <h5>Catégorie :</h5>
                            <select name="categorie" class="form-control">
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </form>
            </div>
        </div>
    </main>

    <script>
        var collapse = document.querySelector('.nav-link.collapsed.equipe');
        var show = document.querySelector('.nav-content.collapse.equipe');
        var active = document.querySelector('.list.ajout');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.equipe .demande');
        // listdemande.classList.add("active");
    </script>
@endsection
