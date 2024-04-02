<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'index')->name('register');
    Route::post('/register',  'store');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'store');
});
Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');

Route::controller(PostController::class)->group(function () {
    Route::get('/{user:username}',  'index')->name('posts.index');
    Route::get('/posts/create', 'create')->name('posts.create');
    Route::post('/posts', 'store')->name('posts.store');
    Route::get('/{user:username}/posts/{post}', 'show')->name('posts.show');
    Route::delete('/posts/{post}', 'destroy')->name('posts.destroy');
});

Route::post('/{user:username}/posts/{post}', [ComentarioController::class, 'store'])->name('comentarios.store');

// Like a las fotos
Route::controller(LikeController::class)->group(function () {
    Route::post('/posts/{post}/likes', 'store')->name('posts.likes.store');
    Route::delete('/posts/{post}/likes', 'destroy')->name('posts.likes.destroy');
});

// Rutas para el perfil
Route::controller(PerfilController::class)->group(function () {
    Route::get('{user:username}/editar-perfil', 'index')->name('perfil.index');
    Route::post('{user:username}/editar-perfil', 'store')->name('perfil.store');
});

// Siguiendo usuario
Route::controller(FollowerController::class)->group(function () {
    Route::post('/{user:username}/follow', 'store')->name('users.follow');
    Route::delete('/{user:username}/follow', 'destroy')->name('users.unfollow');
});
