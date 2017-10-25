@extends('layouts.app')

@section('title', 'User')

@section('breadcrumb')
<ul class="breadcrumb">
    <li>
        <i class="ace-icon fa fa-home home-icon"></i>
        <a href="{{URL::to('/dashboard')}}">Home</a>
    </li>
    <li class="active">User</li>
</ul>
@endsection

@section('pageHeader')
<h1>User Management</h1>
@endsection

@section('content')

<!-- Modal -->
<div class="modal fade" id="userModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add/Edit User</h4>
            </div>
            <div class="modal-body">
                <!--Message-->
                <div class="alert alert-danger" id="messageShow">
                    <strong id="message"></strong>
                </div>
                <form class="form-horizontal" role="form" id="userForm">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Name </label>
                        <div class="col-sm-9">
                            <input type="hidden" name="id" id="id"/>
                            <input type="text" name="name" id="name" placeholder="Name" class="col-xs-10 col-sm-10" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Email </label>
                        <div class="col-sm-9">
                            <input type="email" name="email" id="email" placeholder="Email" class="col-xs-10 col-sm-10" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Mobile </label>
                        <div class="col-sm-9">
                            <input type="text" id="mobile" name="mobile" placeholder="Mobile" class="col-xs-10 col-sm-10" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Password </label>
                        <div class="col-sm-9">
                            <input type="password" id="password" name="password" placeholder="Password" class="col-xs-10 col-sm-10" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Re-password </label>
                        <div class="col-sm-9">
                            <input type="password" id="rePassword" name="rePassword" placeholder="Re password" data-match="#password" class="col-xs-10 col-sm-10" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Type </label>
                        <div class="col-sm-9">
                            <select class="col-xs-10 col-sm-10" id="type" name="type" required>
                                <option value="">-----Select-----</option>
                                <option value="<?php echo base64_encode(1) ?>">Admin</option>
                                <option value="<?php echo base64_encode(2) ?>">Manager</option>
                                <option value="<?php echo base64_encode(3) ?>">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1"> Status </label>
                        <div class="col-sm-9">
                            <select class="col-xs-10 col-sm-10" id="status" name="status" required>
                                <option value="">-----Select-----</option>
                                <option value="<?php echo base64_encode(1) ?>">Active</option>
                                <option value="<?php echo base64_encode(2) ?>">Deactive</option>
                                <option value="<?php echo base64_encode(3) ?>">Suspend</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="clearfix form-actions">
                        <div class="col-md-offset-4 col-md-9">
                            <button class="btn btn-success" type="submit" id="saveButton" onclick="saveUser('save')">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Save
                            </button>
                            <button class="btn btn-success" type="submit" id="updateButton" onclick="updateUser('update')">
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

<div> 
    <button class="btn btn-sm btn-danger" onclick="showUserModal()">
        <i class="ace-icon glyphicon-plus bigger-110"></i><span class="bigger-110 no-text-shadow">Add New</span>
    </button>

</div>
<div class="table-responsive">
    <table id="userTable" class="table table-striped table-bordered table-hover table-responsive">
        <thead>
            <tr>
                <th>Sl#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('pageScript')
<script src="public/assets/js/page/user.js"></script>
<script>
        $(document).ready(function () {
            readUsers();
        });
</script>
@endsection