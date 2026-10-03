@extends('front.layouts.front')

@section('title', 'Accès refusé (403)')

@section('content')
    <section class="app-inner-section">
        <div class="container">
            <div class="app-form-card">
                <div class="ve-contact-form-wrap app-error-box">
                    <div class="app-error-code">403</div>
                    <h1>Accès refusé</h1>
                    <p>
                        Vous n'avez pas les droits nécessaires pour consulter cette page.
                        Seuls les administrateurs y ont accès.
                    </p>
                    <a href="{{ route('front.home') }}" class="ve-btn-primary">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </section>
@endsection
