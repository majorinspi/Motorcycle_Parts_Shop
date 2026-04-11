function showToast(type, message) {
    if (type === 'success') {
        toastr.success(message, 'Success');
    } else {
        toastr.error(message, 'Error');
    }
}

$('#addCategoryForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'categories/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addCategoryForm')[0].reset();
                showToast('success', 'Category added successfully!');
                setTimeout(() => {
                    location.reload();
                }, 1000); 
            } else {
                showToast('error', response.message || 'Failed to add category.');
            }
        },
        error: function () {
            showToast('error', 'An error occurred.');
        }
    });
});

$(document).on('click', '.edit-btn', function () {
   const categoryId = $(this).data('category_id'); 
   $.ajax({
    url: baseUrl + 'categories/edit/' + categoryId,
    method: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response.data) {
            $('#editCategoryModal #category_name').val(response.data.category_name);
            $('#editCategoryModal #category_id').val(response.data.category_id);
            $('#editCategoryModal').modal('show');
        } else {
            alert('Error fetching category data');
        }
    },
    error: function () {
        alert('Error fetching category data');
    }
});
});

$(document).on('click', '.category-name-btn', function (e) {
    e.preventDefault();
    const categoryId = $(this).data('category_id');
    window.location.href = baseUrl + 'products?category_id=' + categoryId;
});


$(document).ready(function () {
    $('#editCategoryForm').on('submit', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: baseUrl + 'categories/update',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#editCategoryModal').modal('hide');
                    showToast('success', 'Category Updated successfully!');
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

$(document).on('click', '.deleteCategoryBtn', function () {
    const categoryId = $(this).data('category_id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    if (confirm('Are you sure you want to delete this category?')) {
        $.ajax({
            url: baseUrl + 'categories/delete/' + categoryId,
            method: 'POST',
            data: {
                _method: 'DELETE',
                [csrfName]: csrfToken
            },
            success: function (response) {
                if (response.success) {
                    showToast('success', 'Category deleted successfully.');
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
            url: baseUrl + 'categories/fetchRecords',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        },
        columns: [
        { data: 'row_number' },
        { data: 'category_id', visible: false },
        {
            data: 'category_name',
            render: function (data, type, row) {
                return `<button class="btn btn-sm btn-primary category-name-btn" data-category_id="${row.category_id}">
                    ${data}
                </button>`;
            }
        },
        {

            data: null,
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
                return `
                <button class="btn btn-sm btn-warning edit-btn" data-category_id="${row.category_id}">
                <i class="far fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger deleteCategoryBtn" data-category_id="${row.category_id}">
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
