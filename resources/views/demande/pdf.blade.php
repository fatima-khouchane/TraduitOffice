<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes traduites - {{ \Carbon\Carbon::parse($mois)->translatedFormat('F Y') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 6px; }
        .header { text-align: center; font-weight: bold; margin-bottom: 20px; }
        .empty { text-align: center; color: red; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Registre de la traduction </h2>
        <p>Mois : {{ \Carbon\Carbon::parse($mois)->translatedFormat('F Y') }}</p>
    </div>

    @if($demandes->isEmpty())
        <p class="empty">❗ Aucune demande traduite trouvée pour ce mois.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>CIN</th>
                    <th>Téléphone</th>
                    <th>date</th>
                    <th>Documents Traduits</th>
                    <th>Montant(MAD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($demandes as $demande)
                <tr>
                    <td>{{ $demande->nom }}</td>
                    <td>{{ $demande->prenom }}</td>
                    <td>{{ $demande->cin }}</td>
                    <td>{{ $demande->telephone }}</td>
                        <td>{{ \Carbon\Carbon::parse($demande->date_fin)->translatedFormat('d F Y') }}</td>

                    <td>
                        <ul>
                            @foreach($demande->documents ?? [] as $doc)
                                <li>
                                    Catégorie : {{ $doc['categorie'] ?? '-' }} <br>
                                    Sous-type : {{ $doc['sous_type'] ?? '-' }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ number_format($demande->prix_total, 2, ',', ' ') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4 style="margin-top: 20px;">Montant Total du mois : {{ number_format($total, 2, ',', ' ') }} MAD</h4>
    @endif
</body>
</html>
