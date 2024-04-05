<div>
    <div class="shadow bg-white p-5 mb-5">
        @auth
            <p class="text-xl font-bold text-center mb-4">Agrega un Nuevo Comentario</p>

            @if (session('mensaje'))
                <div x-data="{ open: true }">
                    <div x-init="setTimeout(() => open = false, 3000)" x-show="open" x-transition.duration.300ms
                        class="bg-green-500 p-2 rounded-lg mb-6 text-white text-center uppercase font-bold">
                        <span>{{ session('mensaje') }}</span>
                    </div>
                </div>
            @endif

            <form wire:submit='crearComentario'>
                @csrf
                <div class="mb-5">
                    <label for="comentario" class="mb-2 block uppercase text-gray-500 font-bold">Añade un
                        Comentario</label>
                    <textarea id="comentario" wire:model="comentario" placeholder="Agrega un Comentario"
                        class="border p-3 w-full rounded-lg @error('comentario')
                        border-red-500
                    @enderror"></textarea>
                    @error('comentario')
                        <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                            {{ $message }}</p>
                    @enderror
                </div>

                <input type="submit" value="Comentar"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
        @endauth

        <div>
            <div class="bg-white shadow mb-5 mt-10 max-h-96 overflow-y-auto">
                @if ($post->comentarios->count())
                    @foreach ($post->comentarios()->latest()->get() as $comentario)
                        <div class="p-5 border-gray-300 border-b">
                            <a href="{{ route('posts.index', $comentario->user) }}"
                                class="font-bold">{{ $comentario->user->username }}</a>
                            <p>{{ $comentario->comentario }}</p>
                            <p class="text-sm text-gray-500">{{ $comentario->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                @else
                    <p class="p-10 text-center">No Hay Comentarios Aún</p>
                @endif
            </div>
        </div>
    </div>
</div>
