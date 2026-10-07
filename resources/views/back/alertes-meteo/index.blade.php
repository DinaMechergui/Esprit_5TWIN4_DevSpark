@extends('back.layouts.back')

@section('title', 'Alertes météo')

@section('content')
    <x-page-header title="Alertes météo" :subtitle="'Gestion des alertes (' . $alertesMeteo->total() . ')'">
        @slot('actions')
            <a href="{{ route('admin.alertes-meteo.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Nouvelle alerte
            </a>
        @endslot
    </x-page-header>

    {{-- Recherche & Filtres --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.alertes-meteo.index') }}" class="d-flex flex-wrap gap-2 align-items-end">
                <div class="app-search-bar">
                    <label class="form-label" for="search">Rechercher</label>
                    <input
                        type="text"
                        class="form-control"
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Titre, message ou source..."
                    >
                </div>
                <div>
                    <label class="form-label" for="niveau_alerte_id">Niveau d'alerte</label>
                    <select name="niveau_alerte_id" id="niveau_alerte_id" class="form-select">
                        <option value="">Tous les niveaux</option>
                        @foreach ($niveauxAlerte as $niveau)
                            <option value="{{ $niveau->id }}" {{ $niveauId == $niveau->id ? 'selected' : '' }}>
                                {{ $niveau->libelle }} ({{ ucfirst($niveau->couleur) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bx bx-search me-1"></i> Filtrer
                </button>
                @if ($search || $niveauId)
                    <a href="{{ route('admin.alertes-meteo.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Tableau des alertes météo --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Niveau / Couleur</th>
                            <th>Titre</th>
                            <th>Date Début</th>
                            <th>Date Fin</th>
                            <th>Source</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alertesMeteo as $alerte)
                            <tr>
                                <td>{{ $alerte->id }}</td>
                                <td>
                                    @if ($alerte->niveauAlerte)
                                        @switch($alerte->niveauAlerte->couleur)
                                            @case('vert')
                                                <span class="badge bg-success">Vert ({{ $alerte->niveauAlerte->libelle }})</span>
                                                @break
                                            @case('jaune')
                                                <span class="badge bg-warning text-dark">Jaune ({{ $alerte->niveauAlerte->libelle }})</span>
                                                @break
                                            @case('orange')
                                                <span class="badge text-white" style="background-color: #fd7e14 !important;">Orange ({{ $alerte->niveauAlerte->libelle }})</span>
                                                @break
                                            @case('rouge')
                                                <span class="badge bg-danger">Rouge ({{ $alerte->niveauAlerte->libelle }})</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $alerte->niveauAlerte->libelle }}</span>
                                        @endswitch
                                    @else
                                        <span class="badge bg-secondary">Non défini</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.alertes-meteo.show', $alerte) }}" class="fw-semibold text-dark">
                                        {{ $alerte->titre }}
                                    </a>
                                </td>
                                <td>{{ $alerte->date_debut->format('d/m/Y H:i') }}</td>
                                <td>{{ $alerte->date_fin ? $alerte->date_fin->format('d/m/Y H:i') : 'Indéfinie' }}</td>
                                <td>{{ $alerte->source ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.alertes-meteo.show', $alerte) }}"
                                           class="btn btn-sm btn-outline-secondary" title="Voir">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('admin.alertes-meteo.edit', $alerte) }}"
                                           class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.alertes-meteo.destroy', $alerte) }}"
                                              onsubmit="return confirm('Confirmer la suppression de cette alerte météo ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Aucune alerte météo ne correspond à votre recherche.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <x-pagination :paginator="$alertesMeteo" variant="bootstrap-5" />
@endsection
