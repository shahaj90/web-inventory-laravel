@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<ul class="breadcrumb">
    <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="{{URL::to('/dashboard')}}">Home</a>
    </li>
    <li class="active">Dashboard</li>
</ul>
@endsection

@section('pageHeader')
<h1>Dashboard</h1>
@endsection

@section('content')
<h1>Dashboard Page</h1>
@endsection