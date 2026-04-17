function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}

$('#addCustomerForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'customers/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addCustomerForm')[0].reset();
                showToast('success', 'Customer added successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1000); 
            } else {
                showToast('error', response.message || 'Failed to add customer.');
            }
        },
        error: function () {
            showToast('error', 'An error occurred.');
        }
    });
});

$(document).on('click', '.edit-btn', function () {
   const customerId = $(this).data('id'); 
   $.ajax({
    url: baseUrl + 'customers/edit/' + customerId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response.data) {
            $('#editCustomerModal #customer_name').val(response.data.customer_name);
            $('#editCustomerModal #customerId').val(response.data.customer_id);
            $('#editCustomerModal #contact_number').val(response.data.contact_number);
            $('#editCustomerModal #address').val(response.data.address);
            $('#editCustomerModal #email').val(response.data.email);
            $('#editCustomerModal').modal('show');
        } else {
            alert('Error fetching customer data');
        }
    },
    error: function () {
        alert('Error fetching user data');
    }
});
});


$(document).ready(function () {
    $('#editCustomerForm').on('submit', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: baseUrl + 'customers/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editCustomerModal').modal('hide');
                    showToast('success', 'Customer Updated successfully!');
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

$(document).on('click', '.deleteCustomerBtn', function () {
    const customerId = $(this).data('id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Are you sure you want to delete this customer?')) {
        $.ajax({
            url: baseUrl + 'customers/delete/' + customerId,
            method: 'POST', 
            data: {
                _method: 'DELETE',
                [csrfName]: csrfToken
            },
            success: function (response) {
                if (response.success) {
                    showToast('success', 'Customers deleted successfully.');
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
            url: baseUrl + 'customers/fetchRecords',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        },
        columns: [
        { data: 'row_number' },
        { data: 'customer_id', visible: false },
        { data: 'customer_name' },
        { data: 'contact_number' },
        { data: 'address' },
        { data: 'email' },
        { data: 'created_at' },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                <button class="btn btn-sm btn-warning edit-btn" data-id="${row.customer_id}">
                <i class="far fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteCustomerBtn" data-id="${row.customer_id}">
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