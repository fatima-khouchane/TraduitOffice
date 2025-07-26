<?php

namespace App\Http\Controllers;

use App\Models\Demande;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {

        // $user = Auth::user();
        // $tasks = Demande::where('translator_id', $user->id)->get(); // selon ta logique
        // return view('translator.tasks', compact('tasks'))
        return view('translator.tasks');
    }
    public function affecter(Request $request, Demande $demande)
    {
        $request->validate([
            'translator_id' => 'required|exists:users,id',
        ]);

        $demande->translator_id = $request->translator_id;
        $demande->status = 'en_cours'; // optionnel
        $demande->save();

        return redirect()->back()->with('success', 'Traducteur affecté avec succès.');
    }

}
;
