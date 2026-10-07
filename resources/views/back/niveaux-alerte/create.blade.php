@extends('back.layouts.back')

@section('title', 'Créer un niveau d\'alerte')

@section('content')
    <x-page-header title="Nouveau niveau d'alerte" subtitle="Formulaire d'ajout d'un niveau d'alerte météo">
        @slot('actions')
            <a href="{{ route('admin.niveaux-alerte.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour à la liste
            </a>
        @endslot
    </x-page-header>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.niveaux-alerte.store') }}">
                @include('back.niveaux-alerte._form', ['isEdit' => false])
            </form>
        </div>
    </div>
@endsection
