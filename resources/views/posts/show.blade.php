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
                        <td><strong>Created</strong></td>
                        <td>{{ $post->created_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated</strong></td>
                        <td>{{ $post->updated_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Likes</strong></td>
                        <td>{{ $post->liked_count }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            @can('like', App\Models\Post::class)
                                @if($userLikedPost)
                                    <form action="{{ route('posts.unlike', $post) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white p-2 rounded">{{ __('Unlike') }}</button>
                                    </form>
                                @else
                                    <form action="{{ route('posts.like', $post) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 text-white p-2 rounded">{{ __('Like') }}</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-8">
                @can('update', App\Models\Post::class)
                    <x-primary-button href="{{ route('posts.edit', $post) }}">
                        {{ __('Edit') }}
                    </x-danger-button>
                @endcan
                @can('delete', App\Models\Post::class)
                    <x-confirm-delete-form parentRoute='posts' :model=$post />
                @endcan
                @can('forceDelete', App\Models\Post::class)
                    <x-confirm-delete-form parentRoute='posts' :model=$post />
                @endcan
                <x-secondary-button href="{{ route('posts.index') }}">
                    {{ __('Back to list') }}
                </x-secondary-button>
            </div>
        @endsection
    </x-columns>
    @endsection