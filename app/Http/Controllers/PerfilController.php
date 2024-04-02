<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index(User $user)
    {
        if (!Gate::allows('view', $user)) {
            abort(403);
        }

        return view('perfil.index');
    }

    public function store(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $request->validate([
            "username" => ["required", Rule::unique('users', 'username')->ignore(auth()->user()), "min:3", "max:15", "regex:/^[a-z][a-z0-9_]{3,15}$/i", "not_in:twitter,editar-perfil"],
            "email" => ["required", Rule::unique("users", "email")->ignore(auth()->user()), "email", "max:60"]
        ]);

        if ($request->imagen) {
            // Borrar la anterior imagen de perfil
            if ($user->imagen) {
                $imagenDelete = public_path('perfiles' . "/" . $user->imagen);
                unlink($imagenDelete);
            }

            $imagen = $request->file('imagen');

            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);

            if (!is_dir(public_path('perfiles'))) {
                mkdir(public_path('perfiles'));
            }

            $imagenPath = public_path('perfiles') . "/" . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        if ($request->password_old || $request->password) {
            if (!Hash::check($request->password_old, auth()->user()->password)) {
                return back()->withErrors(["password_old" => "La password actual no coincide"])->withInput($request->only("username", "email"));
            }

            $request->validate([
                "password" => "required|confirmed|min:8"
            ]);

            $nuevaPassword = $request->password;
        }

        // Guardar Cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->email = $request->email;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->password = $nuevaPassword ?? auth()->user()->password;
        $usuario->save();

        // Redireccionar al usuario
        return redirect()->route('posts.index', $usuario->username);
    }
}
