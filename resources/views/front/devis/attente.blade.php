@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app3')

@section('content')
    <main id="main">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Devis en Attente</h5>

                <!-- Dark Table -->
                <div class="table-responsive">
                    <table class="table">
                        <thead class="dark-thead">
                            <tr class="text-center">
                                <th scope="col">#</th>
                                <th scope="col">Demande du: </th>
                                <th scope="col">Pour un déménagement le:</th>
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if ($attente != null)
                                <tr>
                                    <td scope="row">#</td>
                                    <td scope="row">{{ Carbon::parse($attente->updated_at)->locale('fr')->isoFormat('DD MMMM YYYY') }}
                                    </td>
                                    <td scope="row">{{ Carbon::parse($attente->date_demenagement)->locale('fr')->isoFormat('DD MMMM YYYY') }}</td>
                                    <td scope="row"><a href="{{ route('devis.listeObjetAttente', ['id' => $attente->id]) }}"
                                            class="btn btn-info">Voir les objets</a></td>
                                </tr>
                            @else
                            @endif

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
        var active = document.querySelector('.list.attente');
        collapse.classList.remove("collapsed");
        collapse.classList.add("active");
        show.classList.add("show");
        active.classList.add("active");
        // var listdemande = document.querySelector('.nav-item.devis .demande');
        // listdemande.classList.add("active");
    </script>
@endsection
