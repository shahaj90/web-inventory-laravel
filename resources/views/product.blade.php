@extends('layouts.app')

@section('title', 'Product')

@section('breadcrumb')
<ul class="breadcrumb">
    <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="{{URL::to('/dashboard')}}">Home</a>
    </li>
    <li class="active">Product</li>
</ul>
@endsection

@section('pageHeader')
<h1>Product</h1>
@endsection

@section('content')
<div class="col-sm-12">
    <div class="tabbable" id="tabs">
        <ul class="nav nav-tabs padding-12 tab-color-blue background-blue">
            <li class="active">
                <a data-toggle="tab" href="#product">New Product</a>
            </li>
            <li>
                <a data-toggle="tab" href="#purchase" onclick="purchaseView()">Purchase</a>
            </li>
        </ul>

        <div class="tab-content">
            <!--Product Data-->
            <div id="product" class="tab-pane in active">
                <!-- Modal -->
                <div class="modal fade" id="productModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add/Edit Product</h4>
                            </div>
                            <div class="modal-body">
                                <!--Error message-->
                                <div class="alert alert-danger" id="errorMessageShow">
                                    <strong id="message"></strong>
                                </div>
                                <!--Success message-->
                                <div class="alert alert-success" id="successMessageShow">
                                    <strong id="successMessage"></strong>
                                </div>
                                <form class="form-horizontal" role="form" id="productForm">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Name </label>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="id" id="id" />
                                            <input type="text" name="name" id="name" placeholder="Product Name" class="col-xs-10 col-sm-10" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Category </label>
                                        <div class="col-sm-9">
                                            <select class="col-xs-10 col-sm-10" id="categoryName" name="categoryName" required>
                                                <option value="">-----Select-----</option>
                                                @foreach ($catData as $cat)
                                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Description </label>
                                        <div class="col-sm-9">
                                            <textarea id="description" name="description" placeholder="Description" class="col-xs-10 col-sm-10"/></textarea>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-4 col-md-9">
                                            <button class="btn btn-success" type="submit" id="saveButton" onclick="saveProduct()">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save
                                            </button>
                                            <button class="btn btn-success" type="submit" id="updateButton" onclick="updateProduct()">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Update
                                            </button>
                                            <button class="btn btn-danger" data-dismiss="modal">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!--Data Table-->
                @if(Auth::User()->is_admin())
                <div> 
                    <button class="btn btn-sm btn-danger" onclick="showProductModal()">
                        <i class="ace-icon glyphicon-plus bigger-110"></i><span class="bigger-110 no-text-shadow">Add New</span>
                    </button>

                </div>
                @endif
                <div>
                    <table id="productTable" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sl#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <!--Purchase Data-->  
            <div id="purchase" class="tab-pane">
                <!-- Modal -->
                <div class="modal fade" id="purchaseModal" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Add/Edit Purchase</h4>
                            </div>
                            <div class="modal-body">
                                <!--Message-->
                                <div class="alert" id="purchaseMessageShow">
                                    <strong id="purchaseMessage"></strong>
                                </div>
                                <!--Form-->
                                <form class="form-horizontal" role="form" id="purchaseForm">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Date </label>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="id" id="purchaseId">
                                            <input class="col-xs-10 col-sm-10" id="date-picker" name="date" type="text" data-date-format="dd-mm-yyyy" required/>
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Product </label>
                                        <div class="col-sm-9">
                                            <select class="col-xs-10 col-sm-10" id="productName" name="productName" onchange="getProductCat()" required/>
                                            <option value="">-----Select-----</option>
                                            @foreach ($productData as $product)
                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Category </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="catName" id="catName" placeholder="Category Name" class="col-xs-10 col-sm-10" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Unit Price </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="unitPrice" id="unitPrice" placeholder="Unit Price" class="col-xs-10 col-sm-10" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Qty </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="qty" id="qty" placeholder="Quantity" class="col-xs-10 col-sm-10" required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Total </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="total" id="total" placeholder="0" class="col-xs-10 col-sm-10" disabled/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1"> Description </label>
                                        <div class="col-sm-9">
                                            <textarea id="description" name="description" placeholder="Description" class="col-xs-10 col-sm-10"/></textarea>
                                        </div>
                                    </div>
                                    <div class="space-4"></div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-4 col-md-9">
                                            <button class="btn btn-success" type="submit" id="savePurchaseButton" onclick="savePurchase()">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save
                                            </button>
                                            <button class="btn btn-success" type="submit" id="updatePurchaseButton" onclick="updatePurchase()">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Update
                                            </button>
                                            <button class="btn btn-danger" data-dismiss="modal">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <!--Add button-->
                @if(Auth::User()->is_admin())
                <div> 
                    <button class="btn btn-sm btn-warning" onclick="showPurchaseModal()">
                        <i class="ace-icon glyphicon-plus bigger-110"></i><span class="bigger-110 no-text-shadow">Add New</span>
                    </button>

                </div>
                @endif
                <table id="purchaseTable" class="table table-striped table-bordered table-hover" style="width: false;">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('pageScript')
<script src="public/assets/js/page/product.js"></script>
<script src="public/assets/js/page/purchase.js"></script>
<script>
                        $(document).ready(function () {
                            //Read product Tb
                            readProducts();
                            //datapicker
                            $('#date-picker').datepicker({
                                autoclose: true,
                                todayHighlight: true
                            })
                                    //show datepicker when clicking on the icon
                                    .next().on(ace.click_event, function () {
                                $(this).prev().focus();
                            });
                        });
</script>
@endsection