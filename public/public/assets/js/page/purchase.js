
function readPurchases() {
    var table = $('#purchaseTable').DataTable({
        ajax: baseUrl + 'product/readPurchases',
        autoWidth: false,
        destroy: true,
        columns: [
            {data: 'id'},
            {data: 'date'},
            {data: 'product_name'},
            {data: 'cat_name'},
            {data: 'qty'},
            {data: 'unit_price'},
            {
                data: '',
                render: function (data, type, row, meta) {
                    return row.qty * row.unit_price;
                }
            },
            {data: 'description'},
            {
                data: 'created_at',
                sorting: false,
                filtering: false,
                render: function (data, type, row, meta) {
                    return "\
                            <button class='btn btn-xs btn-success' onclick='showPurchaseModal(" + row.id + ")'><i class='ace-icon fa fa-pencil bigger-120'></i></button>\n\
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
        table.column(8).visible(false);
    }
}

function purchaseView() {
    readPurchases();
}


$('#unitPrice').keyup(function () {
    totalAmount();
});

$('#qty').keyup(function () {
    totalAmount();
});

var totalAmount = function () {
    var input1 = Number($('#unitPrice').val());
    var input2 = Number($('#qty').val());
    var total = input1 * input2;

    if (isNaN(total)) {
        $('#total').val(0);
    } else {
        $('#total').val(total);
    }

};

//Hide message div
$('#purchaseMessageShow').hide();
function showPurchaseModal(type) {
    //Hide buttons
    $("#purchaseForm")[0].reset();
    $('#savePurchaseButton').hide();
    $('#updatePurchaseButton').hide();
    //Update operation
    if (type) {
        //Get edit data
        $.ajax({
            url: baseUrl + "product/editPurchase",
            type: 'POST',
            data: {id: type},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == "success") {
                //Value assign
                var data = response.data;
                $('#purchaseId').val(data.id);
                $('#date-picker').datepicker('setDate', data.date);
                $('#productName').val(data.product_id);
                $('#catName').val(data.cat_name);
                $('#unitPrice').val(data.unit_price);
                $('#qty').val(data.qty);
                $('#total').val(data.total);
                $('textarea#description').val(data.description);
                //Modal open
                $('#updatePurchaseButton').show();
                $("#purchaseModal").modal({
                    backdrop: 'static'
                });
            } else {
                swal('Error!', response.message, response.status);
            }
        });
    } else {
        //Open modal
        $('#savePurchaseButton').show();
        $("#purchaseModal").modal({
            backdrop: 'static'
        });
    }

}

function getProductCat() {
    $('#catName').val('');
    data = {
        id: $("#productName").val()
    };
    $.ajax({
        url: baseUrl + "product/getProductCat",
        type: 'POST',
        data: data,
        dataType: 'json'
    }).done(function (response) {
        if (response.status == 'success') {
            $('#catName').val(response.data.catName);
        } else {
            $('#catName').val('');
            $('#purchaseMessage').html(response.message);
            $('#purchaseMessageShow').removeClass('alert-success');
            $('#purchaseMessageShow').addClass('alert-danger');
            $('#purchaseMessageShow').show();
            setTimeout(function () {
                $('#purchaseMessageShow').hide();
            }, 3000);
        }

    });
}

function savePurchase() {
    $('#purchaseForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "product/savePurchase",
                type: 'POST',
                data: $("#purchaseForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $('#purchaseMessage').html(response.message);
                    $('#purchaseMessageShow').removeClass('alert-danger');
                    $('#purchaseMessageShow').addClass('alert-success');
                    $('#purchaseMessageShow').show();
                    setTimeout(function () {
                        $('#purchaseMessageShow').hide();
                    }, 3000);
                    //Reset form
                    $("#purchaseForm")[0].reset();
                    //Reload table
                    readPurchases();
                } else {
                    $('#purchaseMessage').html(response.message);
                    $('#purchaseMessageShow').removeClass('alert-success');
                    $('#purchaseMessageShow').addClass('alert-danger');
                    $('#purchaseMessageShow').show();
                    setTimeout(function () {
                        $('#purchaseMessageShow').hide();
                    }, 3000);
                }
            });
        }
    })
}

function updatePurchase() {
    $('#purchaseForm').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            // handle the invalid form...
        } else {
            e.preventDefault();
            $.ajax({
                url: baseUrl + "product/updatePurchase",
                type: 'POST',
                data: $("#purchaseForm").serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.status == "success") {
                    $("#purchaseModal").modal("hide");
                    //Reload table
                    readPurchases();
                    swal('Updated!', response.message, response.status);
                } else {
                    $('#purchaseMessage').html(response.message);
                    $('#purchaseMessageShow').removeClass('alert-success');
                    $('#purchaseMessageShow').addClass('alert-danger');
                    $('#purchaseMessageShow').show();
                    setTimeout(function () {
                        $('#purchaseMessageShow').hide();
                    }, 3000);
                }
            });
        }
    });


}