<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" @if(app()->getLocale() === 'ar') dir="rtl" @endif>
<head>
    <meta charset="UTF-8">
    <title>{{ __('pdf.title') }} - {{ \Carbon\Carbon::parse($mois)->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            @if(app()->getLocale() === 'ar') direction: rtl; text-align: right; @endif
        }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 6px; }
        .table th {
            background-color: #B9F6CA;
            font-weight: bold;
            text-align: center;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .empty { text-align: center; color: red; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ __('pdf.registre') }}</h2>
        <p>{{ __('pdf.mois') }} : {{ \Carbon\Carbon::parse($mois)->translatedFormat('F Y') }}</p>
    </div>

    @if($demandes->isEmpty())
        <p class="empty">{{ __('pdf.aucune_demande') }}</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('pdf.nom_titulaire') }}</th>
                    <th>{{ __('pdf.nom_demandeur') }}</th>
                    <th>{{ __('pdf.cin') }}</th>
                    <th>{{ __('pdf.telephone') }}</th>
                    <th>{{ __('pdf.date') }}</th>
                    <th>{{ __('pdf.documents_traduits') }}</th>
                    <th>{{ __('pdf.montant') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($demandes as $demande)
                    <tr>
                        <td>{{ $demande->nom_titulaire }}</td>
                        <td>{{ $demande->nom_demandeur }}</td>
                        <td>{{ $demande->cin }}</td>
                        <td>{{ $demande->telephone }}</td>
                        <td>{{ \Carbon\Carbon::parse($demande->date_fin)->translatedFormat('d F Y') }}</td>
                        <td>
                         <ul>
   @foreach($demande->documents ?? [] as $doc)
    <li class="mb-2">
        <strong>{{ __('demande.categorie') }} :</strong>
        {{ __( $doc['categorie']) ?? ucfirst($doc['categorie']) }}<br>

        <strong>{{ __('demande.sous_type') }} :</strong>
        {{ __( $doc['sous_type']) ?? ucfirst($doc['sous_type'] ?? '—') }}
    </li>
@endforeach
</ul>



                        </td>
                        <td>{{ number_format($demande->prix_total, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="margin-top: 20px;">{{ __('pdf.montant_total') }} : {{ number_format($total, 2, ',', ' ') }} MAD</h4>
    @endif
</body>
</html>
