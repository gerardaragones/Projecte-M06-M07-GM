<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Place') }}
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
                                <td scope="col">name</td>
                                <td scope="col">description</td>
                                <td scope="col">latitude</td>
                                <td scope="col">longitude</td>
                                <td scope="col">file</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($places as $place)
                            <tr>
                                <td>{{ $place->id }}</td>
                                <td>{{ $place->name }}</td>
                                <td>{{ $place->description }}</td>
                                <td>{{ $place->latitude }}</td>
                                <td>{{ $place->longitude }}</td>
                                <td><img src="{{ asset("storage/{$place->file->filepath}") }}"/></td>
                                <td><a href="{{ route('places.edit', $places->id) }}" >Edit</a></td>
                                <form method="post" action="{{ route('places.destroy', $places->id) }}">
                                    @method('DELETE')
                                    @csrf
                                    <td><button type="submit" class="btn btn-danger">Destroy</button></td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <td><a href="{{ route('places.create') }}" class="btn btn-primary">Create</a></td>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>