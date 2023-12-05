@extends('layouts.box-app')

@section('box-title')
    {{ __('Place') . " " . $place->id }}
@endsection

@section('box-content')
    @can('delete', App\Models\Places::class)
        <x-confirm-delete-form parentRoute='places' :model=$place />
    @endcan
    @can('forceDelete', App\Models\Places::class)
        <x-confirm-delete-form parentRoute='places' :model=$place />
    @endcan
@endsection