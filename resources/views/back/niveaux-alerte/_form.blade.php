{{-- Shared Form Partial for NiveauAlerte --}}
@csrf

<div class="row g-3">
    <div class="col-md-6">
        <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control @error('libelle') is-invalid @enderror"
            id="libelle"
            name="libelle"
            value="{{ old('libelle', $niveauAlerte->libelle ?? '') }}"
            placeholder="Ex: Alerte Canicule Forte"
            required
        >
        @error('libelle')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="couleur" class="form-label">Couleur <span class="text-danger">*</span></label>
        <select
            class="form-select @error('couleur') is-invalid @enderror"
            id="couleur"
            name="couleur"
            required
        >
            <option value="">-- Sélectionner une couleur --</option>
            <option value="vert" {{ old('couleur', $niveauAlerte->couleur ?? '') === 'vert' ? 'selected' : '' }}>Vert</option>
            <option value="jaune" {{ old('couleur', $niveauAlerte->couleur ?? '') === 'jaune' ? 'selected' : '' }}>Jaune</option>
            <option value="orange" {{ old('couleur', $niveauAlerte->couleur ?? '') === 'orange' ? 'selected' : '' }}>Orange</option>
            <option value="rouge" {{ old('couleur', $niveauAlerte->couleur ?? '') === 'rouge' ? 'selected' : '' }}>Rouge</option>
        </select>
        @error('couleur')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="niveau" class="form-label">Niveau (1-4) <span class="text-danger">*</span></label>
        <input
            type="number"
            min="1"
            max="4"
            class="form-control @error('niveau') is-invalid @enderror"
            id="niveau"
            name="niveau"
            value="{{ old('niveau', $niveauAlerte->niveau ?? 1) }}"
            required
        >
        @error('niveau')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save me-1"></i> {{ isset($isEdit) && $isEdit ? 'Mettre à jour' : 'Enregistrer' }}
    </button>
    <a href="{{ route('admin.niveaux-alerte.index') }}" class="btn btn-outline-secondary">
        Annuler
    </a>
</div>
