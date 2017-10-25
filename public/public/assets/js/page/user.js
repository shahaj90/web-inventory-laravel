
function readUsers() {
    $('#userTable').DataTable({
        ajax: baseUrl + 'user/read',
        destroy: true,
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'email'},
            {data: 'mobile'},
            {
                data: 'type',
                render: function (data, type, row, meta) {
                    if (data == 1) {
                        return 'Admin';
                    } else if (data == 2) {
                        return 'Manager';
                    } else if (data == 3) {
                        return 'User';
                    }
                }
            },
            {
                data: 'is_active',
                render: function (data, type, row, meta) {
                    if (data == 0) {
                        return 'Not Approved';
                    } else if (data == 1) {
                        return 'Active';
                    } else if (data == 2) {
                        return 'Deactive';
                    } else if (data == 3) {
                        return 'Suspend';
                    }
                }
            },
            {
                data: 'created_at',
                sorting: false,
                filtering: false,
                render: function (data, type, row, meta) {
                    return "\
                            <button class='btn btn-xs btn-success' onclick='showUserModal(" + row.id + ")'><i class='ace-icon fa fa-pencil bigger-120'></i></button>\n\
                            <button class='btn btn-xs btn-danger' onclick='deleteUser(" + row.id + ")'><i class='ace-icon fa fa-trash-o bigger-120'></i></button>\n\
                            ";
                }
            }
        ],

        //Table serial
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            $("td:first", nRow).html(iDisplayIndex + 1);
            return nRow;
        }

    });
}

function showUserModal(type) {
    //Hide buttons
    $("#userForm")[0].reset();
    $('#saveButton').hide();
    $('#updateButton').hide();
    document.getElementById("password").required = true;
    document.getElementById("rePassword").required = true;
    //Update operation
    if (type) {
        //Get edit data
        $.ajax({
            url: baseUrl + "user/edit",
            type: 'POST',
            data: {id: type},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == "success") {
                //Value assign
                var data = response.data;
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#mobile').val(data.mobile);
//                $('#password').val(data.password);
//                $('#repassword').val(data.password);
                $('#type').val(data.type);
                $('#status').val(data.is_active);
                //Open modal
                $('#updateButton').show();
                document.getElementById("password").required = false;
                document.getElementById("rePassword").required = false;
                $('#password').val();
                $("#userModal").modal({
                    backdrop: 'static'
                });
            } else {
                swal('Error!', response.message, response.status);
            }
        });
    } else {
        //Save operation
        $('#saveButton').show();
        $("#userModal").modal({
            backdrop: 'static'
        });
    }

}

$('#messageShow').hide();
function saveUser() {
    $('#userForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "user/save",
                type: 'POST',
                data: $("#userForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $("#userModal").modal("hide");
                    //Reload table
                    readUsers();
                    swal('Done!', response.message, response.status);
                } else {
                    $('#message').html(response.message);
                    $('#messageShow').show();
                    setTimeout(function () {
                        $('#messageShow').hide();
                    }, 3000);
                }
            });
        }
    })
}

function updateUser() {
    $('#userForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "user/update",
                type: 'POST',
                data: $("#userForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $("#userModal").modal("hide");
                    //Reload table
                    readUsers();
                    swal('Done!', response.message, response.status);
                } else {
                    $('#message').html(response.message);
                    $('#messageShow').show();
                    setTimeout(function () {
                        $('#messageShow').hide();
                    }, 3000);
                }
            });
        }
    })


}

function deleteUser(id) {
    swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then(function () {
        $.ajax({
            url: baseUrl + "user/delete",
            type: 'POST',
            data: {id: id},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == "success") {
                //Reload table
                readUsers();
                swal('Done!', response.message, response.status);
            } else {
                swal('Error!', response.message, response.status);
            }
        })
    });
}
