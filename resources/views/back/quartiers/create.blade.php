@extends('back.layouts.back')
@section('title', 'Nouveau quartier')
@section('content')
    <x-page-header title="Nouveau quartier" subtitle="Créer un quartier">
        @slot('actions')<a href="{{ route('admin.quartiers.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Retour à la liste</a>@endslot
    </x-page-header>
    <div class="card"><div class="card-body">@include('back.quartiers._form', ['quartier' => $quartier, 'action' => route('admin.quartiers.store'), 'method' => 'POST'])</div></div>
@endsection