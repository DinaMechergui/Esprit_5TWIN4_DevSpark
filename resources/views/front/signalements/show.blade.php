@extends('front.layouts.front')

@section('title', 'Signalement #' . $signalement->id)
@section('description', 'Détail du signalement #' . $signalement->id . ' : ' . \Illuminate\Support\Str::limit($signalement->description, 100))

@section('content')
    <section class="ve-section pf-section pf-detail">
        <div class="container">
            {{-- Fil d'Ariane --}}
            <nav class="pf-breadcrumb" aria-label="Fil d'Ariane">
                <a href="{{ route('front.home') }}">Accueil</a>
                <span>/</span>
                <a href="{{ route('signalements.index') }}">Signalements</a>
                <span>/</span>
                <strong>Signalement #{{ $signalement->id }}</strong>
            </nav>

            <div class="ve-section-header text-center">
                <span class="ve-section-tag">{{ $signalement->typeSignalement?->libelle ?? 'Signalement' }}</span>
                <h2>Signalement <span>#{{ $signalement->id }}</span></h2>
                <p>
                    Émis par <strong>{{ $signalement->user?->name ?? 'Anonyme' }}</strong>
                    le {{ $signalement->created_at->format('d/m/Y à H:i') }}
                </p>
            </div>

            <div class="pf-detail-grid">
                {{-- Informations du signalement --}}
                <div class="pf-detail-card wow fadeInUp">
                    <div class="pf-detail-icon">
                        <i class="fa fa-flag" aria-hidden="true"></i>
                    </div>

                    <h4>Informations</h4>

                    <ul class="pf-detail-list">
                        <li>
                            <i class="fa fa-tag" aria-hidden="true"></i>
                            <div>
                                <strong>Type</strong>
                                <span>{{ $signalement->typeSignalement?->libelle ?? '—' }}</span>
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <div>
                                <strong>Auteur</strong>
                                <span>{{ $signalement->user?->name ?? 'Anonyme' }}</span>
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-circle" aria-hidden="true"></i>
                            <div>
                                <strong>Statut</strong>
                                <span>
                                    @php
                                        $badgeStyle = match($signalement->statut) {
                                            'nouveau'  => 'background:#3498db;color:#fff;',
                                            'en_cours' => 'background:#f39c12;color:#fff;',
                                            'traite'   => 'background:#27ae60;color:#fff;',
                                            default    => 'background:#95a5a6;color:#fff;',
                                        };
                                    @endphp
                                    <span style="padding:2px 10px;border-radius:4px;{{ $badgeStyle }}">
                                        {{ $statuts[$signalement->statut] ?? $signalement->statut }}
                                    </span>
                                </span>
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                            <div>
                                <strong>Priorité</strong>
                                <span>
                                    @php
                                        $prioStyle = match($signalement->priorite) {
                                            'faible'  => 'background:#95a5a6;color:#fff;',
                                            'moyenne' => 'background:#f39c12;color:#fff;',
                                            'urgente' => 'background:#e74c3c;color:#fff;',
                                            default   => 'background:#95a5a6;color:#fff;',
                                        };
                                        $priorites = \App\Models\Signalement::PRIORITES;
                                    @endphp
                                    <span style="padding:2px 10px;border-radius:4px;{{ $prioStyle }}">
                                        {{ strtoupper($priorites[$signalement->priorite] ?? $signalement->priorite) }}
                                    </span>
                                </span>
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <div>
                                <strong>Date</strong>
                                <span>{{ $signalement->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Description --}}
                <div class="pf-detail-card wow fadeInUp" data-wow-delay="200ms">
                    <div class="pf-detail-icon">
                        <i class="fa fa-align-left" aria-hidden="true"></i>
                    </div>

                    <h4>Description</h4>

                    <p style="line-height:1.7;">{{ $signalement->description }}</p>
                </div>
            </div>

            <div class="pf-detail-actions text-center">
                @auth
                    <a href="{{ route('signalements.create') }}" class="ve-btn-primary">
                        <i class="fa fa-plus" aria-hidden="true"></i> Soumettre un signalement
                    </a>
                @endauth
                <a href="{{ route('signalements.index') }}" class="ve-btn-ghost">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Retour à la liste
                </a>
            </div>
        </div>
    </section>
@endsection
