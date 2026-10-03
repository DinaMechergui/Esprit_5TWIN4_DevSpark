@extends('front.layouts.front')

@section('title', 'Erreur serveur (500)')

@section('content')
    <section class="app-inner-section">
        <div class="container">
            <div class="app-form-card">
                <div class="ve-contact-form-wrap app-error-box">
                    <div class="app-error-code">500</div>
                    <h1>Erreur serveur</h1>
                    <p>
                        Une erreur inattendue s'est produite. Nos équipes ont été prévenues,
                        veuillez réessayer dans quelques instants.
                    </p>
                    <a href="{{ route('front.home') }}" class="ve-btn-primary">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </section>
@endsection
