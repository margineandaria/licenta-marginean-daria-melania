<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'name' => 'required|string|max:255',
            'education_level' => 'nullable|string',
            'work_domain' => 'nullable|string',
            'geographic_zone' => 'nullable|string',
            'age_category' => 'nullable|string',
            'housing_status' => 'nullable|string',
        ];
        if ($user->role === 'parent') {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Numele este obligatoriu.',
            'name.max' => 'Numele este prea lung.',
            'email.required' => 'Adresa de email este obligatorie.',
            'email.email' => 'Introdu o adresă de email validă.',
            'email.unique' => 'Acest email este deja folosit de altcineva.',
        ]);

        $user->update($validated); 

        return redirect()->route('profile.index')->with('success', 'Datele de profil au fost actualizate cu succes!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'parent') {
            abort(403, 'Doar conturile de utilizatori cu rol de părinte pot schimba parolele.');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed', 
        ], [
            'current_password.required' => 'Te rog să introduci parola ta actuală.',
            'password.required' => 'Te rog să alegi o parolă nouă.',
            'password.min' => 'Noua parolă trebuie să aibă minimum 8 caractere.',
            'password.confirmed' => 'Confirmarea noii parole nu se potrivește.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Parola curentă este incorectă.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.index')->with('success', 'Parola a fost schimbată cu succes!');
    }
}