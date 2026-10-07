@extends('back.layouts.back')

@section('title', 'Modifier le niveau d\'alerte')

@section('content')
    <x-page-header title="Modifier le niveau d'alerte" :subtitle="'Modification de : ' . $niveauAlerte->libelle">
        @slot('actions')
            <a href="{{ route('admin.niveaux-alerte.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour à la liste
            </a>
        @endslot
    </x-page-header>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.niveaux-alerte.update', $niveauAlerte) }}">
                @method('PUT')
                @include('back.niveaux-alerte._form', ['isEdit' => true])
            </form>
        </div>
    </div>
@endsection
