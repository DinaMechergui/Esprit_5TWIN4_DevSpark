@extends('back.layouts.back')

@section('title', 'Modifier un type de point')

@section('content')
    <x-page-header :title="'Modifier : ' . $typePoint->nom" subtitle="Modifier les informations du type de point">
        @slot('actions')
            <a href="{{ route('admin.types-point.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour à la liste
            </a>
        @endslot
    </x-page-header>

    <div class="card">
        <div class="card-body">
            @include('back.types-point._form', [
                'typePoint' => $typePoint,
                'action' => route('admin.types-point.update', $typePoint),
                'method' => 'PUT',
            ])
        </div>
    </div>
@endsection
