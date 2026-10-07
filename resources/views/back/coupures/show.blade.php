@extends('back.layouts.back')
@section('title', 'Détail de la coupure')
@section('content')
    <x-page-header :title="'Coupure #' . $coupure->id" subtitle="Détail de la coupure">
        @slot('actions')<a href="{{ route('admin.coupures.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Retour</a><a href="{{ route('admin.coupures.edit', $coupure) }}" class="btn btn-primary"><i class="bx bx-edit me-1"></i> Modifier</a>@endslot
    </x-page-header>
    <div class="card"><div class="card-header"><h5 class="mb-0">Informations</h5></div><div class="card-body">
        <div class="row mb-3"><div class="col-sm-3 text-muted">Quartier</div><div class="col-sm-9"><a href="{{ route('admin.quartiers.show', $coupure->quartier) }}">{{ $coupure->quartier?->nom ?? '—' }}</a></div></div>
        <div class="row mb-3"><div class="col-sm-3 text-muted">Type</div><div class="col-sm-9">{{ ucfirst($coupure->type) }}</div></div>
        <div class="row mb-3"><div class="col-sm-3 text-muted">Statut</div><div class="col-sm-9"><span class="badge {{ ['en_cours' => 'bg-danger', 'prevue' => 'bg-warning text-dark', 'terminee' => 'bg-success'][$coupure->statut] }}">{{ str_replace('_', ' ', ucfirst($coupure->statut)) }}</span></div></div>
        <div class="row mb-3"><div class="col-sm-3 text-muted">Début</div><div class="col-sm-9">{{ $coupure->date_debut->format('d/m/Y H:i') }}</div></div>
        <div class="row mb-3"><div class="col-sm-3 text-muted">Fin</div><div class="col-sm-9">{{ $coupure->date_fin?->format('d/m/Y H:i') ?? 'Non renseignée' }}</div></div>
        <div class="row"><div class="col-sm-3 text-muted">Description</div><div class="col-sm-9">{{ $coupure->description ?? '—' }}</div></div>
    </div></div>
@endsection