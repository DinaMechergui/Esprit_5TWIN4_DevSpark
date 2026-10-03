{{--
    Alerte générique (front et back)
    Usage : <x-alert type="success" message="Enregistré !" />
--}}
@props(['type' => 'success', 'message' => null])

@php($typeClass = $type === 'danger' ? 'danger' : ($type === 'warning' ? 'warning' : ($type === 'info' ? 'info' : 'success')))

@if ($message)
    <div {{ $attributes->merge(['class' => 'alert alert-'.$typeClass]) }} role="alert">
        {{ $message }}
    </div>
@endif
