!<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table">
                        <thead>
                            <tr>
                                <td scope="col">ID</td>
                                <td scope="col">Body</td>
                                <td scope="col">file_id</td>
                                <td scope="col">latitude</td>
                                <td scope="col">longitude</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <td>{{ $post->id }}</td>
                                <td>{{ $post->Body }}</td>
                                <td><img class="img-fluid" src='{{ asset("storage/{$post->file->filepath}") }}' /></td>
                                <td>{{ $post->latitude }}</td>
                                <td>{{ $post->longitude }}</td>
                                <td><a href="{{ route('posts.edit', $post->id) }}" >Edit</a></td>
                                <form method="post" action="{{ route('posts.destroy', $post->id) }}">
                                    @method('DELETE')
                                    @csrf
                                    <td><button type="submit" class="btn btn-danger">Destroy</button></td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <td><a href="{{ route('posts.create') }}" class="btn btn-primary">Create</a></td>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>