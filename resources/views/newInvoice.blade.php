@extends('layouts.app')

@section('title', 'New Invoice')

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{URL::to('/dashboard')}}">Home</a>
        </li>
        <li class="active">New Invoice</li>
    </ul>
@endsection

@section('pageHeader')
    <h1>New Invoice</h1>
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

    <form action="{{url('createInvoice')}}" method="POST">
        {{ csrf_field() }}
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Customer Information </h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    Mobile
                    <input type="hidden" name="cusId" id="cusId" class="form-control">
                    <input type="text" name="mobile" id="mobile" class="form-control" required>
                </div>
                <div class="form-group">
                    Name
                    <input type="text" name="name" id="name" onclick="getCustomer()" class="form-control" required>
                </div>
                <div class="form-group">
                    Address
                    <textarea name="address" id="address" class="form-control" required></textarea>
                </div>
            </div>
        </div>
        <br/>
        <div class="checkbox">
            <label><input type="checkbox" name="status">Unpaid</label>
        </div>
        <br/>

        <table class="table table-bordered table-hover">
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
                <td class="no">1</td>
                <td>
                    <select class="form-control product" id="pro_1" name="product[]" required>
                        <option value="">-----Select-----</option>
                        @foreach ($products as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control description" name="description[]"></td>
                <td><input type="number" min="1" class="form-control quantity" onkeyup="checkStock(1)" onblur="checkStock(1)" id="qty_1" name="quantity[]" required></td>
                <td><input type="text" class="form-control price" name="price[]" required></td>
                <td><input type="text" class="form-control discount" name="discount[]"></td>
                <td><input type="text" class="form-control amount" id="amount_1" name="amount[]" disabled></td>
                <td><a href="#" class="remove">Delete</td>
            </tr>
            </tbody>
            <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="2" style="text-align:center;" class="total">0<b></b></th>
            </tfoot>

        </table>
        <button type="submit"> Create</button>
    </form>

@endsection

@section('pageScript')
    <script src="{{asset('public/assets/js/page/invoice.js')}}"></script>
@endsection