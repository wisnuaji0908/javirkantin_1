@extends('admin.master')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Admin')

@section('content')
    @include('admin.main')
    @include('admin.dashboard')
@endsection
