@extends('front.layouts.front')

@section('title', 'Alertes Météo')
@section('description', 'Consultez les alertes météo en cours et à venir pour anticiper les vagues de chaleur et risques météorologiques.')

@section('content')
    <section class="ve-section pf-section">
        <div class="container">
            {{-- En-tête de section --}}
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Vigilance & Météo</span>
                <h2>Alertes <span>Météorologiques</span></h2>
                <p>
                    Restez informés des alertes météo et consignes de sécurité en cours dans votre région.
                    {{ $alertes->total() }} alerte(s) répertoriée(s).
                </p>
            </div>

            {{-- Filtres : Actives / Toutes --}}
            <div class="d-flex justify-content-center gap-2 mb-4">
                <a href="{{ route('alertes-meteo.index') }}"
                   class="btn btn-sm rounded-pill px-3 py-2 {{ ($filtre ?? 'actives') === 'actives' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fa fa-bell me-1"></i> Alertes actives (en cours)
                </a>
                <a href="{{ route('alertes-meteo.index', ['filtre' => 'toutes']) }}"
                   class="btn btn-sm rounded-pill px-3 py-2 {{ ($filtre ?? 'actives') === 'toutes' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fa fa-history me-1"></i> Toutes les alertes (historique)
                </a>
            </div>

            {{-- Grille des alertes météo --}}
            <div class="row g-4 mt-2">
                @forelse ($alertes as $alerte)
                    <div class="col-lg-6">
                        <article class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden position-relative" style="background: #ffffff;">
                            {{-- Bande supérieure de couleur selon le niveau --}}
                            @php
                                $couleur = $alerte->niveauAlerte->couleur ?? 'vert';
                                $borderClass = match($couleur) {
                                    'vert' => 'border-start border-success border-5',
                                    'jaune' => 'border-start border-warning border-5',
                                    'orange' => 'border-start border-5',
                                    'rouge' => 'border-start border-danger border-5',
                                    default => 'border-start border-secondary border-5'
                                };
                                $styleBorder = ($couleur === 'orange') ? 'border-color: #fd7e14 !important;' : '';
                            @endphp

                            <div class="card-body p-4 {{ $borderClass }}" style="{{ $styleBorder }}">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    {{-- Badge de couleur & Niveau --}}
                                    @if ($alerte->niveauAlerte)
                                        @switch($alerte->niveauAlerte->couleur)
                                            @case('vert')
                                                <span class="badge bg-success px-3 py-2 fs-6">
                                                    <i class="fa fa-check-circle me-1"></i> Vert - {{ $alerte->niveauAlerte->libelle }}
                                                </span>
                                                @break
                                            @case('jaune')
                                                <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                                                    <i class="fa fa-exclamation-triangle me-1"></i> Jaune - {{ $alerte->niveauAlerte->libelle }}
                                                </span>
                                                @break
                                            @case('orange')
                                                <span class="badge text-white px-3 py-2 fs-6" style="background-color: #fd7e14 !important;">
                                                    <i class="fa fa-exclamation-circle me-1"></i> Orange - {{ $alerte->niveauAlerte->libelle }}
                                                </span>
                                                @break
                                            @case('rouge')
                                                <span class="badge bg-danger px-3 py-2 fs-6">
                                                    <i class="fa fa-shield me-1"></i> Rouge - {{ $alerte->niveauAlerte->libelle }}
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary px-3 py-2 fs-6">
                                                    {{ $alerte->niveauAlerte->libelle }}
                                                </span>
                                        @endswitch
                                    @endif

                                    <small class="text-muted">
                                        <i class="fa fa-clock-o"></i> {{ $alerte->date_debut->format('d/m/Y H:i') }}
                                    </small>
                                </div>

                                <h4 class="card-title text-dark fw-bold mb-2">
                                    <a href="{{ route('alertes-meteo.show', $alerte->id) }}" class="text-dark text-decoration-none">
                                        {{ $alerte->titre }}
                                    </a>
                                </h4>

                                <p class="card-text text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ Str::limit($alerte->message, 180) }}
                                </p>

                                <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                                    <span class="small text-muted">
                                        <i class="fa fa-building-o"></i> {{ $alerte->source ?? 'Institut National de la Météorologie (INM)' }}
                                    </span>

                                    <a href="{{ route('alertes-meteo.show', $alerte->id) }}" class="ve-btn-primary py-1 px-3 fs-6">
                                        Voir détails <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="pf-empty">
                            <i class="fa fa-sun-o fa-3x text-muted mb-3" aria-hidden="true"></i>
                            <h4>Aucune alerte météo active</h4>
                            <p class="text-muted">Aucune alerte météorologique particulière n'est actuellement signalée.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-5 d-flex justify-content-center">
                <x-pagination :paginator="$alertes" variant="bootstrap-4" />
            </div>
        </div>
    </section>
@endsection
