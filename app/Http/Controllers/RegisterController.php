<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class RegisterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'guest'
        ];
    }

    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            "name" => "required|max:30",
            "username" => ["required", "unique:users", "min:3", "max:15", "regex:/^[a-z][a-z0-9_]{3,15}$/i"],
            "email" => "required|unique:users|email|max:60",
            "password" => "required|confirmed|min:8"
        ]);

        // Crear al usuario y almacenarlo en la BD
        User::create([
            "name" => $request->name,
            "username" => Str::slug($request->username),
            "email" => $request->email,
            "password" => $request->password
        ]);

        // Autenticar un usuario
        // auth()->attempt([
        //     "email" => $request->email,
        //     "password" => $request->password
        // ]);

        // Otra forma de autenticar
        auth()->attempt($request->only('email', 'password'));


        // Redireccionar al usuario
        return redirect()->route('posts.index', [
            "user" => auth()->user()->username
        ]);
    }
}
