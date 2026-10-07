@extends('back.layouts.back')

@section('title', 'Modifier l\'alerte météo')

@section('content')
    <x-page-header title="Modifier l'alerte météo" :subtitle="'Modification de : ' . $alerteMeteo->titre">
        @slot('actions')
            <a href="{{ route('admin.alertes-meteo.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Retour à la liste
            </a>
        @endslot
    </x-page-header>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.alertes-meteo.update', $alerteMeteo) }}">
                @method('PUT')
                @include('back.alertes-meteo._form', ['isEdit' => true])
            </form>
        </div>
    </div>
@endsection
