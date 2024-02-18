@extends('layouts.box-app')

@section('box-title')
    {{ __('Review') . " " . $review->id }}
@endsection

@section('box-content')
<x-columns columns=2>
    @section('column-1')
        <!-- Aquí puedes mostrar cualquier contenido relacionado con la reseña -->
        <p>{{ __('Review ID') }}: {{ $review->id }}</p>
        <!-- Puedes añadir más campos si es necesario -->
    @endsection
    @section('column-2')
        <table class="table">
            <tbody>
                <tr>
                    <td><strong>ID<strong></td>
                    <td>{{ $review->id }}</td>
                </tr>
                <tr>
                    <td><strong>Content</strong></td>
                    <td>{{ $review->content }}</td>
                </tr>
                <tr>
                    <td><strong>Created</strong></td>
                    <td>{{ $review->created_at }}</td>
                </tr>
                <tr>
                    <td><strong>Updated</strong></td>
                    <td>{{ $review->updated_at }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-8">
            <!-- Aquí puedes añadir botones de edición, eliminación, etc., si es necesario -->
        </div>
    @endsection
</x-columns>
@endsection
