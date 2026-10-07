@extends('back.layouts.back')

@section('title', 'Détails de l\'alerte météo')

@section('content')
    <x-page-header title="Détails de l'alerte météo" :subtitle="$alerteMeteo->titre">
        @slot('actions')
            <a href="{{ route('admin.alertes-meteo.edit', $alerteMeteo) }}" class="btn btn-primary">
                <i class="bx bx-edit me-1"></i> Modifier
            </a>
            <a href="{{ route('admin.alertes-meteo.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour à la liste
            </a>
        @endslot
    </x-page-header>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">{{ $alerteMeteo->titre }}</h5>
                    @if ($alerteMeteo->niveauAlerte)
                        @switch($alerteMeteo->niveauAlerte->couleur)
                            @case('vert')
                                <span class="badge bg-success fs-6">Vert (Niveau {{ $alerteMeteo->niveauAlerte->niveau }})</span>
                                @break
                            @case('jaune')
                                <span class="badge bg-warning text-dark fs-6">Jaune (Niveau {{ $alerteMeteo->niveauAlerte->niveau }})</span>
                                @break
                            @case('orange')
                                <span class="badge text-white fs-6" style="background-color: #fd7e14 !important;">Orange (Niveau {{ $alerteMeteo->niveauAlerte->niveau }})</span>
                                @break
                            @case('rouge')
                                <span class="badge bg-danger fs-6">Rouge (Niveau {{ $alerteMeteo->niveauAlerte->niveau }})</span>
                                @break
                            @default
                                <span class="badge bg-secondary fs-6">{{ $alerteMeteo->niveauAlerte->libelle }}</span>
                        @endswitch
                    @endif
                </div>
                <div class="card-body mt-3">
                    <h6 class="fw-semibold">Message de l'alerte :</h6>
                    <div class="p-3 bg-lighter rounded border">
                        {!! nl2br(e($alerteMeteo->message)) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Informations & Dates</h5>
                </div>
                <div class="card-body mt-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Niveau d'alerte</span>
                            <span>{{ $alerteMeteo->niveauAlerte->libelle ?? 'Non spécifié' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Date de début</span>
                            <span>{{ $alerteMeteo->date_debut->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Date de fin</span>
                            <span>{{ $alerteMeteo->date_fin ? $alerteMeteo->date_fin->format('d/m/Y H:i') : 'Non définie' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Source</span>
                            <span>{{ $alerteMeteo->source ?? 'Non renseignée' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Créée le</span>
                            <span>{{ $alerteMeteo->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
