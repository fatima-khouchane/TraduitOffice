@extends('layouts.client')

@section('title', __('messages.title'))

@section('content')
<div class="container py-5">

    <h2 class="mb-4 text-center fw-bold">
        <i class="bi bi-chat-square-dots text-primary"></i> {{ __('messages.list') }}
    </h2>

    @forelse ($demandes as $demande)
        <div class="card shadow-lg border-0 rounded-4 mb-5">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary m-0">
                    <i class="bi bi-folder2-open"></i>
                    {{ __('messages.request_number', ['id' => $demande->id]) }}
                </h5>
                <span class="badge bg-gradient px-3 py-2">
                    {{ $demande->messages->count() }} {{ __('messages.message_count') }}
                </span>
            </div>

            <div class="card-body bg-light">
                @foreach ($demande->messages as $message)
                    <div class="d-flex mb-4 {{ $loop->index % 2 == 0 ? 'justify-content-start' : 'justify-content-end' }}">
                        <div class="bubble {{ !$message->is_read ? 'unread' : '' }}">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-clock"></i> {{ $message->created_at->format('d/m/Y H:i') }}
                            </small>
                            <p class="mb-0">{{ $message->contenu }}</p>
                            @if(!$message->is_read)
                                <span class="badge bg-danger mt-2">{{ __('messages.unread') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">{{ __('messages.no_messages') }}</p>
        </div>
    @endforelse
</div>

<!-- Styles custom -->
<style>
.bg-gradient {
    background: linear-gradient(45deg, #0d6efd, #6f42c1);
    color: #fff;
    border-radius: 20px;
}
.bubble {
    max-width: 70%;
    padding: 1rem 1.2rem;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    position: relative;
}
.bubble.unread {
    border: 2px solid #dc3545;
}
.bubble::after {
    content: '';
    position: absolute;
    bottom: -8px;
    width: 16px;
    height: 16px;
    background: inherit;
    transform: rotate(45deg);
}
.justify-content-start .bubble::after {
    left: 20px;
    border-radius: 0 0 0 4px;
}
.justify-content-end .bubble {
    background: #a3c8ff;
    color: #000000;
}
.justify-content-end .bubble::after {
    right: 20px;
    background: #a3c8ff;
    border-radius: 0 0 4px 0;
}
</style>
@endsection
