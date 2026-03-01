@props(['active'])

@php
$classes = ($active ?? false)
            ? 'app-rnav-link app-rnav-link-active block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium focus:outline-none transition duration-150 ease-in-out'
            : 'app-rnav-link block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
