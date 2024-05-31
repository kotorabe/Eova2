@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app2')

@section('content')
    <main id="main">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste des livraisons</h5>

                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="wait-tab" data-bs-toggle="tab" data-bs-target="#bordered-wait"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Planifier</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="get-tab" data-bs-toggle="tab" data-bs-target="#bordered-get"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Livraison</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="refus-tab" data-bs-toggle="tab" data-bs-target="#bordered-refus"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">En cours</button>
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
                                        {{-- <th scope="col">Devis du :</th> --}}
                                        <th scope="col">Equipe en charge </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($planifier as $plan)
                                        <tr>
                                            <td scope="row">{{ $plan->id }}</td>
                                            <td scope="row">{{ $plan->client_nom }} {{ $plan->client_prenom }}</td>
                                            {{-- <td>{{ Carbon::parse($plan->created_at)->locale('fr')->isoFormat('DD MMMM YYYY') }} --}}
                                            <td scope="row">{{ $plan->equipe }}</td>
                                            </td>
                                            <td>{{ Carbon::parse($plan->date_livraison)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('livraisonb.DetailPlanifier', ['id' => $plan->id]) }}"
                                                    class="btn btn-info">Voir</a></td>
                                            {{-- <td><a href=""><i class="bi bi-truck text-danger"></i></a></td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td>Aucune livraison planifier.</td>
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
                                        {{-- <th scope="col">Devis du:</th> --}}
                                        <th scope="col">Equipe en charge </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($livraison as $livr)
                                        <tr>
                                            <td scope="row">{{ $livr->id }}</td>
                                            <td scope="row">{{ $livr->client_nom }} {{ $livr->client_prenom }}</td>
                                            {{-- <td>{{ Carbon::parse($livr->created_at)->locale('fr')->isoFormat('DD MMMM YYYY') }} --}}
                                            <td scope="row">{{ $livr->equipe }}</td>
                                            </td>
                                            <td>{{ Carbon::parse($livr->date_livraison)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('livraisonb.DetailPlanifier', ['id' => $livr->id]) }}"
                                                    class="btn btn-info">Voir</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td>Aucune livraison pour aujourd'hui.</td>
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
                                        <th scope="col">Equipe en charge </th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @forelse ($encours as $cours)
                                        @php
                                            $statut = '';
                                            $couleur = '';
                                        @endphp
                                        @if ($cours->etat == 2)
                                            @php
                                                $statut = 'Vers Récupération';
                                                $couleur = 'green';
                                            @endphp
                                        @elseif ($cours->etat == 3)
                                            @php
                                                $statut = 'Livraison';
                                                $couleur = 'green';
                                            @endphp
                                        @endif
                                        <tr>
                                            <td scope="row">{{ $cours->id }}</td>
                                            <td scope="row">{{ $cours->client_nom }} {{ $cours->client_prenom }}</td>
                                            <td scope="row">{{ $cours->equipe }}</td>
                                            </td>
                                            <td scope="row" style="color:{{ $couleur }}">{{ $statut }}</td>
                                            <td><a href="{{ route('livraisonb.SuiviLivraison', ['id' => $cours->id]) }}"
                                                    class="btn btn-info">Voir</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td></td>
                                            {{-- <td></td> --}}
                                            <td></td>
                                            <td>Aucune livraison en cours.</td>
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
        var collapse = document.querySelector('.nav-link.collapsed.livraison');
        var show = document.querySelector('.nav-content.collapse.livraison');
        var active = document.querySelector('.list.livraison');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var list = document.querySelector('.nav-item.livraison .voir');
        // list.classList.add("active");
    </script>
@endsection
