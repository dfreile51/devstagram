@extends('layouts.app')

@section('titulo')
    Página Principal
@endsection

@section('contenido')
    @if ($posts->count())
        <x-listar-post :posts="$posts" />
    @else
        <p class="text-center font-semibold uppercase text-slate-500">No Hay Posts, sigue a alguien para poder mostrar sus
            posts</p>
    @endif
@endsection
