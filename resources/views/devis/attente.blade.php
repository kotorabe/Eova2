@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app2')

@section('content')
    <main id="main">
        @if (session('success_send_devis'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_send_devis') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Devis en Attente de réponse</h5>

                <!-- Dark Table -->
                <div class="table-responsive">
                    <table class="table">
                        <thead class="dark-thead">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Client</th>
                                <th scope="col">Email</th>
                                <th scope="col">Demande du: </th>
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
                                    <td><a href="{{ route('devisb.listeObjetAttente', ['id' => $attente->id, 'id_utilisateur' => $attente->id_utilisateur]) }}"
                                            class="btn btn-info">Faire un devis</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Aucune demande devis.</td>
                                    <td></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- End Dark Table -->
            </div>
        </div>
    </main>

    <script>
        var collapse = document.querySelector('.nav-link.collapsed');
        var show = document.querySelector('.nav-content.collapse');
        var active = document.querySelector('.list.demande');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.devis .demande');
        // listdemande.classList.add("active");
    </script>
@endsection
