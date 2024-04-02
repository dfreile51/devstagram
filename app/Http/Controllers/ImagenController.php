<?php

namespace App\Http\Controllers;

use Error;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        $imagen = $request->file('file');

        $nombreImagen = Str::uuid() . "." . $imagen->extension();

        $imagenServidor = Image::make($imagen);
        $imagenServidor->fit(1000, 1000);

        if (!is_dir(public_path('uploads'))) {
            mkdir(public_path('uploads'));
        }

        $imagenPath = public_path('uploads') . "/" . $nombreImagen;
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreImagen]);
    }

    public function destroy(Request $request)
    {
        if (!File::exists(public_path('uploads') . "/" . $request->imagen)) {
            ImagenController::cleanUploadsImages();
            return response()->json([
                "mensaje" => "Imagen no encontrada",
                "error" => true
            ], 404);
        }

        $posts = Post::where('imagen', $request->imagen)->get();

        if ($posts->count() !== 0) {
            ImagenController::cleanUploadsImages();
            return response()->json([
                "mensaje" => "Error, has intentado borrar otra imagen.",
                "error" => true
            ], 400);
        }

        try {
            $imagenDelete = public_path('uploads') . "/" . $request->imagen;
            unlink($imagenDelete);
        } catch (Error $e) {
            return response()->json([
                "mensaje" => "Error al intentar borrar la imagen.",
                "error" => true
            ], 400);
        }

        return response()->json([
            "mensaje" => "Imagen eliminada correctamente.",
            "error" => false
        ], 200);
    }

    private static function cleanUploadsImages()
    {
        $imagenes = glob(public_path('uploads') . '/*');
        $imagenesBaseDatos = Post::pluck('imagen')->toArray();

        foreach ($imagenes as $imagen) {
            if (!in_array(basename($imagen), $imagenesBaseDatos)) {
                unlink($imagen);
            }
        }
    }
}
