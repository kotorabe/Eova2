@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app3')

@section('content')
    <main id="main">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Demande de devis de:</h5>
                <!-- Dark Table -->
                <div class="container">
                    <div class="table-responsive">
                        <table class="table table-dark">
                            <thead class="thead">
                                <tr class="text-center">
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
                                            <strong class="bi bi-arrow-down" style="color:green">-
                                                {{ $utilisateur->reduction }}%</strong>
                                        @endif
                                    </th>
                                    <th scope="col">Total @if ($utilisateur->reduction != 0)
                                            <strong class="bi bi-arrow-down" style="color:green">-
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
                                        <td scope="row">---</td>
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
                                            <strong>{{ number_format($sum->somme_total, 2, ',', ' ') }} Ar</strong>
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
                <p><a href="{{ route('devis.accepte', ['id' => $utilisateur->id]) }}" class="btn btn-success">Accepter</a>
                </p>
                <p><a href="{{ route('devis.reduction', ['id' => $utilisateur->id]) }}" id="reduction"
                        class="btn btn-warning{{ $utilisateur->reduction != 0 ? ' disabled' : '' }}">Demander une réduction du prix</a></p>
                <p><a href="{{ route('devis.refus', ['id' => $utilisateur->id]) }}" class="btn btn-danger">Refuser</a></p>

            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            if (<?= strtotime($date_demenagement) - time() ?> <= 4 * 24 * 60 * 60) {
                $('#reduction').hide();
            }
        });
    </script>
@endsection
