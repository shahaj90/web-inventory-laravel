function showInvoiceModal() {
    $('#invoiceModal').modal();
}

$('#add').click(function () {
    addnewRow();
});

$('body').delegate('.remove', 'click', function () {
    var tr = $(this).parent().parent();
    tr.remove();
    var qty = tr.find('.quantity').val();
    var price = tr.find('.price').val();
    var dis = tr.find('.discount').val();
    var amt = (qty * price) - (qty * price * dis) / 100;
    tr.find('.amount').val(amt);
    total();
});

$('body').delegate('.quantity,.price,.discount', 'keyup', function () {
    var tr = $(this).parent().parent();
    var qty = tr.find('.quantity').val();
    var price = tr.find('.price').val();
    var dis = tr.find('.discount').val();
//    var amt = (qty * price) - (qty * price * dis) / 100;
    var amt = (qty * price) - dis;
    tr.find('.amount').val(amt);
    total();
});

function total() {
    var t = 0;
    $('.amount').each(function (i, e) {
        var amt = $(this).val() - 0;
        t += amt;
    });
    $('.total').html(t);
}

function addnewRow() {
    var productDetails;
    var n = ($('.detail tr').length - 0) + 1;

    //Get all product
    $.ajax({
        url: baseUrl + "product/read",
        type: 'GET',
        async: false,
        dataType: 'json'
    }).done(function (response) {
        productDetails = response.data;
    });


    var tr = '<tr>' +
        '<td class="no">' + n + '</td>' +
        '<td>' +
        '<select class="form-control product" id="pro_' + n + '" name="product[]" required>' +
        '<option value="">-----Select-----</option>' +
        '</select>' +
        '</td>' +
        '<td><input type="text" class="form-control description" name="description[]"></td>' +
        '<td><input type="number" min="1" class="form-control quantity" onkeyup="checkStock(' + n + ')" onblur="checkStock(' + n + ')"  id="qty_' + n + '" name="quantity[]"></td>' +
        '<td><input type="text" class="form-control price" name="price[]"></td>' +
        '<td><input type="text" class="form-control discount" name="discount[]"></td>' +
        '<td><input type="text" class="form-control amount" id="amount_' + n + '" name="amount[]" disabled></td>' +
        '<td><a href="#" class="remove">Delete</td>' +
        '</tr>';
    $('.detail').append(tr);

    //Selectbox value
    $.each(productDetails, function (index, value) {
        $('#pro_' + n).append('<option value="' + value.id + '">' + value.name + '</option>');
    });
}

function getCustomer() {
    var mobile = $('#mobile').val();
    if (mobile) {
        $.ajax({
            url: baseUrl + "invoice/getCustomer",
            type: 'GET',
            data: {mobile: mobile},
            dataType: 'json'
        }).done(function (response) {
            if (response.status == 'success') {
                var res = response.data;
                $('#cusId').val(res.id);
                $('#mobile').val(res.mobile);
                $('#name').val(res.name);
                $('#address').val(res.address);
            } else {
                $('#cusId').val('');
                $('#name').val('');
                $('#address').val('');
            }
        });
    }
}

function checkStock(id) {
    var productId = $('#pro_' + id).val();
    var qty = $('#qty_' + id).val();

    var data = {
        productId: productId,
        qty: qty
    };

    $.ajax({
        url: baseUrl + "invoice/checkStock",
        type: 'GET',
        data: data,
        dataType: 'json'
    }).done(function (response) {
        if (response.status == 'error') {
            $('#qty_' + id).val('');
            $('#amount_' + id).val('');
            $('#qty_' + id).focus();
            swal('Stock Over!', response.message, response.status);
        }
    });
}