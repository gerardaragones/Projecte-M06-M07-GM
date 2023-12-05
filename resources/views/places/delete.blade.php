@extends('layouts.box-app')

@section('box-title')
    {{ __('Place') . " " . $place->id }}
@endsection

@section('box-content')
    @can('delete', $place)
        <x-confirm-delete-form parentRoute='places' :model=$place />
    @endcan
@endsection