@extends('layouts.app')

@section('title', 'Invoice')

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="{{URL::to('/dashboard')}}">Home</a>
        </li>
        <li class="active">Invoice</li>
    </ul>
@endsection

@section('pageHeader')
    <h1>Invoice</h1>
@endsection

@section('content')
    <div>
        <!--Success message-->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <a href="{{route('newInvoice')}}">
            <button class="btn btn-sm btn-danger">
                <i class="ace-icon glyphicon-plus bigger-110"></i><span class="bigger-110 no-text-shadow">Add New</span>
            </button>
        </a>

    </div>
    <!--Datatable-->
    <div>
        <table id="invoiceTable" class="table table-striped table-bordered table-hover table-responsive">
            <thead>
            <tr>
                <th>Sl#</th>
                <th>Invoice</th>
                <th>Customer Name</th>
                <th>Mobile</th>
                <th>Amount</th>
                <th>Discount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoiceData as $data)
                <tr>
                    <td>{{$data->sl}}</td>
                    <td>{{$data->invoice_id}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->mobile}}</td>
                    <td>{{$data->amount}}</td>
                    <td>{{$data->discount}}</td>
                    <td>{{$data->status}}</td>
                    <td>
                        <!--Actions-->
                        <a href="{{URL::to('editInvoice/'.base64_encode($data->id))}}">
                            <button class='btn btn-xs btn-warning'><i class='ace-icon fa fa-pencil bigger-120'></i>
                            </button>
                        </a>
                        <a href="{{URL::to('printInvoice/'.base64_encode($data->id))}}" target="_blank">
                            <button class='btn btn-xs btn-danger'><i class='ace-icon fa fa-print bigger-120'></i>
                            </button>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('pageScript')
    <script src="public/assets/js/page/invoice.js"></script>
    <script>
        $(document).ready(function () {
            $('#invoiceTable').dataTable();
        });
    </script>
@endsection