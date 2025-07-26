<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TranslatorController extends Controller
{
    public function create()
    {
        return view('admin.create_translator');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'translator',
        ]);

        return redirect()->route('admin.translators.create')->with('success', 'Traducteur ajouté avec succès.');
    }
    public function index()
    {


        $translators = User::where('role', 'translator')->latest()->get();
        return view('translator.index', compact('translators'));
    }
}
