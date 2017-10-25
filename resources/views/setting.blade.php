@extends('layouts.app')

@section('title', 'Setting')

@section('breadcrumb')
<ul class="breadcrumb">
    <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="{{URL::to('/dashboard')}}">Home</a>
    </li>
    <li class="active">Setting</li>
</ul>
@endsection
@section('pageHeader')
<h1>Company Setting</h1>
@endsection
@section('content')

<!--Message-->
@if ($errors->any())
<div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@elseif(session('success'))
<div class="alert alert-success alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session('success') }}
</div>
@elseif(session('error'))
<div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{ session('error') }}
</div>
@endif

<form class="form-horizontal" role="form" method="POST" action="{{Route('confiqSave')}}">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Name </label>
        <div class="col-sm-9">
            <input type="text" id="form-field-1" value="{{$conf_data->name}}" name="name" placeholder="Name" class="col-xs-10 col-sm-5" required=""/>
            <input type="hidden" id="form-field-1" value="{{$conf_data->id}}" name="id" class="col-xs-10 col-sm-5" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Address </label>
        <div class="col-sm-9">
            <textarea id="form-field-1" name="address" placeholder="Address" class="col-xs-10 col-sm-5" required=""/>{{$conf_data->address}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Email </label>
        <div class="col-sm-9">
            <input type="email" id="form-field-1" value="{{$conf_data->email}}" name="email" placeholder="Email" class="col-xs-10 col-sm-5" required=""/>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Mobile </label>
        <div class="col-sm-9">
            <input type="text" id="form-field-1" value="{{$conf_data->mobile}}" name="mobile" placeholder="Mobile" class="col-xs-10 col-sm-5" required=""/>
        </div>
    </div>
    <div class="space-4"></div>
    <div class="clearfix form-actions">
        <div class="col-md-offset-4 col-md-9">
            <button class="btn btn-info" type="submit">
                <i class="ace-icon fa fa-check bigger-110"></i>
                Save
            </button>
        </div>
    </div>

</form>
@endsection