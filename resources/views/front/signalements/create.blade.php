@extends('front.layouts.front')

@section('title', 'Soumettre un signalement')
@section('description', 'Signalez un incident, une coupure ou tout problème lié à la canicule dans votre quartier.')

@section('content')
    <section class="ve-section pf-section">
        <div class="container">
            {{-- Fil d'Ariane --}}
            <nav class="pf-breadcrumb" aria-label="Fil d'Ariane">
                <a href="{{ route('front.home') }}">Accueil</a>
                <span>/</span>
                <a href="{{ route('signalements.index') }}">Signalements</a>
                <span>/</span>
                <strong>Nouveau signalement</strong>
            </nav>

            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Signalements</span>
                <h2>Soumettre un <span>signalement</span></h2>
                <p>Signalez un incident, une coupure ou tout problème lié à la canicule.</p>
            </div>

            <div style="max-width:680px;margin:0 auto;">
                <div class="pf-detail-card wow fadeInUp">
                    <form method="POST" action="{{ route('signalements.store') }}">
                        @csrf

                        {{-- Champ caché requis par la validation (forcé à 'nouveau' par le contrôleur) --}}
                        <input type="hidden" name="statut" value="nouveau">

                        <div class="mb-3">
                            <label for="type_signalement_id" style="display:block;font-weight:600;margin-bottom:6px;">
                                Type de signalement <span style="color:red;">*</span>
                            </label>
                            <select
                                id="type_signalement_id"
                                name="type_signalement_id"
                                style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:6px;font-size:1rem;"
                                required
                            >
                                <option value="">-- Sélectionner un type --</option>
                                @foreach ($typesSignalement as $type)
                                    <option
                                        value="{{ $type->id }}"
                                        @selected(old('type_signalement_id') == $type->id)
                                    >
                                        {{ $type->libelle }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_signalement_id')
                                <p style="color:red;font-size:0.875rem;margin-top:4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" style="display:block;font-weight:600;margin-bottom:6px;">
                                Description <span style="color:red;">*</span>
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                rows="6"
                                maxlength="1000"
                                placeholder="Décrivez l'incident en détail..."
                                style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:6px;font-size:1rem;resize:vertical;"
                                required
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <p style="color:red;font-size:0.875rem;margin-top:4px;">{{ $message }}</p>
                            @enderror
                            <small style="color:#666;">Maximum 1000 caractères.</small>
                        </div>

                        <div class="pf-detail-actions" style="text-align:left;">
                            <button type="submit" class="ve-btn-primary">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer le signalement
                            </button>
                            <a href="{{ route('signalements.index') }}" class="ve-btn-ghost">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
