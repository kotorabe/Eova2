@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app2')
@section('content')
    <main id="main">
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
                    <h5 class="card-title">Devis de:</h5>
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
                                        <th scope="col">#</th>
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
                                        <td scope="row"><button data-bs-toggle="modal" data-bs-target="#Modallist"
                                                class="btn btn-info">Voir les biens</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-backdrop fade"></div>
            <!-- Modal delete -->
            <div class="modal fade" id="ModalAssigne" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('livraisonb.assignation') }}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Assignation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <input type="hidden" name="id_devis" id="id_devis">
                            <input type="hidden" name="id_equipe" id="id_equipe">
                            <input type="hidden" name="date_livraison" id="date_livraison">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="id">
                                <div class="alert alert-success" role="alert">Vous allez assigner le déménagement de
                                    <strong>{{ $utilisateur->nom }} {{ $utilisateur->prenom }}</strong> à la team : <strong><span id="team">
                                    </span></strong>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Retour
                                </button>
                                <button type="submit" class="btn btn-outline-success">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="content-backdrop fade"></div>
            <!-- Modal delete -->
            <div class="modal fade" id="Modallist" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        {{-- <form action="" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel1">Delete Post</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="id">
                                <div class="alert alert-danger" role="alert">Are you sure , you want to
                                    delete this post</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-outline-danger">Validate</button>
                            </div>
                        </form> --}}
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
                                                            <strong class="bi bi-arrow-down"
                                                                style="color:rgb(255, 163, 4)">-
                                                                {{ $utilisateur->reduction }}%</strong>
                                                        @endif
                                                    </th>
                                                    <th scope="col">Total @if ($utilisateur->reduction != 0)
                                                            <strong class="bi bi-arrow-down"
                                                                style="color:rgb(255, 163, 4)">-
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
                                                            <td scope="row">
                                                                {{ number_format($objet->prix, 2, ',', ' ') }} Ar</td>
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
                                                            <td scope="row">
                                                                <strong>{{ number_format($objet->total, 2, ',', ' ') }}
                                                                    Ar</strong>
                                                            </td>
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
                                                        <td scope="row">----</td>
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
                                                            <strong>{{ number_format($sum->somme_total, 2, ',', ' ') }}
                                                                Ar</strong>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Equipe(s) disponible(s):</h4>
                    <!-- Dark Table -->
                    <div class="container">
                        <hr>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead">
                                    <tr class="text-center">
                                        <th scope="col"># </th>
                                        <th scope="col">Team </th>
                                        <th scope="col">email </th>
                                        <th scope="col">###</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($equipes as $equipe)
                                        <tr>
                                            <td scope="row">{{ $equipe->id }}</td>
                                            <td scope="row">{{ $equipe->nom }}</td>
                                            <td scope="row">{{ $equipe->email }}</td>
                                            <td scope="row"><button data-bs-toggle="modal"
                                                    data-bs-target="#ModalAssigne" data-equipe="{{ $equipe->nom }}" data-date="{{ $utilisateur->date_demenagement }}"
                                                    data-id="{{ $utilisateur->id }}" data-idequipe="{{ $equipe->id }}"
                                                    class="btn btn-success">Assigner </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td scope="row">---------------</td>
                                            <td scope="row">---------------</td>
                                            <td scope="row">---------------</td>
                                            <td scope="row">---------------</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Au clic sur le bouton "Assigner"
            $('.btn-success').on('click', function() {
                // Récupérer la valeur de l'attribut data-equipe
                var equipeId = $(this).data('idequipe');
                var equipeNom = $(this).data('equipe');
                var DevisId = $(this).data('id');
                var Date = $(this).data('date');

                // Mettre à jour le contenu du span dans le modal avec le nom de l'équipe
                $('#team').text(equipeNom);
                $('#id_devis').val(DevisId);
                $('#id_equipe').val(equipeId);
                $('#date_livraison').val(Date);

                console.log(equipeId);
            });
        });
    </script>
@endsection
