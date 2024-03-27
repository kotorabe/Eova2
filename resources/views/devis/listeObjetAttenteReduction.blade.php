@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app2')

@section('content')
    <main id="main">
        @if (session('success_add_price'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_add_price') }}
                </div>
            </center>
        @endif
        @if (session('reduction_updt'))
            <center>
                <div class="alert alert-success">
                    {{ session('reduction_updt') }}
                </div>
            </center>
        @endif
        @if (session('null_prix'))
            <center>
                <div class="alert alert-danger">
                    {{ session('null_prix') }}
                </div>
            </center>
        @endif
        @if (session('reduction_add'))
            <center>
                <div class="alert alert-success">
                    {{ session('reduction_add') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Demande de réduction de:</h5>
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
        <style>
            /* Positionne l'input en haut de la table */
            #reduction {
                top: 10px;
                /* Ajustez la valeur selon vos besoins */
                left: 250px;
                /* Ajustez la valeur selon vos besoins */
            }

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
        </style>


        <div class="card">
            <div class="card-body">
                <!-- Dark Table -->
                <div class="container">
                    <hr>
                    <div class="col-lg-6">
                        <form class="row g-3" action="{{ route('devisb.ajouterReduction', ['id' => $utilisateur->id]) }}"
                            method="post">
                            @csrf
                            <div class="col-md-5">
                                @if ($utilisateur->reduction == 0)
                                    <input type="number" name="reduction" min="1" max="25"
                                        class="form-control" placeholder="Réduction en %" required>
                                @else
                                    <input type="number" name="reduction" min="1" max="25"
                                        class="form-control" placeholder="{{ $utilisateur->reduction }} %" required>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if ($utilisateur->reduction == 0)
                                    <button type="submit" class="btn btn-outline-success">Ajouter la réduction</button>
                                @else
                                    <button type="submit" class="btn btn-warning">Modifier la réduction</button>
                                @endif

                            </div>
                        </form>
                    </div>
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
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                {{-- @foreach ($objets as $objet)
                                    <tr>
                                        <td scope="row">{{ $objet->nom }}</td>
                                        <td scope="row">{{ $objet->type_objet }}</td>
                                        <td scope="row">{{ $objet->taille }}</td>
                                        <td scope="row">{{ $objet->quantite }}</td>
                                        <td scope="row">{{ $objet->kilo }} Kg</td>
                                        <td scope="row">{{ number_format($objet->prix, 2, ',', ' ') }} Ar</td>
                                        <td scope="row">
                                            <strong>{{ number_format($objet->total, 2, ',', ' ') }} Ar</strong>
                                        </td>
                                        <td scope="row"><a
                                                href="{{ route('devisb.getObjetReduction', ['id' => $objet->id]) }}"
                                                class="btn btn-warning" id="btnSupprimer">Modifier Prix</a></td>
                                    </tr>
                                @endforeach --}}
                                @foreach ($objets as $objet)
                                    <tr>
                                        <td scope="row">{{ $objet->nom }}</td>
                                        <td scope="row">{{ $objet->type_objet }}</td>
                                        <td scope="row">{{ $objet->taille }}</td>
                                        <td scope="row">{{ $objet->quantite }}</td>
                                        <td scope="row">{{ $objet->kilo }} Kg</td>
                                        @if ($utilisateur->reduction == 0)
                                            <td scope="row">{{ number_format($objet->prix, 2, ',', ' ') }} Ar</td>
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

                                        {{-- <td scope="row">
                                            <div class="chiffre-container">
                                                <div class="chiffre-top">{{ number_format($objet->prix, 2, ',', ' ') }} Ar</div>
                                                <div class="chiffre-bas">{{ number_format($objet->prix, 2, ',', ' ') }} Ar</div>
                                              </div>
                                        </td> --}}
                                        @if ($utilisateur->reduction == 0)
                                            <td scope="row"><strong>{{ number_format($objet->total, 2, ',', ' ') }}
                                                    Ar</strong></td>
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
                                    {{-- <td scope="row" style="font-family:Impact">
                                        <strong>{{ number_format($sum->somme_total, 2, ',', ' ') }} Ar
                                    </td> --}}
                                    @if ($utilisateur->reduction == 0)
                                        <td scope="row" style="font-family:Impact">
                                            <strong>{{ number_format($sum->somme_total, 2, ',', ' ') }} Ar</strong></td>
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
                <form
                    action="{{ route('devisb.sendReduction', ['id_devis' => $utilisateur->id, 'id_utilisateur' => $utilisateur->id_utilisateur]) }}"
                    method="get">
                    <button style="margin: 1%" type="submit" class="btn btn-success">Envoyer devis</button>
                </form>
            </div>
        </div>
    </main>
@endsection
