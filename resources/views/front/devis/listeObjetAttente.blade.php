@extends('layouts.app3')

@section('content')
    <main id="main">
        @if (session('success_update'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_update') }}
                </div>
            </center>
        @endif
        @if (session('success_add'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_add') }}
                </div>
            </center>
        @endif
        @if (session('success_delete'))
            <center>
                <div class="alert alert-danger">
                    {{ session('success_delete') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Liste des objets</h5>
                <p><a href="{{ route('devis.Toaddobjet', ['id' => $id_devis]) }}" class="btn btn-success"
                        style="margin:2px">Ajouter d'autre(s) objet(s)</a></p>
                <!-- Dark Table -->
                <div class="container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="dark-thead">
                                <tr class="text-center">
                                    <th scope="col">Objet </th>
                                    <th scope="col">Catégorie </th>
                                    <th scope="col">Taille </th>
                                    <th scope="col">Quantité </th>
                                    <th scope="col">Poids</th>
                                    <th scope="col">##</th>
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
                                        @if ($objet->prix == 0)
                                            <td scope="row"><a
                                                    href="{{ route('devis.ObjetAttente', ['id' => $objet->id]) }}"
                                                    class="btn btn-info">Modifier</a></td>
                                            <td scope="row"><a
                                                    href="{{ route('devis.dltObjet', ['id' => $objet->id]) }}"
                                                    class="btn btn-danger" id="btnSupprimer">Supprimer</a></td>
                                        @else
                                            <td scope="row">---</td>
                                            <td scope="row">---</td>
                                        @endif
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
                                    <td scope="row" style="font-family:Impact">-----</td>
                                    <td scope="row"></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- End Dark Table -->

            </div>
        </div>
    </main>
@endsection
