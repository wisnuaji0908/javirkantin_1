@extends('seller.master')

@section('title', 'Dashboard seller')

@section('page-title', 'Dashboard seller')

@section('content')
    @include('seller.main')
    @include('seller.dashboard')
@endsection
