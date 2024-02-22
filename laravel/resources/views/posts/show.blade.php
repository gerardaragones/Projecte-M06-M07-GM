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
                <tr>
                    <td><strong>ID<strong></td>
                    <td>{{ $post->id }}</td>
                </tr>
                <tr>
                    <td><strong>Body</strong></td>
                    <td>{{ $post->body }}</td>
                </tr>
                <tr>
                    <td><strong>Lat</strong></td>
                    <td>{{ $post->latitude }}</td>
                </tr>
                <tr>
                    <td><strong>Lng</strong></td>
                    <td>{{ $post->longitude }}</td>
                </tr>
                <tr>
                    <td><strong>Author</strong></td>
                    <td>{{ $author->name }}</td>
                </tr>
                <tr>
                    <td><strong>Visibility</strong></td>
                    <td>{{ $post->visibility->name }}</td>
                </tr>
                <tr>
                    <td><strong>Created</strong></td>
                    <td>{{ $post->created_at }}</td>
                </tr>
                <tr>
                    <td><strong>Updated</strong></td>
                    <td>{{ $post->updated_at }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-8">
            @can('update', $post)
            <x-primary-button href="{{ route('posts.edit', $post) }}">
                {{ __('Edit') }}
            </x-danger-button>
            @endcan
            @can('delete', $post)
            <x-danger-button href="{{ route('posts.delete', $post) }}">
                {{ __('Delete') }}
            </x-danger-button>
            @endcan
            @can('viewAny', App\Models\Post::class)
            <x-secondary-button href="{{ route('posts.index') }}">
                {{ __('Back to list') }}
            </x-secondary-button>
            @endcan
        </div>
        <div class="mt-8">
            <p>{{ $numLikes . " " . __('likes') }}</p>
            @include('partials.buttons-likes')
        </div>
        @auth
            <div class="mt-8">
                <h2>{{ __('Add a Comment') }}</h2>
                <form method="POST" action="{{ route('comments.store', $post) }}">
                    @csrf

                    <div class="mb-4">
                        <label for="comment" class="block text-gray-700 font-bold mb-2">{{ __('Comment') }}</label>
                        <textarea id="comment" class="form-textarea rounded-md shadow-sm w-full" name="comment" rows="4" required></textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <x-primary-button type="submit">
                            {{ __('Submit Comment') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        @endauth

        <!-- Mostrar comentarios -->
        <div class="mt-8">
            <h2>{{ __('Comments') }}</h2>
            <ul>
                @foreach($post->comments as $comment)
                    <div class="mt-4 border p-4">
                        <div>
                            <strong>User:</strong> {{ $comment->user }}
                        </div>
                        <div>
                            <strong>Comment:</strong> {{ $comment->comment }}
                        </div>
                        @auth
                            @if($comment->user_id === auth()->id())
                                <form method="POST" action="{{ route('comments.destroy', ['post' => $post, 'comment' => $comment]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">{{ __('Delete Comment') }}</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </ul>
        </div>
    @endsection
</x-columns>
@endsection
