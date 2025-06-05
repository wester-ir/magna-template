@props([
    'type',
    'closable' => true,
    'message',
])
@php $id = 'alert'. rand(100000, 999999); @endphp

<div {{ $attributes->merge(['class' => 'alert alert-'. $type]) }} id="{{ $id }}">
    <div class="alert-message">{{ $message }}</div>

    @if ($closable)
        <div class="alert-close">
            <button type="button" onclick="document.getElementById('{{ $id }}').remove()">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M16.707 8.707 13.414 12l3.293 3.293-1.414 1.414L12 13.414l-3.293 3.293-1.414-1.414L10.586 12 7.293 8.707l1.414-1.414L12 10.586l3.293-3.293 1.414 1.414ZM24 12c0 6.617-5.383 12-12 12S0 18.617 0 12 5.383 0 12 0s12 5.383 12 12Zm-2 0c0-5.514-4.486-10-10-10S2 6.486 2 12s4.486 10 10 10 10-4.486 10-10Z" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
            </button>
        </div>
    @endif
</div>
