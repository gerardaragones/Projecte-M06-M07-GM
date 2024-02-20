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
                        <td><strong>Visibility</strong></td>
                        <td>{{ $place->visibility->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created</strong></td>
                        <td>{{ $place->created_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated</strong></td>
                        <td>{{ $place->updated_at }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-8">
                @can('update', $place)
                    <x-primary-button href="{{ route('places.edit', $place) }}">
                        {{ __('Edit') }}
                    </x-primary-button>
                @endcan

                @can('delete', $place)
                    <x-danger-button href="{{ route('places.delete', $place) }}">
                        {{ __('Delete') }}
                    </x-danger-button>
                @endcan

                <div class="mt-8">
                    @auth <!-- Mostrar formulario de revisión solo si el usuario está autenticado -->
                        <h2>{{ __('Write a Review') }}</h2>
                        <form method="POST" action="{{ route('reviews.store', $place) }}">
                            @csrf

                            <div class="mb-4">
                                <label for="rating" class="block text-gray-700 font-bold mb-2">{{ __('Rating (1-10)') }}</label>
                                <input id="rating" type="number" class="form-input rounded-md shadow-sm w-full" name="rating" min="1" max="10" required>
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="block text-gray-700 font-bold mb-2">{{ __('Comment') }}</label>
                                <textarea id="comment" class="form-textarea rounded-md shadow-sm w-full" name="comment" rows="4" required></textarea>
                            </div>

                            <input type="hidden" name="place_id" value="{{ $place->id }}">

                            <div class="flex items-center justify-end">
                                <x-primary-button type="submit">
                                    {{ __('Submit Review') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endauth

                    @can('viewAny', App\Models\Place::class)
                        <x-secondary-button href="{{ route('places.index') }}">
                            {{ __('Back to list') }}
                        </x-secondary-button>
                    @endcan
                </div>
            </div>

            <div class="mt-8">
                <p>{{ $numFavs . " " . __('favorites') }}</p>
                @include('partials.buttons-favs')
            </div>

            <!-- Aquí mostramos las revisiones -->
            <div class="mt-8">
                <h2>{{ __('Reviews') }}</h2>
                <ul>
                @foreach($place->reviews as $review)
                    <div class="mt-4 border p-4">
                        <div>
                            <strong>Rating:</strong> {{ $review->rating }}
                        </div>
                        <div>
                            <strong>Comment:</strong> {{ $review->comment }}
                        </div>
                        @auth
                            @if($review->user_id === auth()->id())
                                <form method="POST" action="{{ route('places.reviews.destroy', ['place' => $place, 'review' => $review]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">{{ __('Delete Review') }}</button>
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
