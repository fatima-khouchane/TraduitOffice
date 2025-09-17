<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\FichierDemande;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Nombre de documents terminés et en cours
        $documents_termines = Demande::where('status', 'terminee')->count();
        $documents_en_cours = Demande::where('status', 'en_cours')->count();

        // Langue la plus traduite
        $langue_plus_traduite = Demande::select('langue_origine', 'langue_souhaitee', DB::raw('count(*) as total'))
            ->groupBy('langue_origine', 'langue_souhaitee')
            ->orderByDesc('total')
            ->limit(1)
            ->first();

        // Top catégories (avec traduction)
        $categories_top_raw = Demande::select(
            DB::raw('JSON_EXTRACT(documents, "$[0].categorie") as categorie'),
            DB::raw('JSON_EXTRACT(documents, "$[0].sous_type") as sous_type'),
            DB::raw('count(*) as total')
        )
            ->groupBy('categorie', 'sous_type')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $categories_top = $categories_top_raw->map(function ($item) {
            $categorie = json_decode($item->categorie, true);
            $sous_type = json_decode($item->sous_type, true);

            // Traduction via config/documents.php -> types
            if ($sous_type && __('documents.types.' . $sous_type) !== 'documents.types.' . $sous_type) {
                $categorie_complete = __('documents.types.' . $sous_type);
            } elseif ($categorie && __('documents.types.' . $categorie) !== 'documents.types.' . $categorie) {
                $categorie_complete = __('documents.types.' . $categorie);
            } else {
                $categorie_complete = $categorie ?? 'N/A';
            }

            return (object) [
                'categorie_complete' => $categorie_complete,
                'total' => $item->total
            ];
        });

        // Graphique des mois
        $mois = [];
        $valeurs = [];
        $annee = request('annee', now()->year);

        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::createFromDate($annee, $i, 1);
            $mois[] = $date->translatedFormat('F'); // Mois traduit selon la locale

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

        // Clients en ligne
        $clients_en_ligne = User::whereHas('demandes', function ($query) {
            $query->where('is_online', true);
        })->count();

        // Retour de la vue
        return view('statistique', compact(
            'documents_termines',
            'documents_en_cours',
            'langue_plus_traduite',
            'categories_top',
            'mois',
            'valeurs',
            'clients_en_ligne'
        ));
    }

}


