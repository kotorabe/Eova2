@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app3')

@section('content')
    <main id="main">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Devis répondu</h5>

                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Devis reçu</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">En attente de
                            réduction</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="refus-tab" data-bs-toggle="tab" data-bs-target="#bordered-refus"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Refuser</button>
                    </li>
                </ul>
                <div class="tab-content pt-2" id="borderedTabContent">
                    <div class="tab-pane fade show active" id="bordered-home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Devis du: </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if ($devis_recu != null)
                                        <tr>
                                            <td scope="row"></td>
                                            <td>{{ Carbon::parse($devis_recu->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td>{{ Carbon::parse($devis_recu->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('devis.recu', ['id' => $devis_recu->id]) }}" class="btn btn-info">Voir le devis</a></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="bordered-profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Devis du: </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if ($attente != null)
                                        <tr>
                                            <td scope="row"></td>
                                            <td>{{ Carbon::parse($attente->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td>{{ Carbon::parse($attente->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('devis.allObjetReduction', ['id_devis' => $attente->id]) }}" class="btn btn-info">Voir le devis</a></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                        </tr>
                                    @endif
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
                                        <th scope="col">Devis du: </th>
                                        <th scope="col">Déménagement pour le:</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if ($refuser != null)
                                        <tr>
                                            <td scope="row"></td>
                                            <td>{{ Carbon::parse($refuser->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td>{{ Carbon::parse($refuser->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                            </td>
                                            <td><a href="{{ route('devis.allObjetRefuser', ['id_devis' => $refuser->id]) }}" class="btn btn-info">Voir le devis</a></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                            <td scope="row">-----</td>
                                        </tr>
                                    @endif
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
        // var listdemande = document.querySelector('.nav-item.devis .demande');
        // listdemande.classList.add("active");
    </script>
@endsection
