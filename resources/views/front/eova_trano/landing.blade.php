@extends('layouts.app3')

@section('content')
    <main id="main">
        <!-- Devis en cours -->
        @if (session('exist_devis'))
            <center>
                <div class="alert alert-warning">
                    {{ session('exist_devis') }}
                </div>
            </center>
        @endif
        <!-- Problème -->
        @if (session('erreur_devis'))
            <center>
                <div class="alert alert-danger">
                    {{ session('erreur_devis') }}
                </div>
            </center>
        @endif
        @if (session('success'))
            <center>
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </center>
        @endif
        <!-- Devis accepter -->
        @if (session('success_accept'))
            <center>
                <div class="alert alert-success">
                    {{ session('success_accept') }}
                </div>
            </center>
        @endif
        {{-- Devis supprimer --}}
        @if (session('success_suppr'))
            <center>
                <div class="alert alert-warning">
                    {{ session('success_suppr') }}
                </div>
            </center>
        @endif
        <!-- Demande Reduction -->
        @if (session('success_attente'))
            <center>
                <div class="alert alert-warning">
                    {{ session('success_attente') }}
                </div>
            </center>
        @endif
        {{-- Refus de devis --}}
        @if (session('success_refus'))
            <center>
                <div class="alert alert-warning">
                    {{ session('success_refus') }}
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
        <p class="text-center" style="font-size: 25px">Besoin d'aide pour planifier vôtre déménagement?<br>Laisser nous vous
            aidez .</p>
        <div class="col-md-12 text-center">
            <a href='{{ route('devis.addresse') }}' class="btn btn-primary w-50">Faire une demande de devis</a>
        </div>
        <br><br>
        <section class="section contact">
            <div class="row">
                <div class="col-sm-6">
                    <div class="info-box card">
                        <center>
                            <i class="bi bi-geo-alt"></i>
                            <h3>Address</h3>
                            <p>Ambohidroa<br>Tetezana, BR 125</p>
                        </center>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-box card">
                        <center>
                            <i class="bi bi-telephone"></i>
                            <h3>Call Us</h3>
                            <p>+261 32 00 012 01<br>+261 34 00 012 02</p>
                        </center>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-box card">
                        <center>
                            <i class="bi bi-envelope"></i>
                            <h3>Email Us</h3>
                            <p>eova@trano.com<br>eova@info.com</p>
                        </center>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-box card">
                        <center>
                            <i class="bi bi-clock"></i>
                            <h3>Open Hours</h3>
                            <p>Lundi - Samedi<br>8:30 - 17:00</p>
                        </center>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
