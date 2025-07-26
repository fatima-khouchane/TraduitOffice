@extends('layouts.client')

@section('title', __('messages.title'))

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">{{ __('messages.list') }}</h4>

    @forelse ($demandes as $demande)
        <h5 class="text-primary">{{ __('messages.request_number', ['id' => $demande->id]) }}</h5>
        <ul class="list-group mb-4">
            @foreach ($demande->messages as $message)
                <li class="list-group-item {{ !$message->is_read ? 'fw-bold' : '' }}">
                    <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small><br>
                    <span>{{ $message->contenu }}</span>
                </li>
            @endforeach
        </ul>
    @empty
        <p class="text-muted fst-italic">{{ __('messages.no_messages') }}</p>
    @endforelse
</div>
@endsection
