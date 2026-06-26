<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Family; 
use App\Models\User;   
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth; 
class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'family_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'], 
        ], [
            'family_name.required' => 'Numele familiei este obligatoriu.',
            'name.required' => 'Numele tău este obligatoriu.',
            'email.required' => 'Adresa de email este obligatorie.',
            'email.email' => 'Email invalid',
            'email.unique' => 'Acest email este deja înregistrat.',
            'password.required' => 'Parola este obligatorie.',
            'password.min' => 'Parola trebuie să aibă minimum 8 caractere.',
            'password.confirmed' => 'Parolele nu coincid.'
        ]);

        $family = Family::create([
            'name' => $request->family_name,
        ]);

        $user = User::create([
            'family_id' => $family->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'parent', 
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
