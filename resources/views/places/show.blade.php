@extends('layouts.box-app')

@section('box-title')
    {{ __('Place') . " " . $place->id }}
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
                    <td>{{ $place->id }}</td>
                </tr>
                <tr>
                    <td><strong>Name</strong></td>
                    <td>{{ $place->name }}</td>
                </tr>
                <tr>
                    <td><strong>Description</strong></td>
                    <td>{{ $place->description }}</td>
                </tr>
                <tr>
                    <td><strong>Lat</strong></td>
                    <td>{{ $place->latitude }}</td>
                </tr>
                <tr>
                    <td><strong>Lng</strong></td>
                    <td>{{ $place->longitude }}</td>
                </tr>
                <tr>
                    <td><strong>Author</strong></td>
                    <td>{{ $author->name }}</td>
                </tr>
                <tr>
                    <td><strong>Created</strong></td>
                    <td>{{ $place->created_at }}</td>
                </tr>
                <tr>
                    <td><strong>Updated</strong></td>
                    <td>{{ $place->updated_at }}</td>
                </tr>
                <tr>
                    <td><strong>Favorites</strong></td>
                    <td>{{ $place->favorited_count }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        @if($userFavoritedPlace)
                            <form action="{{ route('places.unfavorite', $place) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white p-2 rounded">{{ __('Unfavorite') }}</button>
                            </form>
                        @else
                            <form action="{{ route('places.favorite', $place) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-500 text-white p-2 rounded">{{ __('Favorite') }}</button>
                            </form>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-8">
            <x-primary-button href="{{ route('places.edit', $place) }}">
                {{ __('Edit') }}
            </x-danger-button>
            <x-danger-button href="{{ route('places.delete', $place) }}">
                {{ __('Delete') }}
            </x-danger-button>
            <x-secondary-button href="{{ route('places.index') }}">
                {{ __('Back to list') }}
            </x-secondary-button>
        </div>
    @endsection
</x-columns>
@endsection
