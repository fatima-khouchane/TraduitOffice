<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf; // si tu utilises barryvdh/laravel-dompdf

// use App\Http\Requests\StoreDemandeRequest;
use App\Models\Demande;
use App\Models\FichierDemande;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $traducteurs = User::where('role', 'translator')->get();
        $user = Auth::user();

        // Si le traducteur est connecté → afficher uniquement ses demandes
        if ($user && $user->role === 'translator') {
            $demandes = Demande::whereIn('status', ['en_cours', 'en_attente'])
                ->where('translator_id', $user->id)
                ->paginate(10);
        } else {
            // Admin ou autre → afficher toutes les demandes en attente ou en cours
            $demandes = Demande::whereIn('status', ['en_cours', 'en_attente'])
                ->paginate(10);
        }

        return view('demande.index', compact('demandes', 'traducteurs'));
    }

    public function index2(Request $request)
    {
        $traducteurs = User::where('role', 'translator')->get();
        $user = Auth::user();

        // Si le traducteur est connecté → afficher uniquement ses demandes terminées
        if ($user && $user->role === 'translator') {
            $demandes = Demande::where('status', 'terminee')
                ->where('translator_id', $user->id)
                ->paginate(10);
        } else {
            // Admin ou autre → afficher toutes les demandes terminées
            $demandes = Demande::where('status', 'terminee')
                ->paginate(10);
        }

        return view('demande.demande_ter', compact('demandes', 'traducteurs'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documents = config('documents'); // récupère le tableau depuis config/documents.php
        return view('demande.create', compact('documents'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_demandeur' => 'nullable',
            'nom_titulaire' => 'required',
            'cin' => 'required',
            'telephone' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'categorie' => 'required|array',
            'sous_type' => 'required|array',
            'prix_total' => 'required|numeric',
            'langue_origine' => 'required',
            'langue_souhaitee' => 'required',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $documents = [];
        foreach ($request->categorie as $index => $categorie) {
            $documents[] = [
                'categorie' => $categorie,  // clé, ex: "administratif"
                'sous_type' => $request->sous_type[$index] ?? null, // clé ex: "certificat_residence"
            ];
        }

        $demande = Demande::create([
            'nom_demandeur' => $request->nom_demandeur,
            'nom_titulaire' => $request->nom_titulaire,
            'cin' => $request->cin,
            'telephone' => $request->telephone,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'documents' => $documents,
            'prix_total' => $request->prix_total,
            'langue_origine' => $request->langue_origine,
            'langue_souhaitee' => $request->langue_souhaitee,
            'remarque' => $request->remarque,
            'user_id' => Auth::id(),
            'is_online' => false,
        ]);

        // Gérer les fichiers
        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs('fichiers', $originalName, 'public');

                FichierDemande::create([
                    'demande_id' => $demande->id,
                    'chemin' => $path,
                ]);
            }
        }

        return redirect()->route('demande.create')->with('success', __('Demande envoyée avec succès.'));
    }



    /**
     * Display the specified resource.
     */
    // Dans DemandeController.php
    public function show($id, Request $request)
    {
        $demande = Demande::with('fichiers')->findOrFail($id);
        $traduit = $request->query('traduit', false); // récupère ?traduit=true ou false

        return view('demande.show', compact('demande', 'traduit'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $documents = config('documents'); // récupère le tableau depuis config/documents.php

        $demande = Demande::with('fichiers')->findOrFail($id);

        return view('demande.edit', compact('demande', 'documents'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_demandeur' => 'nullable',
            'nom_titulaire' => 'required',
            'cin' => 'required',
            'telephone' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'categorie' => 'required|array',
            'sous_type' => 'required|array',
            'prix_total' => 'required|numeric',
            'langue_origine' => 'required|in:Anglais,Arabe',
            'langue_souhaitee' => 'required|in:Anglais,Arabe',
            'status' => 'required|in:en_cours,en_attente',

            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $demande = Demande::findOrFail($id);

        // Supprimer les fichiers cochés
        if ($request->filled('supprimer_fichiers')) {
            foreach ($request->input('supprimer_fichiers') as $fichierId) {
                $fichier = $demande->fichiers()->find($fichierId);
                if ($fichier) {
                    Storage::disk('public')->delete($fichier->chemin);
                    $fichier->delete();
                }
            }
        }

        // Préparer documents
        $documents = [];
        foreach ($request->categorie as $index => $categorie) {
            $documents[] = [
                'categorie' => $categorie,
                'sous_type' => $request->sous_type[$index] ?? null,
            ];
        }

        // Mettre à jour la demande
        $demande->update([
            'nom_titulaire' => $request->nom_titulaire,
            'nom_demandeur' => $request->nom_demandeur,
            'cin' => $request->cin,
            'telephone' => $request->telephone,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'documents' => $documents,
            'prix_total' => $request->prix_total,
            'langue_origine' => $request->langue_origine,
            'langue_souhaitee' => $request->langue_souhaitee,
            'status' => $request->status,

            'remarque' => $request->remarque,
        ]);

        // Ajouter les nouveaux fichiers
        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $file) {
                $path = $file->store('fichiers', 'public');
                FichierDemande::create([
                    'demande_id' => $demande->id,
                    'chemin' => $path,
                ]);
            }
        }

        return redirect()->route('suivi_demande.edit', $id)->with('success', 'La demande a été mise à jour avec succès.');
    }




    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:en_cours,terminee',
        ]);

        $demande = Demande::findOrFail($id);
        $demande->status = $request->status;
        $demande->save();

        return response()->json(['message' => 'Statut mis à jour avec succès']);
    }

    public function download($id, $fichierId)
    {
        $demande = Demande::findOrFail($id);
        $fichier = $demande->fichiers()->findOrFail($fichierId);

        $path = $fichier->chemin;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, "Fichier non trouvé.");
        }
        $fullPath = storage_path('app/public/' . $fichier->chemin);
        return response()->download($fullPath);

    }



    public function uploadFiles(Request $request, $id)
    {
        $request->validate([
            'justificatif_termine' => 'required',
            'justificatif_termine.*' => 'file|mimes:pdf,jpeg,png,jpg,gif,svg|max:20480', // 20MB par fichier
        ]);

        // Vérifier la taille totale des fichiers (max 50 Mo par exemple)
        $totalSize = 0;
        foreach ($request->file('justificatif_termine') as $file) {
            $totalSize += $file->getSize();
        }

        if ($totalSize > 50 * 1024 * 1024) { // 50 Mo
            return back()->withErrors(['La taille totale des fichiers dépasse 50 Mo.']);
        }

        $demande = Demande::findOrFail($id);

        if ($request->hasFile('justificatif_termine')) {
            foreach ($request->file('justificatif_termine') as $file) {
                $originalName = $file->getClientOriginalName();
                $path = $file->storeAs('fichiers', $originalName, 'public');

                $demande->fichiers()->create([
                    'chemin' => $path,
                    'type' => 'final',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Fichiers envoyés avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $demande = Demande::findOrFail($id);
        $demande->delete();

        return redirect()->route('suivi_demande.index')->with('success', 'Demande supprimée avec succès.');
    }

    // imprimer

    // use Mpdf\Mpdf;

    public function imprimerPDF(Request $request)
    {
        $request->validate([
            'mois' => 'required|date_format:Y-m'
        ]);

        $mois = $request->input('mois');
        $start = $mois . '-01';
        $end = \Carbon\Carbon::parse($start)->endOfMonth()->format('Y-m-d');

        $demandes = Demande::where('status', 'terminee')
            ->whereBetween('date_fin', [$start, $end])
            ->with('fichiers')
            ->get();

        $total = $demandes->sum('prix_total');

        $html = view('demande.pdf', compact('demandes', 'mois', 'total'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'default_font' => 'dejavusans',
            'tempDir' => storage_path('app/tmp') // utile en production
        ]);

        $mpdf->WriteHTML($html);
        return response($mpdf->Output('', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }





    public function demandesConfirmees()
    {
        $demandes = Demande::where('confirme_par_client', true)
            ->orderBy('date_fin', 'desc')
            ->paginate(20);

        return view('demande.confirmees', compact('demandes'));
    }

    public function envoyerMessage(Request $request, $id)
    {
        $request->validate([
            'contenu' => 'required|string|max:2000',
        ]);

        $demande = Demande::findOrFail($id);

        // Enregistrer le message
        $demande->messages()->create([
            'contenu' => $request->contenu,
        ]);

        return redirect()->back()->with('success', 'Message envoyé au client.');
    }


}
