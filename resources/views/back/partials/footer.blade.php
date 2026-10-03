{{-- Pied de page du Back Office --}}
<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            &copy; {{ date('Y') }} {{ config('app.name') }} — Back office
        </div>
        <div>
            <a href="{{ route('front.home') }}" class="footer-link me-4" target="_blank">Voir le site</a>
            <a href="{{ route('profile.edit') }}" class="footer-link me-4">Mon profil</a>
        </div>
    </div>
</footer>
<!-- / Footer -->
