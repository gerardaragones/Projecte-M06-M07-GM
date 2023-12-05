@extends('layouts.box-app')

@section('box-title')
    {{ __('File') . " " . $file->id }}
@endsection

@section('box-content')
    @can('delete', $file)
        <x-confirm-delete-form parentRoute='files' :model=$file />
    @endcan
@endsection