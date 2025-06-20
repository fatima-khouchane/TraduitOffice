<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreDemandeRequest;
use App\Models\Demande;
use App\Models\FichierDemande;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // afficher formulaire

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
            'langue_origine' => 'required|in:Anglais,Français',
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
                $path = $file->store('fichiers', 'public');

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
