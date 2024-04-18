@php
    use Carbon\Carbon;
@endphp
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
        <div class="container">
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
                                        <td scope="row">{{ $utilisateur->nom }} {{ $utilisateur->prenom }}</td>
                                        <td scope="row">
                                            {{ Carbon::parse($utilisateur->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                        </td>
                                        <td scope="row">
                                            {{ Carbon::parse($utilisateur->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                        </td>
                                        <td scope="row">{{ $utilisateur->recuperation }} <strong
                                                style="color:{{ $style_recup }}">({{ $recup }})</strong></td>
                                        <td scope="row">{{ $utilisateur->livraison }} <strong
                                                style="color:{{ $style_livr }}">({{ $livr }})</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if ($objet->prix == 0 && $objet->total)
                <form action="{{ route('devisb.addPriceToObjet') }}" method="POST">
                    @csrf
                    <h1>Ajout de prix à un objet</h1>
                    <div id="form-container">
                        <!-- Premier formulaire -->
                        <div class="form-group">
                            <h5>Objet :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->nom }}" disabled>
                        </div>
                        <div class="form-group">
                            <h5>Catégorie :</h5>
                            <input class="form-control" type="text" placeholder="{{ $objet->type_objet }}" disabled>
                        </div>

                        <div class="form-group">
                            <h5>Taille :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->taille }}" disabled>
                        </div>
                        <div class="form-group">
                            <h5>Quantité :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->quantite }}" disabled>
                        </div>
                        <div class="form-group">
                            <h5>Poids Unitaire :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->kilo }}" disabled>
                        </div>
                        <input type="hidden" name="id" value="{{ $objet->id }}">
                        <div class="form-group">
                            <h5>Prix(en Ar) :</h5>
                            <input type="number" id=montant name="prix" min="1" value="0"
                                class="form-control" required oninput="calculerTotal()">
                        </div>
                        <div class="form-group">
                            <h5>Total :</h5>
                            <input type="text" id="total" class="form-control" readonly disabled />
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Valider</button>
                </form>
            @else
                <form action="{{ route('devisb.addPriceToObjet') }}" method="POST">
                    @csrf
                    <h1>Modification de prix</h1>
                    <div id="form-container">
                        <!-- Premier formulaire -->
                        <div class="form-group">
                            <h5>Objet :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->nom }}" disabled>
                        </div>
                        <div class="form-group">
                            <h5>Catégorie :</h5>
                            <input class="form-control" type="text" placeholder="{{ $objet->type_objet }}" disabled>
                        </div>

                        <div class="form-group">
                            <h5>Taille :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->taille }}" disabled>
                        </div>
                        <div class="form-group">
                            <h5>Quantité :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->quantite }}" disabled>
                        </div>
                        <div class="form-group">
                            <h5>Poids Unitaire :</h5>
                            <input type="text" class="form-control" placeholder="{{ $objet->kilo }}" disabled>
                        </div>
                        <input type="hidden" name="id" value="{{ $objet->id }}">
                        <div class="form-group">
                            <h5>Prix(en Ar) :</h5>
                            <input type="number" id=montant name="prix" min="1" value="{{ $objet->prix }}"
                                class="form-control" required oninput="calculerTotal()">
                        </div>
                        <div class="form-group">
                            <h5>Total :</h5>
                            <input type="text" id="total" class="form-control" readonly disabled />
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Valider</button>
                </form>
            @endif

        </div>
    </main>

    <script>
        var quantite = {{ $objet->quantite ?? 0 }};

        function calculerTotal() {
            // Récupérer les valeurs des champs quantité et montant
            var montant = document.getElementById('montant').value;

            // Calculer le total
            var total = quantite * montant;

            var totalFormatte = total.toLocaleString('fr-FR') + ' Ar';

            // Mettre à jour le champ total
            document.getElementById('total').value = totalFormatte;
        }
        window.onload = calculerTotal;
    </script>
@endsection
