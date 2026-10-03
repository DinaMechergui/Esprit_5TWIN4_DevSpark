@extends('front.layouts.front')

@section('title', 'Mon profil')

@section('content')
    <section class="app-inner-section">
        <div class="container">
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Espace personnel</span>
                <h2>Mon <span>profil</span></h2>
                <p>Gérez vos informations, votre mot de passe et votre compte.</p>
            </div>

            <div class="app-form-card-wide">
                <div class="row">
                    {{-- Informations personnelles --}}
                    <div class="col-lg-7 mb-4">
                        <div class="ve-contact-form-wrap h-100">
                            @include('front.profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="col-lg-5 mb-4">
                        {{-- Mot de passe --}}
                        <div class="ve-contact-form-wrap mb-4">
                            @include('front.profile.partials.update-password-form')
                        </div>

                        {{-- Suppression du compte --}}
                        <div class="ve-contact-form-wrap app-danger-zone">
                            @include('front.profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
