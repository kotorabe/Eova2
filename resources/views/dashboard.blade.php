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
                                                    <i class="bi bi-truck-flatbed"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>{{ $accepter }}</h6>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div><!-- End Revenue Card -->

                                <!-- Customers Card -->
                                <div class="col-xxl-4 col-xl-12">

                                    <div class="card info-card customers-card">

                                        <div class="card-body">
                                            <h5 class="card-title">En attente de réduction</h5>

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
                            </div>
                        </div>
                        <div class="col-lg-4">

                            <!-- Recent Activity -->
                            <div class="card">
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
                                            <button type="submit" class="btn btn-warning">Purger devis refusé</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                </section>
                <div class="col-lg-8">
                    <div class="row">

                        <!-- Sales Card -->
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
                                            <h6>{{ $demande }}</h6>

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
                                            <h6>{{ $accepter }}</h6>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
