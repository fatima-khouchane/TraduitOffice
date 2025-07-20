<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use App\Models\FichierDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class DemandeClientController extends Controller
{
    public function createClient()
    {
        return view('client.createDemande'); // Assure-toi que le fichier est dans /resources/views/client/
    }

    public function storeFromClient(Request $request)
    {
        $request->validate([
            'nom_titulaire' => 'required',

            'cin' => 'required',
            'telephone' => 'required',
            'adresse' => 'required',
            'langue_origine' => 'required',
            'langue_souhaitee' => 'required',
            'fichiers.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Génération des documents
        $documents = [];
        if ($request->has('categorie')) {
            foreach ($request->categorie as $index => $categorie) {
                $documents[] = [
                    'categorie' => $categorie,
                    'sous_type' => $request->sous_type[$index] ?? null,
                ];
            }
        }
        $user = Auth::user();

        $demande = Demande::create([
            'nom_titulaire' => $request->nom_titulaire,
            'nom_demandeur' => $user->name,
            'cin' => $request->cin,
            'telephone' => $request->telephone,
            'date_debut' => now()->format('Y-m-d'),
            'date_fin' => null,
            'langue_origine' => $request->langue_origine,
            'langue_souhaitee' => $request->langue_souhaitee,
            'remarque' => $request->remarque,
            'status' => 'en_attente',
            'user_id' => Auth::id(),
            'documents' => $documents,
            'prix_total' => null,
            'is_online' => true, // ✅ Ajout ici
            'adresse' => $request->adresse
        ]);


        if ($request->hasFile('fichiers')) {
            foreach ($request->file('fichiers') as $file) {
                $path = $file->store('fichiers', 'public');
                FichierDemande::create([
                    'demande_id' => $demande->id,
                    'chemin' => $path,
                ]);
            }
        }

        return redirect()->route('client.mes_demandes')->with('success', 'Demande envoyée avec succès.');
    }

    public function mesDemandes()
    {
        $user = Auth::user();
        $demandes = Demande::with('fichiers') // ✅ chargement des fichiers
            ->where('user_id', $user->id)
            ->paginate(10);

        return view('client.home', [
            'demandes' => $demandes
        ]);
    }




    public function confirmerReception($id)
    {
        $demande = Demande::where('id', $id)
            ->where('user_id', Auth::id()) // sécurité
            ->firstOrFail();

        if ($demande->status === 'terminee') {
            $demande->confirme_par_client = true;
            $demande->save();
        }

        return back()->with('success', 'Vous avez confirmé la réception des fichiers traduits.');



    }



    public function messages()
    {
        $demandes = Demande::with(['messages'])->where('user_id', Auth::id())->get();

        // Marquer tous les messages comme lus
        foreach ($demandes as $demande) {
            foreach ($demande->messages as $message) {
                if (!$message->is_read) {
                    $message->is_read = true;
                    $message->save();
                }
            }
        }

        return view('client.messages', compact('demandes'));
    }

}


