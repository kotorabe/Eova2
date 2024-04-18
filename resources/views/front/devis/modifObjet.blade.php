@extends('layouts.app3')

@section('content')
    <main id="main">
        <div class="container">
            <form action="{{ route('devis.modification') }}" method="POST">
                @csrf
                <h1>Modification d'objet</h1>
                <input type="hidden" name="id" value="{{ $objet->id }}">

                <div id="form-container">
                    <!-- Premier formulaire -->
                    <div class="form-group formulaire">
                        <h5>Catégorie :</h5>
                        <select name="type" class="form-control" required>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group formulaire">
                        <h5>Objet :</h5>
                        <input type="text" name="objet" class="form-control" placeholder="{{ $objet->nom }}"
                            required>
                    </div>

                    <div class="form-group formulaire">
                        <h5>Quantité :</h5>
                        <input type="number" name="quantite" min="1" value="{{ $objet->quantite }}" class="form-control" required>
                    </div>

                    <div class="form-group formulaire">
                        <h5>Taille :</h5>
                        <select name="taille" class="form-control" required>
                            @foreach ($tailles as $taille)
                                <option value="{{ $taille->id }}">{{ $taille->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group formulaire">
                        <h5>Poids(en Kg) :</h5>
                        <input type="number" name="poids" min="1" value="{{ $objet->kilo }}" class="form-control" required>
                    </div>

                    <hr> <!-- Ajouter une ligne horizontale -->

                    <!-- Bouton Ajouter -->

                </div>

                <!-- Bouton Valider -->
                <button type="submit" class="btn btn-success">Valider</button>
            </form>
        </div>
    </main>
@endsection
