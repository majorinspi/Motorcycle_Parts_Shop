function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}

$('#addTransactionForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'transactions/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addTransactionForm')[0].reset();
                showToast('success', 'Transaction added successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1000); 
            } else {
                showToast('error', response.message || 'Failed to add transaction.');
            }
        },
        error: function () {
            showToast('error', 'An error occurred.');
        }
    });
});

$(document).on('click', '.edit-btn', function () {
   const transactionId = $(this).data('transaction_id'); 
   $.ajax({
    url: baseUrl + 'transactions/edit/' + transactionId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response.data) {
            $('#editTransactionModal #product_id').val(response.data.product_id);
            $('#editTransactionModal #transaction_id').val(response.data.transaction_id);
            $('#editTransactionModal #type').val(response.data.type);
            $('#editTransactionModal #quantity').val(response.data.quantity);
            $('#editTransactionModal #date').val(response.data.date);

            
            $('#editTransactionModal').modal('show');
        } else {
            alert('Error fetching transaction data');
        }
    },
    error: function () {
        alert('Error fetching transaction data');
    }
});
});


$(document).ready(function () {
    $('#editTransactionForm').on('submit', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: baseUrl + 'transactions/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editTransactionModal').modal('hide');
                    showToast('success', 'Transaction Updated successfully!');
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

$(document).on('click', '.deleteTransactionBtn', function () {
    const transactionId = $(this).data('transaction_id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Are you sure you want to delete this transaction?')) {
        $.ajax({
            url: baseUrl + 'transactions/delete/' + transactionId,
            method: 'POST',
            data: {
                _method: 'DELETE',
                [csrfName]: csrfToken
            },
            success: function (response) {
                if (response.success) {
                    showToast('success', 'Transaction deleted successfully.');
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

    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $table.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'transactions/fetchRecords',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        },
        columns: [
        { data: 'row_number' },
        { data: 'transaction_id', visible: false },
        { data: 'product_name' },
        {
            data: 'type',
            render: function (data, type, row) {
                let badgeClass = data === 'In' ? 'badge-success' : 'badge-danger';
                return `<span class="badge ${badgeClass}">${data}</span>`;
            }
        },
        { data: 'quantity' },
        { data: 'date' },
        {

            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                <button class="btn btn-sm btn-warning edit-btn" data-transaction_id="${row.transaction_id}">
                <i class="far fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteTransactionBtn" data-transaction_id="${row.transaction_id}">
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
