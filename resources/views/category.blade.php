@extends('layouts.app')

@section('title', 'Categories')

@section('breadcrumb')
<ul class="breadcrumb">
    <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="{{URL::to('/dashboard')}}">Home</a>
    </li>
    <li class="active">Categories</li>
</ul>
@endsection

@section('pageHeader')
<h1>Categories</h1>
@endsection

@section('content')
<!-- Modal -->
<div class="modal fade" id="categoryModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add/Edit Category</h4>
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
                <form class="form-horizontal" role="form" id="categoryForm">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Name </label>
                        <div class="col-sm-9">
                            <input type="hidden" name="id" id="id" />
                            <input type="text" name="name" id="name" placeholder="Category Name" class="col-xs-10 col-sm-10" required/>
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
                            <button class="btn btn-success" type="submit" id="saveButton" onclick="saveCategory('save')">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Save
                            </button>
                            <button class="btn btn-success" type="submit" id="updateButton" onclick="updateCategory('update')">
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

@if(Auth::User()->is_admin())
<div> 
    <button class="btn btn-sm btn-danger" onclick="showCategoryModal()">
        <i class="ace-icon glyphicon-plus bigger-110"></i><span class="bigger-110 no-text-shadow">Add New</span>
    </button>

</div>
@endif
<div>
    <table id="categoryTable" class="table table-striped table-bordered table-hover table-responsive">
        <thead>
            <tr>
                <th>Sl#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('pageScript')
<script src="public/assets/js/page/category.js"></script>
<script>
        $(document).ready(function () {
            readCategories();
        });
</script>
@endsection