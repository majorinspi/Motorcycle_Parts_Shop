function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}

$('#addUserForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'teachers/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addUserForm')[0].reset();
                showToast('success', 'Teachers added successfully!');
                  setTimeout(() => {
                    location.reload();
                }, 1000); 
            } else {
                showToast('error', response.message || 'Failed to add Teachers.');
            }
        },
        error: function () {
            showToast('error', 'An error occurred.');
        }
    });
});

$(document).on('click', '.edit-btn', function () {
   const userId = $(this).data('id'); 
   $.ajax({
    url: baseUrl + 'teachers/edit/' + userId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response.data) {
            $('#editUserModal #name').val(response.data.first_name);
            $('#editUserModal #userId').val(response.data.id);
            $('#editUserModal #name').val(response.data.last_name);
             $('#editUserModal #email').val(response.data.email);
            $('#editUserModal').modal('show');
        } else {
            alert('Error fetching teacher data');
        }
    },
    error: function () {
        alert('Error fetching teacher data');
    }
});
});


$(document).ready(function () {
    $('#editUserForm').on('submit', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: baseUrl + 'teachers/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editUserModal').modal('hide');
                    showToast('success', 'Teachers Updated successfully!');
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

$(document).on('click', '.deleteUserBtn', function () {
    const userId = $(this).data('id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Are you sure you want to delete this teacher?')) {
        $.ajax({
            url: baseUrl + 'teachers/delete/' + userId,
            method: 'POST', 
            data: {
                _method: 'DELETE',
                [csrfName]: csrfToken
            },
            success: function (response) {
                if (response.success) {
                    showToast('success', 'Teachers deleted successfully.');
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
            url: baseUrl + 'teachers/fetchRecords',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        },
        columns: [
        { data: 'row_number' },
        { data: 'id', visible: false },
        { data: 'first_name' },
        { data: 'last_name' },
        { data: 'email' },
        {
            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}">
                <i class="far fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteUserBtn" data-id="${row.id}">
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
