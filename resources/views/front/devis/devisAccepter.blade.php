@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app3')

@section('content')
    <main id="main">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Devis accepté</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Devis du: </th>
                                <th scope="col">Déménagement pour le:</th>
                                <th scope="col">Status</th>
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
                                    <td></td>
                                    <td><a href="{{ route('devis.devisObjetAccepter', ['id' => $accepter->id]) }}"
                                            class="btn btn-info">Voir le devis</a></td>
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
    </main>

    <script>
        var collapse = document.querySelector('.nav-link.collapsed');
        var show = document.querySelector('.nav-content.collapse');
        var active = document.querySelector('.list.accepter');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.devis .demande');
        // listdemande.classList.add("active");
    </script>
@endsection
