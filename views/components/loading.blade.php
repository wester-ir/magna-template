@props(['status' => true])

<div class="flex items-center justify-center" @class(['hidden' => ! $status])>
    <svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => 'w-5 h-5 fill-neutral-600 animate-spin']) }}>
        <path d="M7.229 1.173a9.25 9.25 0 1011.655 11.412 1.25 1.25 0 10-2.4-.698 6.75 6.75 0 11-8.506-8.329 1.25 1.25 0 10-.75-2.385z"></path>
    </svg>
</div>
