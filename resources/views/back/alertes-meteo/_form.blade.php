{{-- Shared Form Partial for AlerteMeteo --}}
@csrf

{{-- Assistant Générateur d'Alerte par IA --}}
<div class="card border border-primary border-opacity-25 shadow-none mb-4" style="background: linear-gradient(135deg, rgba(105, 108, 255, 0.05) 0%, rgba(255, 255, 255, 0.9) 100%);">
    <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary p-2 rounded-circle">
                    <i class="bx bx-bot fs-5 text-white"></i>
                </span>
                <div>
                    <h6 class="mb-0 fw-bold text-primary">Assistant IA : Rédacteur Intelligent de Bulletins Météo</h6>
                    <small class="text-muted">Générez automatiquement le titre, les consignes médicales et la gravité à partir de mots-clés.</small>
                </div>
            </div>
            <span class="badge bg-label-primary px-2 py-1">
                <i class="bx bx-chip me-1"></i> Météo Tunisie IA
            </span>
        </div>

        <div class="row g-2 align-items-center mt-1">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-sparkles text-warning"></i>
                    </span>
                    <input
                        type="text"
                        id="ai-prompt-input"
                        class="form-control border-start-0"
                        placeholder="Ex: Kairouan, 46°C, Sirocco violent, déshydratation"
                    >
                </div>
            </div>
            <div class="col-md-3">
                <button
                    type="button"
                    id="btn-generate-ai"
                    class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-1 shadow-sm"
                >
                    <i class="bx bx-wand"></i>
                    <span id="btn-ai-text">🪄 Rédiger par IA</span>
                </button>
            </div>
        </div>

        {{-- Exemples rapides cliquables --}}
        <div class="d-flex flex-wrap align-items-center gap-1 mt-2">
            <small class="text-muted me-1"><i class="bx bx-bulb"></i> Exemples rapides :</small>
            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill ai-quick-example" data-prompt="Kairouan, 46°C, Sirocco violent, risque coup de chaleur">
                Kairouan 46°C (Sirocco)
            </button>
            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill ai-quick-example" data-prompt="Tozeur, 48°C, Canicule extrême historique, urgence médicale">
                Tozeur 48°C (Canicule extrême)
            </button>
            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill ai-quick-example" data-prompt="Tabarka, 27°C, Brise marine agréable, temps calme">
                Tabarka 27°C (Normal)
            </button>
            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill ai-quick-example" data-prompt="Jendouba, 42°C, Chaleur sèche et risque de feux de forêts">
                Jendouba 42°C (Feux de forêts)
            </button>
        </div>

        {{-- Zone de notification de retour IA --}}
        <div id="ai-status-message" class="mt-2 small" style="display:none;"></div>
    </div>
</div>

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
            rows="6"
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
        const inputTitre = document.getElementById('titre');
        const inputSource = document.getElementById('source');
        const inputMessage = document.getElementById('message');
        const inputPrompt = document.getElementById('ai-prompt-input');
        const btnAi = document.getElementById('btn-generate-ai');
        const btnAiText = document.getElementById('btn-ai-text');
        const statusMsg = document.getElementById('ai-status-message');

        function updateBadge() {
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption || !selectedOption.value) {
                badge.className = 'badge bg-secondary';
                badge.textContent = 'Sélectionnez un niveau';
                badge.removeAttribute('style');
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

        // Clic sur un exemple rapide
        document.querySelectorAll('.ai-quick-example').forEach(button => {
            button.addEventListener('click', function () {
                inputPrompt.value = this.getAttribute('data-prompt');
                triggerAiGeneration();
            });
        });

        // Clic sur le bouton de génération IA
        btnAi.addEventListener('click', triggerAiGeneration);

        function triggerAiGeneration() {
            const prompt = inputPrompt.value.trim();
            if (!prompt) {
                statusMsg.style.display = 'block';
                statusMsg.className = 'mt-2 alert alert-warning py-2 px-3 small';
                statusMsg.innerHTML = '<i class="bx bx-error me-1"></i> Veuillez entrer quelques mots-clés d\'abord (ex: <em>Kairouan, 46°C, Sirocco</em>).';
                inputPrompt.focus();
                return;
            }

            // État de chargement
            btnAi.disabled = true;
            btnAiText.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Génération IA...';
            statusMsg.style.display = 'block';
            statusMsg.className = 'mt-2 alert alert-info py-2 px-3 small';
            statusMsg.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Rédaction du bulletin officiel et calcul du niveau de risque en cours...';

            fetch('{{ route('admin.alertes-meteo.ai-generate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la génération IA.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Pré-remplir les champs avec animation subtile
                    inputTitre.value = data.titre;
                    inputSource.value = data.source;
                    inputMessage.value = data.message;

                    // Mettre à jour le niveau d'alerte
                    if (data.niveau_id) {
                        select.value = data.niveau_id;
                        updateBadge();
                    }

                    // Message de confirmation
                    statusMsg.className = 'mt-2 alert alert-success py-2 px-3 small d-flex align-items-center justify-content-between';
                    statusMsg.innerHTML = `
                        <span>
                            <i class="bx bx-check-circle me-1"></i>
                            <strong>Bulletin généré avec succès !</strong>
                            Niveau recommandé : <span class="badge bg-dark">${data.couleur.toUpperCase()}</span>
                        </span>
                        <span class="badge bg-white text-dark border small">${data.provider}</span>
                    `;

                    // Focus sur le titre
                    inputTitre.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    throw new Error(data.message || 'Impossible de générer le bulletin.');
                }
            })
            .catch(error => {
                statusMsg.className = 'mt-2 alert alert-danger py-2 px-3 small';
                statusMsg.innerHTML = '<i class="bx bx-x-circle me-1"></i> Erreur : ' + error.message;
            })
            .finally(() => {
                btnAi.disabled = false;
                btnAiText.innerHTML = '🪄 Rédiger par IA';
            });
        }
    });
</script>
@endpush
