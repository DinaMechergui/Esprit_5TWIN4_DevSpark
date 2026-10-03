{{--
    Champ de formulaire générique (front et back)
    Usage : <x-form-input name="email" type="email" label="Adresse e-mail" required />
--}}
@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'autocomplete' => null,
])

@php
    $label = $label ?? ucfirst($name);
    $inputValue = old($name, $value);
    $hasError = $errors->has($name);
@endphp

<div {{ $attributes->merge(['class' => 've-form-group app-form-group']) }}>
    <label for="{{ $name }}">
        {{ $label }}
        @if ($required)
            <span class="app-required">*</span>
        @endif
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $inputValue }}"
        placeholder="{{ $placeholder }}"
        class="form-control @if ($hasError) is-invalid @endif"
        @if ($required) required @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    >

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
