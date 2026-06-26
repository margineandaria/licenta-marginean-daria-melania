<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FamilyMemberController extends Controller
{

    public function index()
    {
        $members = User::where('family_id', Auth::user()->family_id)->get();
        return view('family.index', compact('members'));
    }
    public function create()
    {
        if (Auth::user()->role !== 'parent') {
            return redirect()->route('family.index')->with('error', 'Nu ai permisiunea de a adăuga membri!');
        }

        return view('family.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'parent') {
            abort(403, 'Nu ai permisiunea de a adăuga membri!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:parent,child', 
        ], [
            'name.required' => 'Numele membrului este obligatoriu.',
            'email.required' => 'Adresa de email este obligatorie.',
            'email.email' => 'Te rog să introduci un email valid.',
            'email.unique' => 'Acest email este deja înregistrat în sistem.',
            'password.required' => 'Setarea unei parole este obligatorie.',
            'password.min' => 'Parola trebuie să aibă cel puțin 8 caractere.',
            'password.confirmed' => 'Parolele introduse nu coincid.',
            'role.required' => 'Te rog să alegi rolul membrului (Părinte/Copil).',
            'role.in' => 'Rolul selectat nu este valid.',
        ]);

        User::create([
            'family_id' => Auth::user()->family_id, 
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role, 
        ]);

        return redirect()->route('family.index')->with('success', 'Noul membru a fost adăugat cu succes!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'parent') {
            return redirect()->route('family.index')->with('error', 'Nu ai permisiunea de a șterge membri!');
        }

        if (Auth::id() === $user->id) {
            return redirect()->route('family.index')->with('error', 'Nu îți poți șterge propriul cont de aici!');
        }

        if ($user->family_id !== Auth::user()->family_id) {
            abort(403, 'Acțiune interzisă.');
        }

        $user->delete();

        return redirect()->route('family.index')->with('success', 'Membrul a fost eliminat din familie.');
    }
}