{{-- Shared Form Partial for AlerteMeteo --}}
@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="niveau_alerte_id" class="form-label">Niveau d'alerte <span class="text-danger">*</span></label>
        <select
            class="form-select @error('niveau_alerte_id') is-invalid @enderror"
            id="niveau_alerte_id"
            name="niveau_alerte_id"
            required
        >
            <option value="">-- Sélectionner un niveau d'alerte --</option>
            @foreach ($niveauxAlerte as $niveau)
                <option
                    value="{{ $niveau->id }}"
                    data-couleur="{{ $niveau->couleur }}"
                    {{ old('niveau_alerte_id', $alerteMeteo->niveau_alerte_id ?? '') == $niveau->id ? 'selected' : '' }}
                >
                    [Niveau {{ $niveau->niveau }} - {{ strtoupper($niveau->couleur) }}] {{ $niveau->libelle }}
                </option>
            @endforeach
        </select>
        @error('niveau_alerte_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        {{-- Badge dynamique d'indicateur de couleur --}}
        <div class="mt-2 d-flex align-items-center gap-2">
            <small class="text-muted">Indicateur de couleur :</small>
            <span id="badge-couleur-indicator" class="badge bg-secondary">Sélectionnez un niveau</span>
        </div>
    </div>

    <div class="col-md-6">
        <label for="titre" class="form-label">Titre de l'alerte <span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control @error('titre') is-invalid @enderror"
            id="titre"
            name="titre"
            value="{{ old('titre', $alerteMeteo->titre ?? '') }}"
            placeholder="Ex: Canicule extrême et vent de sirocco - Kairouan"
            required
        >
        @error('titre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="date_debut" class="form-label">Date et heure de début <span class="text-danger">*</span></label>
        <input
            type="datetime-local"
            class="form-control @error('date_debut') is-invalid @enderror"
            id="date_debut"
            name="date_debut"
            value="{{ old('date_debut', isset($alerteMeteo->date_debut) ? $alerteMeteo->date_debut->format('Y-m-d\TH:i') : '') }}"
            required
        >
        @error('date_debut')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="date_fin" class="form-label">Date et heure de fin (optionnelle)</label>
        <input
            type="datetime-local"
            class="form-control @error('date_fin') is-invalid @enderror"
            id="date_fin"
            name="date_fin"
            value="{{ old('date_fin', isset($alerteMeteo->date_fin) ? $alerteMeteo->date_fin->format('Y-m-d\TH:i') : '') }}"
        >
        @error('date_fin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="source" class="form-label">Source d'information</label>
        <input
            type="text"
            class="form-control @error('source') is-invalid @enderror"
            id="source"
            name="source"
            value="{{ old('source', $alerteMeteo->source ?? '') }}"
            placeholder="Ex: Institut National de la Météorologie (INM), Protection Civile..."
        >
        @error('source')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <label for="message" class="form-label">Message / Description de l'alerte <span class="text-danger">*</span></label>
        <textarea
            class="form-control @error('message') is-invalid @enderror"
            id="message"
            name="message"
            rows="5"
            placeholder="Détails des mesures à prendre, préconisations de sécurité, etc."
            required
        >{{ old('message', $alerteMeteo->message ?? '') }}</textarea>
        @error('message')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> {{ isset($isEdit) && $isEdit ? 'Mettre à jour' : 'Enregistrer' }}
    </button>
    <a href="{{ route('admin.alertes-meteo.index') }}" class="btn btn-outline-secondary">
        Annuler
    </a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('niveau_alerte_id');
        const badge = document.getElementById('badge-couleur-indicator');

        function updateBadge() {
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                badge.className = 'badge bg-secondary';
                badge.textContent = 'Sélectionnez un niveau';
                return;
            }

            const couleur = selectedOption.getAttribute('data-couleur');
            let bgClass = 'bg-secondary';
            let style = '';

            switch(couleur) {
                case 'vert':
                    bgClass = 'badge bg-success';
                    break;
                case 'jaune':
                    bgClass = 'badge bg-warning text-dark';
                    break;
                case 'orange':
                    bgClass = 'badge text-white';
                    style = 'background-color: #fd7e14 !important;';
                    break;
                case 'rouge':
                    bgClass = 'badge bg-danger';
                    break;
            }

            badge.className = bgClass;
            badge.setAttribute('style', style);
            badge.textContent = couleur.toUpperCase();
        }

        select.addEventListener('change', updateBadge);
        updateBadge();
    });
</script>
@endpush
