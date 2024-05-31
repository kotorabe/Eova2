@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app3')

@section('content')
    <main id="main">
        @if (session('date_accept'))
            <center>
                <div class="alert alert-success">
                    {{ session('date_accept') }}
                </div>
            </center>
        @endif
        @if (session('error'))
            <center>
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Devis accepté</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Devis du: </th>
                                @if ($indispo == 1)
                                    <th scope="col">Déménagement pour le:</th>
                                @else
                                    <th scope="col" style="color:red">Date Indisponible :</th>
                                @endif
                                @if ($indispo == 1)
                                    {{-- <th scope="col">Déménagement pour le:</th> --}}
                                @else
                                    <th scope="col" style="color:green">Date disponible :</th>
                                @endif
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if ($accepter != null)
                                <tr>
                                    <td scope="row"></td>
                                    <td>{{ Carbon::parse($accepter->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    <td>{{ Carbon::parse($accepter->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    @if ($indispo == 1)
                                        {{--  --}}
                                        <td><a href="{{ route('devis.devisObjetAccepter', ['id' => $accepter->id]) }}"
                                                class="btn btn-info">Voir le devis</a></td>
                                    @else
                                        <td>{{ Carbon::parse($dispo->date_livraison)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#ModalAccept">Oui</button>
                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#ModalExit">X</button>
                                        </td>
                                    @endif
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

        </div>

        @if ($indispo == 2)
        <div class="content-backdrop fade"></div>
        <!-- Modal delete -->
        <div class="modal fade" id="ModalAccept" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('devis.acceptDateDispo') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Accepter pour cette date?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                            <input type="hidden" name="id_devis" value="{{ $accepter->id }}">
                            <input type="hidden" name="id_livraison" value="{{ $dispo->id }}">
                            <input type="hidden" name="date_livraison" value="{{ $dispo->date_livraison }}">

                        <div class="modal-body">
                            <div class="alert alert-success" role="alert">Vous êtes sur le point de planifier votre
                                déménagement pour le :
                                <strong>{{ Carbon::parse($dispo->date_livraison)->locale('fr')->isoFormat('DD MMMM YYYY') }}</strong>
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
        @endif

        @if ($indispo == 2)
        <div class="content-backdrop fade"></div>
        <!-- Modal delete -->
        <div class="modal fade" id="ModalExit" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('devis.RefusDateDispo') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Annuler la planification?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                            <input type="hidden" name="id_devis" value="{{ $accepter->id }}">
                            <input type="hidden" name="id_livraison" value="{{ $dispo->id }}">
                        <div class="modal-body">
                            <div class="alert alert-danger" role="alert">Êtes-vous sûr de vouloir annuler vôtre planification?
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
        @endif

    </main>

    <script>
        var collapse = document.querySelector('.nav-link.collapsed');
        var show = document.querySelector('.nav-content.collapse');
        var active = document.querySelector('.list.accepter');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
    </script>
@endsection
