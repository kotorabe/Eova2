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
                <h5 class="card-title">Liste des devis acceptés</h5>
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
                            @forelse ($accepter as $accepte)
                                <tr>
                                    <td scope="row">{{ $accepte->id }}</td>
                                    <td scope="row">{{ $accepte->nom }} {{ $accepte->prenom }}</td>
                                    <td scope="row">{{ $accepte->email }}</td>
                                    <td>{{ Carbon::parse($accepte->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    <td>{{ Carbon::parse($accepte->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    <td><a href="{{ route('devisb.redirectionToAssignation', ['id' => $accepte->id]) }}"
                                            class="btn btn-success">Assigner une équipe</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Aucun devis accepter.</td>
                                    <td></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- End Bordered Tabs -->

            </div>
        </div>
    </main>

    <script>
        var collapse = document.querySelector('.nav-link.collapsed');
        var show = document.querySelector('.nav-content.collapse');
        var active = document.querySelector('.list.accepter');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.devis .accepter');
        // listdemande.classList.add("active");
    </script>
@endsection
