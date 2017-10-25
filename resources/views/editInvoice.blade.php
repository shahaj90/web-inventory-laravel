<?php
//echo '<pre>';
//print_r($data['customer']);
//echo '</pre>';
//exit;
?>


@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('breadcrumb')
<ul class="breadcrumb">
    <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="{{URL::to('/dashboard')}}">Home</a>
    </li>
    <li class="active">Edit Invoice</li>
</ul>
@endsection

@section('pageHeader')
<h1>Edit Invoice</h1>
@endsection

@section('content')

<!--Validation message-->
<div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
<!--Error message-->
@if (session('status'))
<div class="alert alert-danger">
    {{ session('status') }}
</div>
@endif

<form action="{{url('updateInvoice')}}" method="POST">
    {{ csrf_field() }}
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Customer Information </h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                Mobile
                <input type="hidden" name="cusId" id="cusId" class="form-control">
                <input type="text" name="mobile" id="mobile" value="{{$data['customer']['cus_mobile']}}" class="form-control" required>
            </div>
            <div class="form-group">
                Name
                <input type="text" name="name" id="name" value="{{$data['customer']['cus_name']}}" onclick="getCustomer()" class="form-control" required>
            </div>
            <div class="form-group">
                Address
                <textarea name="address" id="address" class="form-control" required>{{$data['customer']['cus_add']}}</textarea>
            </div>
        </div>
    </div>
    <br/>
    <div class="checkbox">
        <label><input type="checkbox" name="status" @if($data['customer']['paid_status'] != 1) checked="checked" @endif>Unpaid</label>
    </div>
    <br/>

    <table class="table table-bordered table-hover">
        <input type="hidden" name="old_order_id" value="{{$data['customer']['old_order_id']}}" class="form-control">
        <thead>
        <th>No</th>
        <th>Product Name</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Discount</th>
        <th>Amount</th>
        <th><input type="button" value="+" id="add" class="btnbtn-primary"></th>
        </thead>
        <tbody class="detail" id="inv_det">
            <tr>
                @foreach ($data['orderDetails'] as $details)
                <td class="no">{{$details['sl']}}</td>
                <td>
                    <select class="form-control product" id="pro_{{$details['sl']}}" name="product[]" required>
                        <option value="">-----Select-----</option>
                        @foreach ($data['products'] as $product)
                        <option value="{{$product['id']}}" @if($product['id'] == $details['product_id']) selected="selected" @endif>{{$product['name']}}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control description" value="{{$details['description']}}" name="description[]"></td>
                <td><input type="text" min="1" class="form-control quantity" value="{{$details['qty']}}" onkeyup="checkStock({{$details['sl']}})" onblur="checkStock({{$details['sl']}})" id="qty_{{$details['sl']}}" name="quantity[]" required></td>
                <td><input type="text" class="form-control price" value="{{$details['price']}}" name="price[]" required></td>
                <td><input type="text" class="form-control discount" value="{{$details['discount']}}" name="discount[]"></td>
                <td><input type="text" class="form-control amount" value="{{$details['amount']}}" id="amount_{{$details['sl']}}" name="amount[]" disabled></td>
                <td><a href="#" class="remove">Delete</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th colspan="2" style="text-align:center;" class="total">{{$data['total']}}<b></b></th>
        </tfoot>

    </table>
    <button type="submit"> Update</button>
</form>

@endsection

@section('pageScript')
<script src="{{asset('public/assets/js/page/invoice.js')}}"></script>
@endsection