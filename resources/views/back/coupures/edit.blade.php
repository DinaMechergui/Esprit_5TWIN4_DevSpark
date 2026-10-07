@extends('back.layouts.back')
@section('title', 'Modifier une coupure')
@section('content')
    <x-page-header :title="'Modifier la coupure #' . $coupure->id" subtitle="Mettre à jour la coupure">
        @slot('actions')<a href="{{ route('admin.coupures.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i> Retour à la liste</a>@endslot
    </x-page-header>
    <div class="card"><div class="card-body">@include('back.coupures._form', ['coupure' => $coupure, 'quartiers' => $quartiers, 'action' => route('admin.coupures.update', $coupure), 'method' => 'PUT'])</div></div>
@endsection