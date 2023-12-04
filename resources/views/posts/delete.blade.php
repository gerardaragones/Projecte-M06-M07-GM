@extends('layouts.box-app')

@section('box-title')
    {{ __('Post') . " " . $post->id }}
@endsection

@section('box-content')
    @can('delete', App\Models\Post::class)
        <x-confirm-delete-form parentRoute='posts' :model=$post />
    @endcan
    @can('forceDelete', App\Models\Post::class)
        <x-confirm-delete-form parentRoute='posts' :model=$post />
    @endcan
@endsection