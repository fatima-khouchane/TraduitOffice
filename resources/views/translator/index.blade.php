@extends('layouts.app')

@section('title', __('messages.translators_list'))

@section('content')
<div class="container py-5">
    <h2 class="mb-5 fw-bold text-center text-primary">
        <i class="bi bi-people-fill"></i> {{ __('messages.translators_list') }}
    </h2>

    @if (session('success'))
        <div class="alert alert-success shadow-sm rounded-3">
            {{ session('success') }}
        </div>
    @endif

<div class="timeline-modern @if(app()->getLocale() === 'ar') rtl @endif">
        @forelse ($translators as $index => $translator)
            <div class="timeline-item @if($index % 2 == 0) left fade-in-left @else right fade-in-right @endif">
                <div class="timeline-avatar">
                    <i class="bi bi-person-circle avatar-icon"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-top">
                        <h5>{{ $translator->name }}</h5>

                    </div>
                    <p><i class="bi bi-envelope-fill text-primary me-2"></i>{{ $translator->email }}</p>
                    <p><i class="bi bi-telephone-fill text-success me-2"></i>{{ $translator->phone ?? '-' }}</p>
                    <p><i class="bi bi-calendar-event-fill text-warning me-2"></i>{{ $translator->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center shadow-sm rounded-3 mt-3">
                <i class="bi bi-info-circle"></i> {{ __('messages.no_translators_found') }}
            </div>
        @endforelse
    </div>
</div>

<style>
/* ===== Timeline container ===== */
.timeline-modern {
    position: relative;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 0;
}
.timeline-modern::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 4px;
    background: linear-gradient(to bottom, #6a11cb, #2575fc);
    transform: translateX(-50%);
    border-radius: 2px;
}

/* ===== Timeline items ===== */
.timeline-item {
    position: relative;
    width: 50%;
    padding: 1rem 2rem;
    box-sizing: border-box;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease;
}
.timeline-item:hover .timeline-content {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

/* Left / Right */
.timeline-item.left { left: 0; text-align: right; padding-right: 4rem; }
.timeline-item.right { left: 50%; text-align: left; padding-left: 4rem; }

/* ===== Avatar / Dot ===== */
.timeline-avatar {
    position: absolute;
    top: 15px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #6a11cb;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    color: #fff;
    font-size: 1.3rem;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.timeline-item.left .timeline-avatar { right: -15px; left: auto; }
.timeline-item.right .timeline-avatar { left: -15px; right: auto; }

/* ===== Card content ===== */
.timeline-content {
    background: #fff;
    padding: 1.2rem 1.8rem;
    border-radius: 15px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    position: relative;
    transition: transform 0.3s, box-shadow 0.3s;

    /* ✅ Fix overflow text */
    word-wrap: break-word;        /* coupe les mots trop longs */
    overflow-wrap: break-word;    /* version moderne */
    white-space: normal;          /* permet le retour à la ligne */
    max-width: 100%;              /* empêche le débordement horizontal */
}

.timeline-content h5 { color: #2575fc; font-weight: 600; margin-bottom: 0.5rem; }
.timeline-content p { margin: 0.3rem 0; font-size: 0.9rem; color: #555; }
.badge { font-size: 0.75rem; padding: 0.35rem 0.6rem; border-radius: 0.75rem; }

/* ===== Badge animation ===== */
.animated-badge {
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}

/* ===== Fade-in animation ===== */
.fade-in-left { transform: translateX(-30px); opacity: 0; }
.fade-in-right { transform: translateX(30px); opacity: 0; }

/* Trigger fade-in on scroll */
.timeline-item.show { transform: translateX(0) translateY(0); opacity: 1; }

/* ===== Responsive ===== */
@media (max-width: 768px) {
    .timeline-item, .timeline-item.left, .timeline-item.right {
        width: 100%; left: 0 !important; text-align: left !important; padding: 0 2rem; margin-bottom: 2rem;
    }
    .timeline-avatar { left: -10px !important; top: 10px; }
}
.timeline-modern.rtl .timeline-item.left {
  right: 0;
  left: auto;
  text-align: right;
  padding-right: 8rem;
  padding-left: 0;
}
.timeline-modern.rtl .timeline-item.right {
  right: 50%;
  left: auto;
  text-align: left;
  padding-left: 4rem;
  padding-right: 0;
}


/* RTL avatars */
.timeline-modern.rtl .timeline-item.left .timeline-avatar {
    left: -15px;   /* khlli avatar 3la left */
    right: auto;
}
.timeline-modern.rtl .timeline-item.right .timeline-avatar {
    right: -15px;  /* khlli avatar 3la right */
    left: auto;
}

/* Responsive avatars */



/* RTL cards */
.timeline-modern.rtl .timeline-item.left .timeline-content {
    margin-left: 40px;  /* space bin avatar w card */
    margin-right: 0;
}
.timeline-modern.rtl .timeline-item.right .timeline-content {
    margin-right: 40px;
    margin-left: 0;
}

@media (max-width: 768px) {
    .timeline-modern::before {
        left: 20px; /* déplace la ligne sur la gauche */
        right: auto;
    }

    .timeline-item,
    .timeline-item.left,
    .timeline-item.right {
        width: 100%;
        left: 0 !important;
        right: 0 !important;
        text-align: left !important;
        padding: 0 1rem 2rem 3rem; /* espace 3rem pour la ligne à gauche */
        margin-bottom: 2rem;
    }

    /* Avatar (dot) */
    .timeline-avatar {
        left: 0 !important;
        right: auto !important;
        top: 0.5rem;
    }

    /* Contenu (card) */
    .timeline-content {
        margin-left: 2.5rem;  /* card décalée à droite de la ligne */
        margin-right: 0 !important;
    }

    /* RTL mode (arabe) */
    .timeline-modern.rtl::before {
        right: 20px;
        left: auto;
    }
    .timeline-modern.rtl .timeline-item,
    .timeline-modern.rtl .timeline-item.left,
    .timeline-modern.rtl .timeline-item.right {
        text-align: right !important;
        padding: 0 3rem 2rem 1rem; /* espace 3rem à droite */
    }
    .timeline-modern.rtl .timeline-avatar {
        right: 0 !important;
        left: auto !important;
    }
    .timeline-modern.rtl .timeline-content {
        margin-right: 2.5rem;
        margin-left: 0 !important;
    }

}

</style>

<script>
// ===== Fade-in on scroll =====
document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.timeline-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting){
                entry.target.classList.add('show');
            }
        });
    }, { threshold: 0.2 });
    items.forEach(item => observer.observe(item));
});
</script>
@endsection
