{{-- Pied de page du Front Office --}}
<footer class="ve-footer">
    <div class="container">
        <div class="row">
            {{-- Colonne 1 : marque --}}
            <div class="col-12 col-sm-6 col-lg-4 mb-50">
                <div class="ve-footer-brand">
                    <a href="{{ route('front.home') }}" class="ve-footer-logo">
                        <span class="ve-logo-icon"><i class="fa fa-thermometer-full" aria-hidden="true"></i></span>
                        <span class="ve-logo-text">{{ str_replace(' ', '', config('app.name')) }}</span>
                    </a>
                    <p>
                        L'application du quartier pour anticiper les épisodes de canicule,
                        les coupures de courant et trouver un point de fraîcheur près de chez soi.
                    </p>
                    <div class="ve-social">
                        <a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>

            {{-- Colonne 2 : liens rapides --}}
            <div class="col-12 col-sm-6 col-lg-2 mb-50">
                <h5 class="ve-footer-title">Liens rapides</h5>
                <ul class="ve-footer-links">
                    <li><a href="{{ route('front.home') }}">Accueil</a></li>
                    {{-- Module « Points de fraîcheur » : à activer quand les routes seront créées
                    <li><a href="{{ route('points-fraicheur.index') }}">Points de fraîcheur</a></li>
                    --}}
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                    <li><a href="{{ route('register') }}">Inscription</a></li>
                </ul>
            </div>

            {{-- Colonne 3 : services --}}
            <div class="col-12 col-sm-6 col-lg-3 mb-50">
                <h5 class="ve-footer-title">Nos services</h5>
                <ul class="ve-footer-links">
                    <li><a href="{{ route('front.home') }}">Alertes météo</a></li>
                    <li><a href="{{ route('front.home') }}">Coupures de courant</a></li>
                    <li><a href="{{ route('front.home') }}">Points de fraîcheur</a></li>
                    <li><a href="{{ route('front.home') }}">Conseils anti-canicule</a></li>
                </ul>
            </div>

            {{-- Colonne 4 : contact --}}
            <div class="col-12 col-sm-6 col-lg-3 mb-50">
                <h5 class="ve-footer-title">Nous contacter</h5>
                <ul class="ve-footer-contact">
                    <li><i class="fa fa-map-marker"></i> Mairie de quartier, 12 avenue des Tilleuls</li>
                    <li><i class="fa fa-phone"></i> 01 23 45 67 89</li>
                    <li><i class="fa fa-envelope"></i> contact@alerte-canicule.fr</li>
                    <li><i class="fa fa-clock-o"></i> Lun. - Ven., 9h - 18h</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Barre du bas --}}
    <div class="ve-footer-bottom">
        <div class="container">
            <div class="ve-footer-bottom-inner">
                <p>
                    &copy; {{ date('Y') }} {{ config('app.name') }} — Projet universitaire,
                    module « Applications Web Avancées ».
                </p>
                <ul>
                    <li><a href="#">Mentions légales</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
