@extends('front.layouts.front')

@section('title', 'Carte des points de fraîcheur (plein écran)')
@section('description', 'Carte interactive plein écran des points de fraîcheur du quartier : parcs, salles climatisées et plages, avec géolocalisation « Près de moi ».')

{{-- Styles de Leaflet (fichiers servis localement, pas de CDN) --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/front/vendor/leaflet/leaflet.css') }}">
@endpush

@section('content')
    <section class="ve-section pf-map-section">
        <div class="container">
            {{-- En-tête de section --}}
            <div class="ve-section-header text-center">
                <span class="ve-section-tag">Carte plein écran</span>
                <h2>Tous les points de fraîcheur <span>sur la carte</span></h2>
                <p>
                    Cliquez sur un marqueur pour voir les détails, filtrez par type
                    ou utilisez « Près de moi » pour trier les points par distance.
                </p>
            </div>

            {{-- Barre d'outils : filtre par type + géolocalisation --}}
            <div class="pf-map-toolbar">
                <div class="pf-map-filter">
                    <label for="pf-type-filter"><i class="fa fa-filter" aria-hidden="true"></i> Type de point</label>
                    <select id="pf-type-filter">
                        <option value="">Tous les types</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">
                                {{ $type->nom }} ({{ $type->points_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" id="pf-locate" class="ve-btn-primary">
                    <i class="fa fa-location-arrow" aria-hidden="true"></i> Près de moi
                </button>

                <a href="{{ route('points-fraicheur.index') }}" class="pf-map-back">
                    <i class="fa fa-list" aria-hidden="true"></i> Voir la liste
                </a>
            </div>

            {{-- Messages (géolocalisation, erreurs) --}}
            <div id="pf-map-message" class="pf-map-message" role="status" hidden></div>

            @if ($points->isEmpty())
                {{-- Aucun point en base --}}
                <div class="pf-map-empty">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <p>Aucun point de fraîcheur disponible</p>
                    <a href="{{ route('front.home') }}" class="ve-btn-ghost">Retour à l'accueil</a>
                </div>
            @else
                <div class="pf-map-layout">
                    {{-- Carte --}}
                    <div id="pf-map" class="pf-map-canvas" aria-label="Carte des points de fraîcheur"></div>

                    {{-- Liste latérale des points --}}
                    <aside class="pf-map-side" aria-label="Liste des points affichés">
                        <h4 class="pf-map-side-title">
                            Points affichés <span class="pf-map-count" id="pf-map-count">{{ $points->count() }}</span>
                        </h4>
                        <ul id="pf-map-list" class="pf-map-list"></ul>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/front/vendor/leaflet/leaflet.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var carteElement = document.getElementById('pf-map');
            var points = @json($points);

            // Sans carte (aucun point) ou sans Leaflet, on n'initialise rien.
            if (!carteElement || typeof L === 'undefined' || !points.length) {
                return;
            }

            // Icônes des marqueurs servies localement (éviter tout 404).
            L.Icon.Default.prototype.options.imagePath =
                @json(asset('assets/front/vendor/leaflet/images')) + '/';

            // Centre par défaut : Tunis, zoom 11.
            var map = L.map('pf-map').setView([36.8065, 10.1815], 11);

            // Seules les tuiles viennent d'Internet (OpenStreetMap).
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var filtreType = document.getElementById('pf-type-filter');
            var boutonLocaliser = document.getElementById('pf-locate');
            var zoneMessage = document.getElementById('pf-map-message');
            var listeElement = document.getElementById('pf-map-list');
            var compteurElement = document.getElementById('pf-map-count');
            var urlProches = @json(route('points-fraicheur.proches'));

            var distances = null;            // id => km (après « Près de moi »)
            var marqueurUtilisateur = null;
            var entrees = [];                // { point, marqueur }

            /**
             * Construit le contenu d'une fenêtre de marqueur avec du DOM
             * sécurisé : le texte passe par textContent (aucun HTML non
             * échappé, donc aucun risque d'injection XSS).
             */
            function construirePopup(point) {
                var conteneur = document.createElement('div');
                conteneur.className = 'pf-popup';

                var titre = document.createElement('strong');
                titre.className = 'pf-popup-titre';
                titre.textContent = point.nom;
                conteneur.appendChild(titre);

                if (point.type) {
                    var type = document.createElement('span');
                    type.className = 'pf-popup-type';
                    type.textContent = point.type;
                    conteneur.appendChild(type);
                }

                if (point.adresse) {
                    var adresse = document.createElement('span');
                    adresse.className = 'pf-popup-meta';
                    adresse.textContent = point.adresse;
                    conteneur.appendChild(adresse);
                }

                if (point.horaires) {
                    var horaires = document.createElement('span');
                    horaires.className = 'pf-popup-meta';
                    horaires.textContent = 'Horaires : ' + point.horaires;
                    conteneur.appendChild(horaires);
                }

                if (point.accessible) {
                    var badge = document.createElement('span');
                    badge.className = 'pf-popup-badge';
                    badge.textContent = 'Accessible';
                    conteneur.appendChild(badge);
                }

                var liens = document.createElement('div');
                liens.className = 'pf-popup-liens';

                var lienDetails = document.createElement('a');
                lienDetails.href = point.url;
                lienDetails.textContent = 'Détails';
                liens.appendChild(lienDetails);

                var lienItineraire = document.createElement('a');
                lienItineraire.href = 'https://www.openstreetmap.org/directions'
                    + '?engine=fossgis_osrm_foot&route=;' + point.lat + ',' + point.lng;
                lienItineraire.target = '_blank';
                lienItineraire.rel = 'noopener';
                lienItineraire.textContent = 'Itinéraire';
                liens.appendChild(lienItineraire);

                conteneur.appendChild(liens);

                return conteneur;
            }

            /** Affiche un message dans la page (jamais d'alert()). */
            function afficherMessage(texte, type) {
                zoneMessage.textContent = texte;
                zoneMessage.className = 'pf-map-message' + (type ? ' is-' + type : '');
                zoneMessage.hidden = !texte;
            }

            /** Points visibles selon le filtre par type. */
            function pointsVisibles() {
                var valeur = filtreType.value;

                return entrees.filter(function (entree) {
                    return !valeur || String(entree.point.type_id) === valeur;
                });
            }

            /** Centre la carte sur un point et ouvre sa fenêtre. */
            function centrerSurPoint(point) {
                map.setView([point.lat, point.lng], 16);

                entrees.forEach(function (entree) {
                    if (entree.point.id === point.id) {
                        entree.marqueur.openPopup();
                    }
                });
            }

            /** Réaffiche la liste latérale (filtrée et triée par distance). */
            function afficherListe() {
                var visibles = pointsVisibles().map(function (entree) {
                    return entree.point;
                });

                // Tri du plus proche au plus éloigné dès que les distances sont connues.
                if (distances) {
                    visibles.sort(function (a, b) {
                        return distances[a.id] - distances[b.id];
                    });
                }

                listeElement.innerHTML = '';

                visibles.forEach(function (point) {
                    var item = document.createElement('li');
                    item.className = 'pf-map-item';

                    var bouton = document.createElement('button');
                    bouton.type = 'button';
                    bouton.className = 'pf-map-item-btn';

                    var nom = document.createElement('strong');
                    nom.textContent = point.nom;
                    bouton.appendChild(nom);

                    var meta = document.createElement('span');
                    meta.className = 'pf-map-item-meta';

                    var type = document.createElement('span');
                    type.className = 'pf-map-item-type';
                    type.textContent = point.type || '';
                    meta.appendChild(type);

                    if (distances && typeof distances[point.id] === 'number') {
                        var distance = document.createElement('span');
                        distance.className = 'pf-map-distance';
                        distance.textContent = distances[point.id].toFixed(1) + ' km';
                        meta.appendChild(distance);
                    } else if (point.horaires) {
                        var horaires = document.createElement('span');
                        horaires.textContent = point.horaires;
                        meta.appendChild(horaires);
                    }

                    bouton.appendChild(meta);
                    bouton.addEventListener('click', function () {
                        centrerSurPoint(point);
                    });

                    item.appendChild(bouton);
                    listeElement.appendChild(item);
                });

                if (compteurElement) {
                    compteurElement.textContent = String(visibles.length);
                }
            }

            /** Filtre les marqueurs et la liste par type, sans rechargement. */
            function appliquerFiltre() {
                var valeur = filtreType.value;

                entrees.forEach(function (entree) {
                    var visible = !valeur || String(entree.point.type_id) === valeur;

                    if (visible && !map.hasLayer(entree.marqueur)) {
                        entree.marqueur.addTo(map);
                    } else if (!visible && map.hasLayer(entree.marqueur)) {
                        map.removeLayer(entree.marqueur);
                    }
                });

                afficherListe();
            }

            // Création d'un marqueur par point.
            points.forEach(function (point) {
                var marqueur = L.marker([point.lat, point.lng]).addTo(map);
                marqueur.bindPopup(construirePopup(point));
                entrees.push({ point: point, marqueur: marqueur });
            });

            filtreType.addEventListener('change', appliquerFiltre);

            // « Près de moi » : géolocalisation + points triés par distance.
            boutonLocaliser.addEventListener('click', function () {
                afficherMessage('');

                if (!navigator.geolocation) {
                    afficherMessage("La géolocalisation n'est pas prise en charge par votre navigateur.", 'error');
                    return;
                }

                boutonLocaliser.disabled = true;
                afficherMessage('Recherche de votre position en cours…', 'info');

                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

                    if (marqueurUtilisateur) {
                        map.removeLayer(marqueurUtilisateur);
                    }

                    marqueurUtilisateur = L.marker([lat, lng]).addTo(map);
                    marqueurUtilisateur.bindPopup('<strong>Vous êtes ici</strong>').openPopup();
                    map.setView([lat, lng], 14);

                    fetch(urlProches + '?lat=' + encodeURIComponent(lat) + '&lng=' + encodeURIComponent(lng))
                        .then(function (reponse) {
                            if (!reponse.ok) {
                                throw new Error('http');
                            }

                            return reponse.json();
                        })
                        .then(function (donnees) {
                            distances = {};

                            (donnees.points || []).forEach(function (point) {
                                distances[point.id] = point.distance;
                            });

                            afficherListe();
                            afficherMessage('Points triés du plus proche au plus éloigné.', 'success');
                            boutonLocaliser.disabled = false;
                        })
                        .catch(function () {
                            boutonLocaliser.disabled = false;
                            afficherMessage('Impossible de charger les points les plus proches.', 'error');
                        });
                }, function (erreur) {
                    boutonLocaliser.disabled = false;

                    if (erreur.code === erreur.PERMISSION_DENIED) {
                        afficherMessage("Permission refusée : autorisez l'accès à votre position pour utiliser « Près de moi ».", 'error');
                    } else if (erreur.code === erreur.POSITION_UNAVAILABLE) {
                        afficherMessage('Position indisponible : impossible de déterminer votre localisation.', 'error');
                    } else if (erreur.code === erreur.TIMEOUT) {
                        afficherMessage('Délai dépassé : la recherche de votre position a trop duré.', 'error');
                    } else {
                        afficherMessage('Votre position est introuvable.', 'error');
                    }
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                });
            });

            // Ouverture ciblée : /points-fraicheur/carte?point={id}
            var parametres = new URLSearchParams(window.location.search);
            var idPoint = parametres.get('point');
            var entreeCiblee = null;

            if (idPoint) {
                entrees.forEach(function (entree) {
                    if (String(entree.point.id) === idPoint) {
                        entreeCiblee = entree;
                    }
                });
            }

            if (entreeCiblee) {
                map.setView([entreeCiblee.point.lat, entreeCiblee.point.lng], 16);
                entreeCiblee.marqueur.openPopup();
            } else if (entrees.length > 0) {
                // Ajustement de la vue sur l'ensemble des marqueurs.
                map.fitBounds(entrees.map(function (entree) {
                    return [entree.point.lat, entree.point.lng];
                }), { padding: [30, 30] });
            }

            afficherListe();
        });
    </script>
@endpush
