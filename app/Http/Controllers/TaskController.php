<?php

namespace App\Http\Controllers;

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

}
;
