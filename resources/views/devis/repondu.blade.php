@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app2')

@section('content')
    <main id="main">
        @if (session('success_send_reduction'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_send_reduction') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste des devis répondu</h5>

                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="wait-tab" data-bs-toggle="tab" data-bs-target="#bordered-wait"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">En attente de réduction</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="get-tab" data-bs-toggle="tab" data-bs-target="#bordered-get"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">En attente de réponse</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="refus-tab" data-bs-toggle="tab" data-bs-target="#bordered-refus"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Refuser</button>
                    </li>
                </ul>
                <div class="tab-content pt-2" id="borderedTabContent">
                    <div class="tab-pane fade show active" id="bordered-wait" role="tabpanel" aria-labelledby="wait-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Devis du: </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($attentes as $attente)
                                        <tr>
                                            <td scope="row">{{ $attente->id }}</td>
                                            <td scope="row">{{ $attente->nom }} {{ $attente->prenom }}</td>
                                            <td scope="row">{{ $attente->email }}</td>
                                            <td>{{ Carbon::parse($attente->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td>{{ Carbon::parse($attente->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('devisb.allObjetReduction', ['id_devis' => $attente->id, 'id_utilisateur' => $attente->id_utilisateur]) }}"
                                                    class="btn btn-info">Voir Devis</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Aucune devis en attente de réduction.</td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bordered-get" role="tabpanel" aria-labelledby="get-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Devis du: </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($envoyers as $envoyer)
                                        <tr>
                                            <td scope="row">{{ $envoyer->id }}</td>
                                            <td scope="row">{{ $envoyer->nom }} {{ $envoyer->prenom }}</td>
                                            <td scope="row">{{ $envoyer->email }}</td>
                                            <td>{{ Carbon::parse($envoyer->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td>{{ Carbon::parse($envoyer->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('devisb.listeObjetEnvoyer', ['id' => $envoyer->id, 'id_utilisateur' => $envoyer->id_utilisateur]) }}"
                                                    class="btn btn-info">Voir Devis</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Aucune devis en attente de reponse.</td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bordered-refus" role="tabpanel" aria-labelledby="refus-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Client</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Devis du: </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($refuser as $refus)
                                        <tr>
                                            <td scope="row">{{ $refus->id }}</td>
                                            <td scope="row">{{ $refus->nom }} {{ $refus->prenom }}</td>
                                            <td scope="row">{{ $refus->email }}</td>
                                            <td>{{ Carbon::parse($refus->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td>{{ Carbon::parse($refus->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('devisb.listeObjetAttente', ['id' => $refus->id, 'id_utilisateur' => $refus->id_utilisateur]) }}"
                                                    class="btn btn-success">Démarrer la procédure</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Aucune devis refuser.</td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- End Bordered Tabs -->

            </div>
        </div>
    </main>

    <script>
        var collapse = document.querySelector('.nav-link.collapsed');
        var show = document.querySelector('.nav-content.collapse');
        var active = document.querySelector('.list.repondu');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        var listdemande = document.querySelector('.nav-item.devis .repondu');
        listdemande.classList.add("active");
    </script>
@endsection
