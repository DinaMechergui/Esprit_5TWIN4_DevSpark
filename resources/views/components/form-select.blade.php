{{--
    Liste déroulante générique (front et back)
    Usage :
        <x-form-select name="role" label="Rôle" required>
            <option value="user">Utilisateur</option>
        </x-form-select>
--}}
@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    $label = $label ?? ucfirst($name);
    $selectedValue = old($name, $value);
    $hasError = $errors->has($name);
@endphp

<div {{ $attributes->merge(['class' => 've-form-group app-form-group']) }}>
    <label for="{{ $name }}">
        {{ $label }}
        @if ($required)
            <span class="app-required">*</span>
        @endif
    </label>

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-control @if ($hasError) is-invalid @endif"
        @if ($required) required @endif
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        {{ $slot }}
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
