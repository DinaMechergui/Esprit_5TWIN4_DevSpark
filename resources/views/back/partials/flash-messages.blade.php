{{-- Messages flash du Back Office (succès, erreur) --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bx bx-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bx bx-error-circle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

{{-- Erreurs de validation non affichées dans un formulaire --}}
@if ($errors->any() && ! request()->isMethod('POST') && ! request()->isMethod('PUT') && ! request()->isMethod('PATCH'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bx bx-error me-1"></i>
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif
