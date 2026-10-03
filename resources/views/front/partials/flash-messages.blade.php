{{-- Messages flash du Front Office (succès, erreur et erreurs de validation) --}}
@if (session('success'))
    <div class="ve-flash-container">
        <div class="container">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="ve-flash-container">
        <div class="container">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endif

{{-- Message de statut utilisé par certaines actions (chaîne traduite) --}}
@php($flashStatus = session('status'))
@if (is_string($flashStatus) && $flashStatus !== ''
    && ! in_array($flashStatus, ['profile-updated', 'password-updated', 'verification-link-sent'], true))
    <div class="ve-flash-container">
        <div class="container">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-info-circle"></i> {{ $flashStatus }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endif
