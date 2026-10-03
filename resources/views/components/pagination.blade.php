{{--
    Pagination (Bootstrap 4 pour le front, Bootstrap 5 pour le back)
    Usage : <x-pagination :paginator="$users" variant="bootstrap-5" />
--}}
@props(['paginator', 'variant' => 'bootstrap-4'])

@if ($paginator->hasPages())
    <div {{ $attributes->merge(['class' => 'app-pagination']) }}>
        {{ $paginator->links($variant === 'bootstrap-5' ? 'pagination::bootstrap-5' : 'pagination::bootstrap-4') }}
    </div>
@endif
