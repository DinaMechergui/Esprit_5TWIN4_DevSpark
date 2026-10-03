@extends('back.layouts.back')

@section('title', 'Nouveau point de fraîcheur')

@section('content')
    <x-page-header title="Nouveau point de fraîcheur" subtitle="Ajouter un lieu de rafraîchissement">
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
                'action' => route('admin.points-fraicheur.store'),
                'method' => 'POST',
            ])
        </div>
    </div>
@endsection
