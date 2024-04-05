<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comentario;

class ComentarioPost extends Component
{
    public $comentario;
    public $post;

    public function crearComentario()
    {
        $validated = $this->validate([
            'comentario' => 'required|max:255'
        ]);

        Comentario::create([
            'user_id' => auth()->user()->id,
            'post_id' => $this->post->id,
            'comentario' => $validated['comentario']
        ]);

        session()->flash('mensaje', 'Comentario Realizado Correctamente');
        $this->reset('comentario');
    }

    public function render()
    {
        return view('livewire.comentario-post');
    }
}
