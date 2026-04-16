function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}

$('#addProductsForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'products/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addProductsForm')[0].reset();
                showToast('success', 'Product added successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1000); 
            } else {
                showToast('error', response.message || 'Failed to add product.');
            }
        },
        error: function () {
            showToast('error', 'An error occurred.');
        }
    });
});

$(document).on('click', '.edit-btn', function () {
   const productId = $(this).data('product_id'); 
   $.ajax({
    url: baseUrl + 'products/edit/' + productId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response.data) {
            $('#editProductsModal #product_name').val(response.data.product_name);
            $('#editProductsModal #product_id').val(response.data.product_id);
            $('#editProductsModal #category_id').val(response.data.category_id);
            $('#editProductsModal #current_stock').val(response.data.current_stock);
            $('#editProductsModal #unit_price').val(response.data.unit_price);

            
            $('#editProductsModal').modal('show');
        } else {
            alert('Error fetching product data');
        }
    },
    error: function () {
        alert('Error fetching product data');
    }
});
});


$(document).ready(function () {
    $('#editProductsForm').on('submit', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: baseUrl + 'products/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editProductsModal').modal('hide');
                    showToast('success', 'Product Updated successfully!');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Error updating: ' + (response.message || 'Unknown error'));
                }
            },
            error: function (xhr) {
                alert('Error updating');
                console.error(xhr.responseText);
            }
        });
    });
});


$(document).on('click', '.deleteProductBtn', function () {
    const productId = $(this).data('product_id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Are you sure you want to delete this product?')) {
        $.ajax({
            url: baseUrl + 'products/delete/' + productId,
            method: 'POST',
            data: {
                _method: 'DELETE',
                [csrfName]: csrfToken
            },
            success: function (response) {
                if (response.success) {
                    showToast('success', 'Product deleted successfully.');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(response.message || 'Failed to delete.');
                }
            },
            error: function () {
                alert('Something went wrong while deleting.');
            }
        });
    }
});

$(document).ready(function () {
    const $table = $('#example1');

    const csrfName = $('meta[name="csrf-name"]').attr('content') ;
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category_id');

    $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'products/fetchRecords' + (categoryId ? '?category_id=' + categoryId : ''),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        },
       columns: [
    { data: 'row_number' },
    { data: 'product_id', visible: false },
    { data: 'product_name' },
    { data: 'category_name' },
    { data: 'current_stock' },

    // THIS IS WHERE YOU ADD ₱
    {
        data: 'unit_price',
        render: function (data) {
            return '₱ ' + parseFloat(data).toFixed(2);
        }
    },

    {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            return `
            <button class="btn btn-sm btn-warning edit-btn" data-product_id="${row.product_id}">
                <i class="far fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-danger deleteProductBtn" data-product_id="${row.product_id}">
                <i class="fas fa-trash-alt"></i>
            </button>
            `;
        }
    }
],
        responsive: true,
        autoWidth: false
    });
});
