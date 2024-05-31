@extends('layouts.app2')

@section('content')
    <main id="main">
        @if (session('success_purge'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_purge') }}
                </div>
            </center>
        @endif
        @if (session('success_null'))
            <center>
                <div class="alert alert-warning">
                    {{ session('success_null') }}
                </div>
            </center>
        @endif
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tableau de bord</h4>
                {{-- <h5 class="card-title"></h5> --}}
                <section class="section dashboard">
                    <div class="row">
                        <!-- Left side columns -->
                        <div class="col-lg-8">
                            <div class="row">

                                <!-- Sales Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card sales-card">

                                        <div class="card-body">
                                            <h5 class="card-title">Demande de devis</h5>

                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $demande }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End Sales Card -->

                                <!-- Revenue Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card revenue-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Devis accepté</h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $accepter }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End Revenue Card -->

                                <!-- Customers Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card customers-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Attente de réduction</h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-currency-dollar"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $reduction }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Revenue Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card customers-card">

                                        <div class="card-body">
                                            <h5 class="card-title">Attente de réponse</h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $reponse }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End Revenue Card -->


                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card sales-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Planifié</h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-calendar-check"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $planifier }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card sales-card">

                                        <div class="card-body">
                                            <h5 class="card-title">Date actuelle:</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="ps-3">
                                                    <h6>{{ $dateActuelle }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <!-- Recent Activity -->
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Devis refusé</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-trash"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $refuser }}</h6>
                                        </div>
                                    </div>
                                    <div class="container">
                                        <form action="{{ route('dashboard.purgeDevis') }}" method="post">
                                            @csrf
                                            <br>
                                            <button type="submit" class="btn btn-warning">Purger devis refusé</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="row">
                                <!-- Sales Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card revenue-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Livraison</h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-truck-front"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $livraison }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card revenue-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Livraison en cours</h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-truck"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $encours }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card customers-card">
                                        <div class="card-body">
                                            <h5 class="card-title">Livraison terminé</h5>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-truck-flatbed"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $fini }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Statistiques</h5>

                                    <!-- Line Chart -->
                                    <canvas id="lineChart" style="max-height: 400px;"></canvas>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            const counts =
                                                {!! json_encode($demande_stat) !!}; // Assurez-vous d'échapper correctement les données pour éviter les attaques XSS
                                            const counts_stat =
                                                {!! json_encode($realisation_stat) !!};
                                            const monthNames = [
                                                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                                                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
                                            ];
                                            const months = counts.map(item => monthNames[item.month]);
                                            const months_stat = counts_stat.map(item => monthNames[item.month]);
                                            const dataCounts = counts.map(item => item.count);
                                            const dataCounts_stat = counts_stat.map(item => item.count);
                                            // new Chart(document.querySelector('#lineChart'), {
                                            //     type: 'line',
                                            //     data: {
                                            //         labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                                            //             'Septembre', 'Novembre', 'Décembre'
                                            //         ],
                                            //         datasets: [{
                                            //                 label: 'Line 1',
                                            //                 data: [65, 59, 80, 81, 56, 55, 40],
                                            //                 fill: false,
                                            //                 borderColor: 'rgb(75, 192, 192)',
                                            //                 tension: 0.1
                                            //             },
                                            //             {
                                            //                 label: 'Line 2',
                                            //                 data: [30, 40, 50, 60, 70, 80, 150], // Données pour la deuxième ligne
                                            //                 fill: false,
                                            //                 borderColor: 'rgb(255, 99, 132)',
                                            //                 tension: 0.1
                                            //             }
                                            //         ]
                                            //     },
                                            //     options: {
                                            //         scales: {
                                            //             y: {
                                            //                 beginAtZero: true
                                            //             }
                                            //         }
                                            //     }
                                            // });
                                            new Chart(document.querySelector('#lineChart'), {
                                                type: 'line',
                                                data: {
                                                    labels: months,
                                                    datasets: [{
                                                            label: 'Nombre de demande',
                                                            data: dataCounts,
                                                            fill: true,
                                                            borderColor: 'rgb(75, 192, 192)',
                                                            tension: 0.1
                                                        },
                                                        {
                                                            label: 'Réalisation',
                                                            data: dataCounts_stat, // Données pour la deuxième ligne
                                                            fill: true,
                                                            borderColor: 'rgb(255, 99, 132)',
                                                            tension: 0.1
                                                        }
                                                    ]
                                                },
                                                options: {
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true
                                                        }
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                    <!-- End Line CHart -->

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
