@props(['status' => 'info'])

@php
if(session('status') === 'info'){ $bgColor = 'bg-indigo-500'; }
if(session('status') === 'error'){ $bgColor = 'bg-red-300'; }
if(session('status') === 'alert'){ $bgColor = 'bg-red-300'; }
@endphp

@if(session('message'))
    <div class="{{ $bgColor }} w-1/2 mx-auto p-2 my-4 text-white">
        {{ session('message') }}
    </div>
@endif
