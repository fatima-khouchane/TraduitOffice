<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\FichierDemande;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    public function index()
    {
        $documents_termines = Demande::where('status', 'terminee')->count();
        $documents_en_cours = Demande::where('status', 'en_cours')->count();

        $langue_plus_traduite = Demande::select('langue_origine', 'langue_souhaitee', DB::raw('count(*) as total'))
            ->groupBy('langue_origine', 'langue_souhaitee')
            ->orderByDesc('total')
            ->limit(1)
            ->first();

        $categories_top = Demande::select(
            DB::raw('CONCAT(
                JSON_UNQUOTE(JSON_EXTRACT(documents, "$[0].categorie")),
                IF(JSON_EXTRACT(documents, "$[0].sous_type") IS NOT NULL,
                    CONCAT(" → ", JSON_UNQUOTE(JSON_EXTRACT(documents, "$[0].sous_type"))),
                    ""
                )
            ) as categorie_complete'),
            DB::raw('count(*) as total')
        )
            ->groupBy('categorie_complete')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $mois = [];
        $valeurs = [];
        $annee = request('annee', now()->year);

        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::createFromDate($annee, $i, 1);
            $mois[] = $date->translatedFormat('F'); // Mois en français, ex : "Janvier"

            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $count = FichierDemande::where('type', 'final')
                ->whereHas('demande', function ($query) use ($startOfMonth, $endOfMonth) {
                    $query->where('status', 'terminee')
                        ->whereBetween('date_fin', [$startOfMonth, $endOfMonth]);
                })
                ->count();

            $valeurs[] = $count;
        }



        return view('statistique', compact(
            'documents_termines',
            'documents_en_cours',
            'langue_plus_traduite',
            'categories_top',
            'mois',
            'valeurs'
        ));
    }
}


