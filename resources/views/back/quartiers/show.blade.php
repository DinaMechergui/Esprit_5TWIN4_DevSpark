@extends('back.layouts.back')
@section('title', 'Détail du quartier')
@section('content')
    <x-page-header :title="$quartier->nom" :subtitle="'Quartier #' . $quartier->id">
        @slot('actions')<a href="{{ route('admin.quartiers.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Retour</a><a href="{{ route('admin.quartiers.edit', $quartier) }}" class="btn btn-primary"><i class="bx bx-edit me-1"></i> Modifier</a>@endslot
    </x-page-header>
    <div class="row"><div class="col-lg-5 mb-4"><div class="card"><div class="card-header"><h5 class="mb-0">Informations</h5></div><div class="card-body">
        <div class="row mb-3"><div class="col-sm-4 text-muted">Nom</div><div class="col-sm-8">{{ $quartier->nom }}</div></div>
        <div class="row mb-3"><div class="col-sm-4 text-muted">Ville</div><div class="col-sm-8">{{ $quartier->ville }}</div></div>
        <div class="row"><div class="col-sm-4 text-muted">Code postal</div><div class="col-sm-8">{{ $quartier->code_postal }}</div></div>
    </div></div></div><div class="col-lg-7 mb-4"><div class="card"><div class="card-header"><h5 class="mb-0">Coupures ({{ $coupures->count() }})</h5></div><div class="card-body">
        @forelse ($coupures as $coupure)<p class="mb-2"><a href="{{ route('admin.coupures.show', $coupure) }}">{{ ucfirst($coupure->type) }}</a> · {{ $coupure->date_debut->format('d/m/Y H:i') }}</p>@empty<p class="text-muted mb-0">Aucune coupure rattachée.</p>@endforelse
    </div></div></div></div>
@endsection