@extends('front.layouts.front')

@section('title', 'Page introuvable (404)')

@section('content')
    <section class="app-inner-section">
        <div class="container">
            <div class="app-form-card">
                <div class="ve-contact-form-wrap app-error-box">
                    <div class="app-error-code">404</div>
                    <h1>Page introuvable</h1>
                    <p>
                        La page que vous recherchez n'existe pas ou a été déplacée.
                        Elle sera peut-être disponible prochainement.
                    </p>
                    <a href="{{ route('front.home') }}" class="ve-btn-primary">Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </section>
@endsection
