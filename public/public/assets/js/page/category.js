
//Read categories
function readCategories() {
    var table = $('#categoryTable').DataTable({
        ajax: baseUrl + 'category/read',
        destroy: true,
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'description'},
            {
                data: 'created_at',
                sorting: false,
                filtering: false,
                render: function (data, type, row, meta) {
                    return "\
                            <button class='btn btn-xs btn-success' onclick='showCategoryModal(" + row.id + ")'><i class='ace-icon fa fa-pencil bigger-120'></i></button>\n\
                            <button class='btn btn-xs btn-danger' onclick='deleteCategory(" + row.id + ")'><i class='ace-icon fa fa-trash-o bigger-120'></i></button>\n\
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

    //Column show/hide
    if (user == 'admin') {
        //show action column
    } else {
        table.column(3).visible(false);
    }
}

//Hide message div
$('#errorMessageShow').hide();
$('#successMessageShow').hide();
function showCategoryModal(type) {
    //Hide buttons
    $("#categoryForm")[0].reset();
    $('#saveButton').hide();
    $('#updateButton').hide();
    //Update operation
    if (type) {
        //Get edit data
        $.ajax({
            url: baseUrl + "category/edit",
            type: 'POST',
            data: {id: type},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == "success") {
                //Value assign
                var data = response.data;
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('textarea#description').val(data.description);
                //Modal open
                $('#updateButton').show();
                $("#categoryModal").modal({
                    backdrop: 'static'
                });
            } else {
                swal('Error!', response.message, response.status);
            }
        })
    } else {
        //Save operation
        $('#saveButton').show();
        $("#categoryModal").modal({
            backdrop: 'static'
        });
    }

}

function saveCategory() {
    $('#categoryForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "category/save",
                type: 'POST',
                data: $("#categoryForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $('#successMessage').html(response.message);
                    $('#successMessageShow').show();
                    setTimeout(function () {
                        $('#successMessageShow').hide();
                    }, 3000);
                    //Reset form
                    $("#categoryForm")[0].reset();
                    //Reload table
                    readCategories();
                } else {
                    $('#errorMessage').html(response.message);
                    $('#errorMessageShow').show();
                    setTimeout(function () {
                        $('#errorMessageShow').hide();
                    }, 3000);
                }
            });
        }
    })
}

function updateCategory() {
    $('#categoryForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "category/update",
                type: 'POST',
                data: $("#categoryForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $("#categoryModal").modal("hide");
                    //Reload table
                    readCategories();
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

function deleteCategory(id) {
    swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then(function () {
        $.ajax({
            url: baseUrl + "category/delete",
            type: 'POST',
            data: {id: id},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == "success") {
                //Reload table
                readCategories();
                swal('Done!', response.message, response.status);
            } else {
                swal('Error!', response.message, response.status);
            }
        })
    });
}
