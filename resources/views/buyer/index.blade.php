@extends('buyer.master')

@section('title', 'Dashboard Buyer')

@section('sidebar')
    @include('buyer.sidebar')
@endsection

@section('page-title', 'Dashboard Buyer')

@section('main')
    @include('buyer.dashboard')
    {{-- @include('dashboard.profile-modal') --}}
@endsection
