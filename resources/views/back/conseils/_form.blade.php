<form method="POST" action="{{ $action }}">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    {{-- Liste déroulante des catégories (relation belongsTo) --}}
    <x-form-select
        name="categorie_conseil_id"
        label="Catégorie"
        :value="$conseil->categorie_conseil_id"
        placeholder="Sélectionnez une catégorie..."
        required
    >
        @foreach ($categories as $categorie)
            <option value="{{ $categorie->id }}"
                @selected((int) old('categorie_conseil_id', $conseil->categorie_conseil_id) === $categorie->id)>
                {{ $categorie->nom }}
            </option>
        @endforeach
    </x-form-select>

    <x-form-input
        name="titre"
        label="Titre"
        :value="$conseil->titre"
        placeholder="Ex. Fermer les volets en journée"
        required
    />

    {{-- Zone de texte pour le contenu --}}
    <div class="ve-form-group app-form-group">
        <label for="contenu">Contenu <span class="app-required">*</span></label>
        <textarea
            name="contenu"
            id="contenu"
            rows="6"
            class="form-control @error('contenu') is-invalid @enderror"
            required
        >{{ old('contenu', $conseil->contenu) }}</textarea>
        @error('contenu')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="bx bx-save me-1"></i> Enregistrer
        </button>
        <a href="{{ route('admin.conseils.index') }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
