@extends('front.layouts.front')

@section('title', $alerte->titre)
@section('description', Str::limit($alerte->message, 150))

@section('content')
    <section class="ve-section py-5">
        <div class="container">
            {{-- Navigation retour --}}
            <div class="mb-4">
                <a href="{{ route('alertes-meteo.index') }}" class="text-decoration-none text-muted fw-semibold">
                    <i class="fa fa-arrow-left me-1"></i> Retour à la liste des alertes
                </a>
            </div>

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                {{-- En-tête avec niveau d'alerte --}}
                @php
                    $couleur = $alerte->niveauAlerte->couleur ?? 'vert';
                    $headerBg = match($couleur) {
                        'vert' => 'bg-success text-white',
                        'jaune' => 'bg-warning text-dark',
                        'orange' => 'text-white',
                        'rouge' => 'bg-danger text-white',
                        default => 'bg-secondary text-white'
                    };
                    $headerStyle = ($couleur === 'orange') ? 'background-color: #fd7e14 !important;' : '';
                @endphp

                <div class="card-header p-4 {{ $headerBg }}" style="{{ $headerStyle }}">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <span class="badge bg-white text-dark px-3 py-2 fs-6 rounded-pill fw-bold">
                            Level {{ $alerte->niveauAlerte->niveau ?? '-' }} - {{ strtoupper($couleur) }} ({{ $alerte->niveauAlerte->libelle ?? '' }})
                        </span>
                        <span class="fs-6">
                            <i class="fa fa-calendar me-1"></i> Début : {{ $alerte->date_debut->format('d/m/Y à H:i') }}
                            @if ($alerte->date_fin)
                                | Fin : {{ $alerte->date_fin->format('d/m/Y à H:i') }}
                            @endif
                        </span>
                    </div>
                    <h2 class="mt-3 mb-0 text-white fw-bold">{{ $alerte->titre }}</h2>
                </div>

                {{-- Corps du détail --}}
                <div class="card-body p-4 p-md-5">
                    @if ($alerte->source)
                        <div class="mb-4 p-3 bg-light rounded-3 border">
                            <i class="fa fa-info-circle text-primary me-2"></i>
                            <strong>Source officielle :</strong> {{ $alerte->source }}
                        </div>
                    @endif

                    <h4 class="fw-bold mb-3">Bulletin d'information & Consignes :</h4>
                    <div class="fs-5 text-secondary leading-relaxed mb-4">
                        {!! nl2br(e($alerte->message)) !!}
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 text-muted small">
                        <span>
                            <i class="fa fa-clock-o me-1"></i> Publié le {{ $alerte->created_at->format('d/m/Y à H:i') }}
                        </span>
                        <a href="{{ route('alertes-meteo.index') }}" class="ve-btn-primary px-4 py-2">
                            Voir toutes les alertes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
