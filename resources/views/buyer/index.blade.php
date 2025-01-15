@extends('buyer.master')

@section('title', 'Dashboard Buyer')

@section('page-title', 'Dashboard Buyer')

@section('content')
    @include('buyer.main')
    @include('buyer.dashboard')
@endsection
