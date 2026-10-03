{{--
    En-tête de page (titre + sous-titre + actions)
    Usage :
        <x-page-header title="Utilisateurs" :subtitle="'Liste des comptes'">
            @slot('actions')
                <a href="..." class="btn btn-primary">Ajouter</a>
            @endslot
        </x-page-header>
--}}
@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'app-page-header d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4']) }}>
    <div>
        <h3 class="app-page-title mb-0">{{ $title }}</h3>
        @if ($subtitle)
            <p class="app-page-subtitle mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="app-page-actions">
            {{ $actions }}
        </div>
    @endisset
</div>
