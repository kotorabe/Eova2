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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Ajout catégorie</h5>
                <form action="{{ route('categorie.updtCategorie') }}" method="POST">
                    @csrf
                    <div id="form-container">
                        <!-- Premier formulaire -->
                        <div class="form-group">
                            <h5>Catégorie :</h5>
                            <input type="hidden" name="id" value="{{ $categorie->id }}">
                            <input type="text" name="nom" class="form-control" placeholder="{{ $categorie->nom }}"
                                required>
                        </div>
                        <div class="form-group">
                            <h5>Poids suppoter :</h5>
                            <input type="number" name="poids_total" class="form-control" min="1000" placeholder="En Kg"
                                required>
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </form>
            </div>
        </div>
    </main>
@endsection
