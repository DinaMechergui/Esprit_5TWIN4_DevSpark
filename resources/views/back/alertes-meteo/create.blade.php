@extends('back.layouts.back')

@section('title', 'Créer une alerte météo')

@section('content')
    <x-page-header title="Nouvelle alerte météo" subtitle="Formulaire d'enregistrement d'une alerte météo">
        @slot('actions')
            <a href="{{ route('admin.alertes-meteo.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour à la liste
            </a>
        @endslot
    </x-page-header>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.alertes-meteo.store') }}">
                @include('back.alertes-meteo._form', ['isEdit' => false])
            </form>
        </div>
    </div>
@endsection
