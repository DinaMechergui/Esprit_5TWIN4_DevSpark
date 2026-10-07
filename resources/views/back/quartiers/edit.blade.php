@extends('back.layouts.back')
@section('title', 'Modifier un quartier')
@section('content')
    <x-page-header :title="'Modifier ' . $quartier->nom" subtitle="Mettre à jour les informations du quartier">
        @slot('actions')<a href="{{ route('admin.quartiers.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Retour à la liste</a>@endslot
    </x-page-header>
    <div class="card"><div class="card-body">@include('back.quartiers._form', ['quartier' => $quartier, 'action' => route('admin.quartiers.update', $quartier), 'method' => 'PUT'])</div></div>
@endsection