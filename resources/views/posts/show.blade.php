@extends('layouts.box-app')

@section('box-title')
    {{ __('Post') . " " . $post->id }}
@endsection

@section('box-content')
<x-columns columns=2>
    @section('column-1')
        <img class="w-full" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview"/>
    @endsection
    @section('column-2')
        <table class="table">
            <tbody>                
                <!-- ... (otras filas de la tabla) ... -->
                <tr>
                    <td><strong>Likes</strong></td>
                    <td>{{ $post->likes_count }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Botón para dar like o unlike al post -->
        @if(auth()->check())
            @if($userLiked)
                <form method="post" action="{{ route('posts.unlike', $post) }}" id="unlike-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Unlike</button>
                </form>
            @else
                <form method="post" action="{{ route('posts.like', $post) }}" id="like-form">
                    @csrf
                    <button type="submit">Like</button>
                </form>
            @endif
        @else
            <p>Inicia sesión para dar like a este post.</p>
        @endif

        <!-- Resto del contenido -->
        <div class="mt-8">
            <x-primary-button href="{{ route('posts.edit', $post) }}">
                {{ __('Edit') }}
            </x-danger-button>
            <x-danger-button href="{{ route('posts.delete', $post) }}">
                {{ __('Delete') }}
            </x-danger-button>
            <x-secondary-button href="{{ route('posts.index') }}">
                {{ __('Back to list') }}
            </x-secondary-button>
        </div>
    @endsection
</x-columns>
@endsection