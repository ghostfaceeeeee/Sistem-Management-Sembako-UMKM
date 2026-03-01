@props(['active'])

@php
$classes = ($active ?? false)
            ? 'app-nav-link app-nav-link-active inline-flex items-center px-2 pt-1 pb-1 border-b-2 text-sm font-semibold leading-5 focus:outline-none transition duration-150 ease-in-out tracking-wide'
            : 'app-nav-link inline-flex items-center px-2 pt-1 pb-1 border-b-2 border-transparent text-sm font-semibold leading-5 focus:outline-none transition duration-150 ease-in-out tracking-wide';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
