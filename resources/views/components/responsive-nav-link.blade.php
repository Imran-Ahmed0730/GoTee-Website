@props(['active'])

@php
$classes = ($active ?? false) ? 'nav-link active' : 'nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' d-block w-100']) }}>
    {{ $slot }}
</a>
