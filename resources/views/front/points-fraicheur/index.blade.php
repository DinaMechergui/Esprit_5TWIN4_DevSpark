@extends('front.layouts.front')

@section('title', 'Points de fraîcheur')
@section('description', 'Parcourez les points de fraîcheur du quartier : parcs, salles climatisées et fontaines ouvertes pendant la canicule.')

@section('content')
    <section class="ve-section pf-section">
        <div class="container">
            {{-- En-tête de section --}}
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Points de fraîcheur</span>
                <h2>Trouvez un lieu de <span>rafraîchissement</span> près de chez vous</h2>
                <p>
                    Parcs, salles climatisées et fontaines ouvertes pour souffler pendant les épisodes de chaleur.
                    {{ $points->total() }} point(s) disponible(s){{ $selectedType ? ' pour ce type' : '' }}.
                </p>
            </div>

            {{-- Filtres : recherche + type --}}
            <form method="GET" action="{{ route('points-fraicheur.index') }}" class="pf-filters">
                <div class="pf-filter-field">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher un nom ou une adresse..."
                    >
                </div>

                <div class="pf-filter-field pf-filter-select">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                    <select name="type">
                        <option value="">Tous les types</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected($selectedType?->id === $type->id)>
                                {{ $type->nom }} ({{ $type->points_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="ve-btn-primary">Filtrer</button>

                @if ($search || request('type'))
                    <a href="{{ route('points-fraicheur.index') }}" class="pf-reset">Réinitialiser</a>
                @endif
            </form>

            {{-- Grille des points --}}
            <div class="pf-grid">
                @forelse ($points as $point)
                    <article class="pf-card wow fadeInUp" data-wow-delay="100ms">
                        <div class="pf-card-icon">
                            <i class="{{ $point->type?->icone ?: 'fa fa-snowflake' }}" aria-hidden="true"></i>
                        </div>

                        <div class="pf-card-body">
                            <span class="pf-badge">{{ $point->type?->nom ?? 'Point de fraîcheur' }}</span>
                            <h4>{{ $point->nom }}</h4>

                            <p class="pf-address">
                                <i class="fa fa-map-marker" aria-hidden="true"></i> {{ $point->adresse }}
                            </p>

                            @if ($point->horaires)
                                <p class="pf-hours">
                                    <i class="fa fa-clock-o" aria-hidden="true"></i> {{ $point->horaires }}
                                </p>
                            @endif

                            <div class="pf-card-footer">
                                <span class="pf-access {{ $point->accessible ? 'is-open' : 'is-limited' }}">
                                    {{ $point->accessible ? 'Accessible' : 'Accès limité' }}
                                </span>
                                <a href="{{ route('points-fraicheur.show', $point) }}" class="pf-link">
                                    Détail <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="pf-empty">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <p>Aucun point de fraîcheur ne correspond à votre recherche.</p>
                        <a href="{{ route('points-fraicheur.index') }}" class="ve-btn-ghost">Voir tous les points</a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <x-pagination :paginator="$points" variant="bootstrap-4" />
        </div>
    </section>
@endsection
