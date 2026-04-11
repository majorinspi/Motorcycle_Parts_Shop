function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}

$('#addSupplierForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'suppliers/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addSupplierForm')[0].reset();
                showToast('success', 'Supplier added successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1000); 
            } else {
                showToast('error', response.message || 'Failed to add supplier.');
            }
        },
        error: function () {
            showToast('error', 'An error occurred.');
        }
    });
});

$(document).on('click', '.edit-btn', function () {
   const supplierId = $(this).data('supplier_id'); 
   $.ajax({
    url: baseUrl + 'suppliers/edit/' + supplierId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response.data) {
            $('#editSupplierModal #supplier_name').val(response.data.supplier_name);
            $('#editSupplierModal #supplier_id').val(response.data.supplier_id);
            $('#editSupplierModal #contact_email').val(response.data.contact_email);

            
            $('#editSupplierModal').modal('show');
        } else {
            alert('Error fetching supplier data');
        }
    },
    error: function () {
        alert('Error fetching supplier data');
    }
});
});


$(document).ready(function () {
    $('#editSupplierForm').on('submit', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: baseUrl + 'suppliers/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editSupplierModal').modal('hide');
                    showToast('success', 'Supplier Updated successfully!');
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

$(document).on('click', '.deleteSupplierBtn', function () {
    const supplierId = $(this).data('supplier_id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Are you sure you want to delete this supplier?')) {
        $.ajax({
            url: baseUrl + 'suppliers/delete/' + supplierId,
            method: 'POST',
            data: {
                _method: 'DELETE',
                [csrfName]: csrfToken
            },
            success: function (response) {
                if (response.success) {
                    showToast('success', 'Supplier deleted successfully.');
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

    const csrfName = 'csrf_test_name'; 
    const csrfToken = $('input[name="' + csrfName + '"]').val();

    $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'suppliers/fetchRecords',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        },
        columns: [
        { data: 'row_number' },
        { data: 'supplier_id', visible: false },
        { data: 'supplier_name' },
        { data: 'contact_email' },
        {

            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                <button class="btn btn-sm btn-warning edit-btn" data-supplier_id="${row.supplier_id}">
                <i class="far fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteSupplierBtn" data-supplier_id="${row.supplier_id}">
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
