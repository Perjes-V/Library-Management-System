$(function () {

    // ================= CSRF =================
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ================= GLOBAL LOCK =================
    let isSubmitting = false;

    // ================= TOAST =================
    function toast(message, type = 'success') {

        let bg = type === 'success' ? 'bg-success' : 'bg-danger';

        let el = $(`
            <div class="toast align-items-center text-white ${bg} border-0 show position-fixed top-0 end-0 m-3"
                 style="z-index:9999;">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        `);

        $('body').append(el);
        setTimeout(() => el.remove(), 3000);
    }

    // ================= SHOW ERRORS =================
    function showErrors(errors) {

        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $.each(errors, function (key, value) {

            let input = $('[name="' + key + '"]');

            input.addClass('is-invalid');

            input.after(`
                <div class="invalid-feedback">${value[0]}</div>
            `);
        });
    }

    // ================= CORE REQUEST =================
    function request(options) {

        if (isSubmitting) return;

        isSubmitting = true;

        $.ajax({
            url: options.url,
            method: options.method || 'GET',
            data: options.data || {},

            success: function (res) {

                if (res.success) {
                    toast(res.message || 'Success', 'success');
                    if (options.success) options.success(res);
                } else {
                    toast(res.message || 'Error', 'error');
                    if (res.errors) showErrors(res.errors);
                    if (options.error) options.error(res);
                }
            },

            error: function (xhr) {

                if (xhr.status === 422) {
                    showErrors(xhr.responseJSON?.errors || {});
                    toast(xhr.responseJSON?.message || 'Validation error', 'error');
                } else {
                    toast(xhr.responseJSON?.message || 'Server error', 'error');
                }

                if (options.error) options.error(xhr);
            },

            complete: function () {
                setTimeout(() => {
                    isSubmitting = false;
                }, 200);
            }
        });
    }

    // =====================================================
    // 📚 BOOKS
    // =====================================================

    $('#addBookForm').on('submit', function (e) {
        e.preventDefault();

        request({
            url: this.action,
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                $('#addBookForm')[0].reset();
                setTimeout(() => location.href = "/books", 800);
            }
        });
    });

    $('#editBookForm').on('submit', function (e) {
        e.preventDefault();

        request({
            url: this.action,
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                setTimeout(() => location.href = "/books", 800);
            }
        });
    });

    $(document).on('click', '.deleteBtn', function () {

        let id = $(this).data('id');

        if (!confirm('Delete this book?')) return;

        $.ajax({
            url: '/books/' + id,
            type: 'DELETE',

            success: function (res) {
                if (res.success) {
                    $('#row_' + id).remove();
                    toast(res.message, 'success');
                } else {
                    toast(res.message, 'error');
                }
            },

            error: function () {
                toast('Delete failed', 'error');
            }
        });
    });

    // =====================================================
    // 🎓 STUDENTS
    // =====================================================

    $('#addStudentForm').on('submit', function (e) {
        e.preventDefault();

        request({
            url: this.action,
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                $('#addStudentForm')[0].reset();
                setTimeout(() => location.href = "/students", 800);
            }
        });
    });

    $('#editStudentForm').on('submit', function (e) {
        e.preventDefault();

        request({
            url: this.action,
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                setTimeout(() => location.href = "/students", 800);
            }
        });
    });

    $(document).on('click', '.deleteStudentBtn', function () {

        let id = $(this).data('id');

        if (!confirm('Delete this student?')) return;

        $.ajax({
            url: '/students/' + id,
            type: 'DELETE',

            success: function (res) {
                if (res.success) {
                    $('#row_' + id).remove();
                    toast(res.message, 'success');
                } else {
                    toast(res.message, 'error');
                }
            }
        });
    });

    // =====================================================
    // 📖 BORROW
    // =====================================================

    $(document).on('submit', '#borrowForm', function (e) {
        e.preventDefault();

        request({
            url: this.action,
            method: 'POST',
            data: $(this).serialize(),
            success: function () {
                $('#borrowForm')[0].reset();
                setTimeout(() => location.href = "/borrow_transactions", 800);
            }
        });
    });

    $(document).on('submit', '#editBorrowForm', function (e) {
        e.preventDefault();

        request({
            url: this.action,
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                toast(res.message, 'success');
                setTimeout(() => {
                    location.href = "/borrow_transactions";
                }, 800);
            }
        });
    });

    // =====================================================
    // 📂 CATEGORIES
    // =====================================================

    $('#addCategoryForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);

        request({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),

            success: function (res) {
                toast(res.message || 'Category created successfully', 'success');

                setTimeout(() => {
                    window.location.href = "/categories";
                }, 800);
            }
        });
    });

    $('#editCategoryForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);

        request({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),

            success: function (res) {
                toast(res.message || 'Category updated successfully', 'success');

                setTimeout(() => {
                    window.location.href = "/categories";
                }, 800);
            }
        });
    });

    $(document).on('click', '.deleteCategoryBtn', function () {

        let id = $(this).data('id');

        if (!confirm('Delete category?')) return;

        $.ajax({
            url: '/categories/' + id,
            type: 'DELETE',

            success: function (res) {
                if (res.success) {
                    $('#row_' + id).remove();
                    toast(res.message, 'success');
                } else {
                    toast(res.message, 'error');
                }
            },

            error: function (xhr) {
                console.log(xhr.responseText);
                toast('Delete failed', 'error');
            }
        });
    });

    // =====================================================
    // 📖 BORROW UI LOGIC
    // =====================================================

    function toggleReturnQuantity() {
        const status = $('#status').val();
        $('#returnQuantityContainer').toggle(status === 'returned');
    }

    toggleReturnQuantity();

    $(document).on('change', '#status', toggleReturnQuantity);

    $('.book-checkbox').on('change', function () {

        let id = $(this).data('id');
        let qty = $(`input[name="quantities[${id}]"]`);

        qty.prop('disabled', !this.checked);

        if (!this.checked) qty.val(1);
    });

});