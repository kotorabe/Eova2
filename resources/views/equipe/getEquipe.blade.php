@extends('layouts.app2')

@section('content')
    <main id="main">
        @if (session('error'))
            <center>
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Modification d' équipe</h5>
                <form action="{{ route('equipe.updtEquipe') }}" method="POST">
                    @csrf
                    <div id="form-container">
                        <!-- Premier formulaire -->
                        <input type="hidden" name="id" value="{{ $equipe->id }}">
                        <div class="form-group">
                            <h5>Nom :</h5>
                            <input type="text" name="nom" class="form-control" placeholder="{{ $equipe->nom }}" required>
                        </div>
                        <div class="form-group">
                            <h5>Mot de passe :</h5>
                            <input type="text" name="password" class="form-control" placeholder="Entrer le nouveau mot de passe" required>
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
                    <button type="submit" class="btn btn-success">Modifier</button>
                </form>
            </div>
        </div>
    </main>
@endsection
