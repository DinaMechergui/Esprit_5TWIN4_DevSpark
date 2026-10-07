@extends('front.layouts.front')

@section('title', $conseil->titre)
@section('description', \Illuminate\Support\Str::limit(strip_tags($conseil->contenu), 150))

@section('content')
    <section class="ve-section">
        <div class="container">
            {{-- Fil d'Ariane --}}
            <nav class="mb-4" aria-label="Fil d'Ariane">
                <a href="{{ route('front.home') }}">Accueil</a>
                <span>/</span>
                <a href="{{ route('conseils.index') }}">Conseils</a>
                <span>/</span>
                <strong>{{ $conseil->titre }}</strong>
            </nav>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <article class="card shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <a href="{{ route('conseils.index', ['categorie' => $conseil->categorie->id]) }}"
                               class="badge badge-info mb-3">
                                {{ $conseil->categorie->nom }}
                            </a>

                            <h2 class="mb-3">{{ $conseil->titre }}</h2>

                            <p class="text-muted small mb-4">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                Publié le {{ $conseil->created_at?->format('d/m/Y') }}
                            </p>

                            <div class="conseil-contenu">
                                {!! nl2br(e($conseil->contenu)) !!}
                            </div>
                        </div>
                    </article>

                    <div class="mt-4">
                        <a href="{{ route('conseils.index') }}" class="btn btn-outline-primary">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour aux conseils
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
