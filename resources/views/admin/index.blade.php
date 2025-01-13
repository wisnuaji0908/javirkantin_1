@extends('admin.master')

@section('title', 'Dashboard Admin')

@section('sidebar')
    @include('admin.sidebar')
@endsection

@section('page-title', 'Dashboard Admin')

@section('main')
    @include('admin.dashboard')
    {{-- @include('dashboard.profile-modal') --}}
@endsection
