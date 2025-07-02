<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreDemandeRequest;
use App\Models\Demande;
use App\Models\FichierDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $demandes = Demande::where('status', 'en_cours')->paginate(10);

        // $demandes = Demande::paginate(10);
        return view('demande.index', compact('demandes'));
    }

    public function index2(Request $request)
    {
        $demandes = Demande::where('status', 'terminee')->paginate(10);

        // $demandes = Demande::paginate(10);
        return view('demande.demande_ter', compact('demandes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('demande.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
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

        // Construire le tableau documents à insérer dans le champ JSON
        $documents = [];
        foreach ($request->categorie as $index => $categorie) {
            $documents[] = [
                'categorie' => $categorie,
                'sous_type' => $request->sous_type[$index] ?? null,
            ];
        }

        // Enregistrement dans la base
        $demande = Demande::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'cin' => $request->cin,
            'telephone' => $request->telephone,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'documents' => $documents,
            'prix_total' => $request->prix_total,
            'langue_origine' => $request->langue_origine,
            'langue_souhaitee' => $request->langue_souhaitee,
            'remarque' => $request->remarque,
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

        return redirect()->route('demande.create')->with('success', 'Demande envoyée avec les fichiers.');
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
        $demande = Demande::with('fichiers')->findOrFail($id);
        return view('demande.edit', compact('demande'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'cin' => 'required',
            'telephone' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'categorie' => 'required|array',
            'sous_type' => 'required|array',
            'prix_total' => 'required|numeric',
            'langue_origine' => 'required|in:Anglais,Arabe',
            'langue_souhaitee' => 'required|in:Anglais,Arabe',
            'status' => 'required|in:en_cours,terminee,annulee',

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
            'nom' => $request->nom,
            'prenom' => $request->prenom,
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
            'status' => 'required|in:en_cours,terminee,annulee',
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



}
