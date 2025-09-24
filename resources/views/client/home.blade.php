@extends('layouts.client')

@section('title', __('app.client_home'))

@section('content')
<div class="container mt-5" @if(app()->getLocale() == 'ar') dir="rtl" style="text-align: right;" @endif>

   @if(Auth::check())
    @php
        $heure = now()->format('H');
        $salutation = $heure < 12 ? __('app.good_morning') : ($heure < 18 ? __('app.good_afternoon') : __('app.good_evening'));
        $user = Auth::user();
    @endphp
   @else
    <h4 class="mb-4 text-danger text-end">{{ __('app.user_not_logged_in') }}</h4>
   @endif

   <h4 class="mb-4 text-primary">
       {{ $salutation }}, {{ Auth::user()->name ?? '' }}
   </h4>

   <h2 class="mb-4 fw-bold text-center">{{ __('app.my_translation_requests') }}</h2>

   <div class="card shadow-sm border-0 rounded-4 p-4">
       <!-- Table responsive -->
       <div class="table-responsive">
           <table class="table table-striped table-hover align-middle">
               <thead class="table-primary">
                   <tr>
                       <th>{{ __('app.holder_name') }}</th>
                       <th>{{ __('app.date') }}</th>
                       <th>{{ __('app.source_language') }}</th>
                       <th>{{ __('app.target_language') }}</th>
                       <th>{{ __('app.delivery_date') }}</th>
                       <th>{{ __('app.status') }}</th>
                       <th class="text-center">{{ __('app.action') }}</th>
                   </tr>
               </thead>
               <tbody>
                   @forelse($demandes as $demande)
                   <tr>
                       <td>{{ $demande->nom_titulaire }}</td>
                       <td>{{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y') }}</td>
                       <td>{{ __('demande.' . strtolower($demande->langue_origine)) }}</td>
                       <td>{{ __('demande.' . strtolower($demande->langue_souhaitee)) }}</td>
                       <td>{{ $demande->date_fin ? \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') : '—' }}</td>
                       <td>
                           @php
                               $statusLabels = [
                                   'en_attente' => __('app.status_pending'),
                                   'en_cours' => __('app.status_in_progress'),
                                   'terminee' => __('app.status_finished'),
                               ];
                               $statusColors = [
                                   'en_attente' => 'bg-info',
                                   'en_cours' => 'bg-warning',
                                   'terminee' => 'bg-success',
                               ];
                           @endphp
                           <span class="badge {{ $statusColors[$demande->status] ?? 'bg-secondary' }}">
                               {{ $statusLabels[$demande->status] ?? '—' }}
                           </span>
                       </td>
                       <td class="text-center">
                           <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-{{ $demande->id }}">
                               {{ __('app.details') }}
                           </button>
                       </td>
                   </tr>

                   <!-- Modal -->
                   <div class="modal fade" id="modal-{{ $demande->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $demande->id }}" aria-hidden="true">
                     <div class="modal-dialog modal-lg modal-dialog-scrollable" @if(app()->getLocale() == 'ar') dir="rtl" style="text-align: right;" @endif>
                       <div class="modal-content">
                         <div class="modal-header">
                           <h5 class="modal-title fw-bold" id="modalLabel-{{ $demande->id }}">{{ __('app.request_details') }}</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                         </div>

                         <div class="modal-body">
                           <p><strong>{{ __('app.source_language') }} :</strong> {{ __('demande.' . strtolower($demande->langue_origine)) }}</p>
                           <p><strong>{{ __('app.target_language') }} :</strong> {{ __('demande.' . strtolower($demande->langue_souhaitee)) }}</p>

                           @foreach($demande->documents ?? [] as $doc)
                               <li class="mb-2">
                                   <strong>{{ __('demande.categorie') }} :</strong>
                                   {{ __('documents.types.' . $doc['categorie']) ?? $doc['categorie'] }}<br>
                                   <strong>{{ __('demande.sous_type_document') }} :</strong>
                                   {{ __('documents.types.' . $doc['sous_type']) ?? $doc['sous_type'] }}
                               </li>
                           @endforeach

                           <p><strong>{{ __('app.start_date') }} :</strong>
                             {{ $demande->date_debut ? \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') : __('app.not_defined') }}
                           </p>

                           <p><strong>{{ __('app.delivery_date') }} :</strong>
                             {{ $demande->date_fin ? \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') : __('app.not_defined') }}
                           </p>

                           <p><strong>{{ __('app.total_price') }} :</strong>
                             {{ $demande->prix_total ? number_format($demande->prix_total, 2, ',', ' ') . ' MAD' : __('app.not_defined') }}
                           </p>

                           <p><strong>{{ __('app.your_address') }} : </strong>{{ $demande->adresse }}</p>

                           <p>
                             <strong>{{ __('app.status') }} :</strong>
                             <span class="badge {{ $statusColors[$demande->status] ?? 'bg-secondary' }}">
                               {{ $statusLabels[$demande->status] ?? '—' }}
                             </span>
                           </p>

                           @if ($demande->status === 'en_cours')
                             <div class="alert alert-warning mt-3">{{ __('app.status_processing') }}</div>
                           @elseif ($demande->status === 'en_attente')
                             <div class="alert alert-info mt-3">{{ __('app.status_pending_text') }}</div>
                           @elseif ($demande->status === 'terminee')
                             <div class="alert alert-success mt-3">{{ __('app.status_finished_text') }}</div>
                           @endif

                           <h5 class="mt-4">📎 {{ __('app.uploaded_files') }} :</h5>
                           <ul class="list-group">
                             @forelse ($demande->fichiers->where('type', 'initial') as $fichier)
                               <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                 <span class="mb-2 mb-md-0">{{ basename($fichier->chemin) }}</span>
                                 <div class="d-flex gap-2 flex-wrap">
                                   <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="btn btn-sm btn-info">{{ __('app.view') }}</a>
                                   <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichier->id]) }}" class="btn btn-sm btn-primary">{{ __('app.download') }}</a>
                                 </div>
                               </li>
                             @empty
                               <li class="list-group-item fst-italic">{{ __('app.no_files_uploaded') }}</li>
                             @endforelse
                           </ul>

                           @if ($demande->status === 'terminee')
                             <h5 class="mt-4 text-success">✅ {{ __('app.translated_files') }}</h5>

                             @if (!$demande->confirme_par_client)
                               <form method="POST" action="{{ route('demande.confirmer_reception', $demande->id) }}" class="d-inline">
                                   @csrf
                                   <button type="submit" class="btn btn-sm btn-outline-success mt-2" onclick="return confirm('{{ __('app.confirm_receipt_question') }}');">
                                       ✅ {{ __('app.confirm_receipt') }}
                                   </button>
                               </form>
                               <h5 class="mt-4">{{ __('app.admin_messages') }} :</h5>
                             @else
                               <div class="mt-2">
                                   <span class="badge bg-success">✅ {{ __('app.already_confirmed') }}</span>
                               </div>
                             @endif

                             <ul class="list-group mt-3">
                               @forelse ($demande->fichiers->where('type', 'final') as $fichierTraduit)
                                 <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                   <span class="mb-2 mb-md-0">{{ basename($fichierTraduit->chemin) }}</span>
                                   <div class="d-flex gap-2 flex-wrap">
                                     <a href="{{ asset('storage/' . $fichierTraduit->chemin) }}" target="_blank" class="btn btn-sm btn-info">{{ __('app.view') }}</a>
                                     <a href="{{ route('suivi_demande.download', ['id' => $demande->id, 'fichierId' => $fichierTraduit->id]) }}" class="btn btn-sm btn-success">{{ __('app.download') }}</a>
                                   </div>
                                 </li>
                               @empty
                                 <li class="list-group-item fst-italic">{{ __('app.no_translated_files') }}</li>
                               @endforelse
                             </ul>
                           @endif
                         </div>

                         <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.close') }}</button>
                         </div>
                       </div>
                     </div>
                   </div>
                   @empty
                   <tr>
                       <td colspan="7" class="text-center text-muted">{{ __('app.no_requests_found') }}</td>
                   </tr>
                   @endforelse
               </tbody>
           </table>
       </div>

       <div class="mt-3 d-flex justify-content-center">
           {{ $demandes->links() }}
       </div>
   </div>
</div>
@endsection
