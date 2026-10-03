{{--
    Carte générique avec titre optionnel (front et back)
    Usage :
        <x-card :title="'Titre'">Contenu</x-card>
--}}
@props(['title' => null])

<div {{ $attributes->merge(['class' => 'card app-card']) }}>
    @if ($title)
        <div class="card-header app-card-header">
            <h5 class="mb-0">{{ $title }}</h5>
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
