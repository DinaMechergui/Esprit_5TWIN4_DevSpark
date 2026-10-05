@extends('back.layouts.back')

@section('title', 'Modifier un point de fraîcheur')

@section('content')
    <x-page-header :title="'Modifier : ' . $pointFraicheur->nom" subtitle="Modifier les informations du point">
        @slot('actions')
            <a href="{{ route('admin.points-fraicheur.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour à la liste
            </a>
        @endslot
    </x-page-header>

    <div class="card">
        <div class="card-body">
            @include('back.points-fraicheur._form', [
                'pointFraicheur' => $pointFraicheur,
                'types' => $types,
                'action' => route('admin.points-fraicheur.update', $pointFraicheur),
                'method' => 'PUT',
            ])
        </div>
    </div>
@endsection
