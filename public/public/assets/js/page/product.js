
function readProducts() {
    var table = $('#productTable').DataTable({
        ajax: baseUrl + 'product/read',
        destroy: true,
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'cat_name'},
            {data: 'description'},
            {
                data: 'created_at',
                sorting: false,
                filtering: false,
                render: function (data, type, row, meta) {
                    return "\
                            <button class='btn btn-xs btn-success' onclick='showProductModal(" + row.id + ")'><i class='ace-icon fa fa-pencil bigger-120'></i></button>\n\
                            <button class='btn btn-xs btn-danger' onclick='deleteProduct(" + row.id + ")'><i class='ace-icon fa fa-trash-o bigger-120'></i></button>\n\
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
        table.column(4).visible(false);
    }
}

//Hide message div
$('#errorMessageShow').hide();
$('#successMessageShow').hide();
function showProductModal(type) {
    //Hide buttons
    $("#productForm")[0].reset();
    $('#saveButton').hide();
    $('#updateButton').hide();
    //Update operation
    if (type) {
        //Get edit data
        $.ajax({
            url: baseUrl + "product/edit",
            type: 'POST',
            data: {id: type},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == "success") {
                //Value assign
                var data = response.data;
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#categoryName').val(data.categories_id);
                $('textarea#description').val(data.description);
                //Modal open
                $('#updateButton').show();
                $("#productModal").modal({
                    backdrop: 'static'
                });
            } else {
                swal('Error!', response.message, response.status);
            }
        });
    } else {
        //Open modal
        $('#saveButton').show();
        $("#productModal").modal({
            backdrop: 'static'
        });
    }

}

function saveProduct() {
    $('#productForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "product/save",
                type: 'POST',
                data: $("#productForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $('#successMessage').html(response.message);
                    $('#successMessageShow').show();
                    setTimeout(function () {
                        $('#successMessageShow').hide();
                    }, 3000);
                    //Reset form
                    $("#productForm")[0].reset();
                    //Reload table
                    readProducts();
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

function updateProduct() {
    $('#productForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "product/update",
                type: 'POST',
                data: $("#productForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $("#productModal").modal("hide");
                    //Reload table
                    readProducts();
                    swal('Updated!', response.message, response.status);
                } else {
                    $('#message').html(response.message);
                    $('#messageShow').show();
                    setTimeout(function () {
                        $('#messageShow').hide();
                    }, 3000);
                }
            });
        }
    });


}

function deleteProduct(id) {
    swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then(function () {
        $.ajax({
            url: baseUrl + "product/delete",
            type: 'POST',
            data: {id: id},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == "success") {
                //Reload table
                readProducts();
                swal('Deleted!', response.message, response.status);
            } else {
                swal('Error!', response.message, response.status);
            }
        })
    });
}