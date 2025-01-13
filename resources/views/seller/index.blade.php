@extends('seller.master')

@section('title', 'Dashboard seller')

@section('sidebar')
    @include('seller.sidebar')
@endsection

@section('page-title', 'Dashboard seller')

@section('main')
    @include('seller.dashboard')
    {{-- @include('dashboard.profile-modal') --}}
@endsection
