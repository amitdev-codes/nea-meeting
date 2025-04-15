export const AjaxRequestHandler = (function () {
    /**
     * Perform AJAX request to load the create form and show the modal
     * @param {string} createUrl - The URL to load the form from
     * @param {string} modalSelector - The modal selector where the form will be loaded
     * @param {string} title - The modal title to display
     */
    const create = function (createUrl, modalSelector, title) {
        $.ajax({
            url: createUrl,
            type: 'GET',
            success: function (data) {
                $(modalSelector + " .modal-title").text(title);
                $(modalSelector + " .modal-body").html(data.html);
                $(modalSelector).modal('show');
            },
            error: function () {
                toastr.error('Unable to load the form. Please try again.');
            }
        });
    };

    /**
     * Handle the form submission via AJAX
     * @param {string} formSelector - The form selector to be submitted
     * @param {string} modalSelector - The modal selector to hide after success
     * @param {string} tableSelector - The table selector to reload after success
     */
    const storeOrUpdate = function (formSelector, modalSelector, tableSelector) {
        let form = $(formSelector);
        let formData = form.serialize();
        let actionUrl = form.attr('action');

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.success === 'success') {
                    $(modalSelector).modal('hide');
                    $(tableSelector).DataTable().ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                handleErrors(xhr);
            }
        });
    };

    /**
     * Perform AJAX request to load the edit form and show the modal
     * @param {string} editUrl - The URL to load the form from
     * @param {string} modalSelector - The modal selector where the form will be loaded
     * @param {string} title - The modal title to display
     */
    const edit = function (editUrl, modalSelector, title) {
        $.ajax({
            url: editUrl,
            type: 'GET',
            success: function (data) {
                $(modalSelector + " .modal-title").text(title);
                $(modalSelector + " .modal-body").html(data.html);
                $(modalSelector).modal('show');
            },
            error: function () {
                toastr.error('Unable to load the form. Please try again.');
            }
        });
    };

    /**
     * Perform AJAX request to delete a resource
     * @param {string} deleteUrl - The URL to send the delete request to
     * @param {string} tableSelector - The table selector to reload after success
     */
    const destroy = function (deleteUrl, tableSelector) {

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            customClass: {
                confirmButton: 'btn btn-danger',
            },
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    success: function (response) {
                        if (response.status === 'success') {
                            $(tableSelector).DataTable().ajax.reload();
                            Swal.fire('Deleted!', response.message, 'success');
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'An error occurred while trying to delete.', 'error');
                    }
                });
            } else {
                Swal.fire('Cancelled', 'Your data is safe!', 'info');
            }
        });
    };


    const datatableReorder = function (tableSelector, reorderUrl) {
        const table = $(tableSelector).DataTable();

        table.on('row-reorder.dt', function (e, diff, edit) {
            console.log('Row reordered');

            if (diff.length === 0) {
                console.log('No changes');
                return;
            }

            let order = [];
            let pageInfo = table.page.info();

            $.each(diff, function (i, val) {
                console.log('i', i , 'val', val);
                let neworder = pageInfo.start + val.newPosition + 1;
                order.push({
                    id: val.node.id,
                    position: neworder
                });
            });

            console.log(order);

            $.ajax({
                url: reorderUrl,
                method: 'POST',
                data: {
                    order: order
                },
                success: function (response) {
                    toastr.success('Order updated successfully');
                },
                error: function (xhr) {
                    toastr.error('Failed to update order');
                }
            });
        });
    };

    /**
     * Handle validation errors returned from the server
     * @param {object} xhr - The XHR object containing the response
     */
    const handleErrors = function (xhr) {
        let errors = xhr.responseJSON.errors;
        if (errors) {
            $.each(errors, function (key, error) {
                let input = $(`[name="${key}"]`);
                input.addClass('is-invalid');
                input.next('.invalid-feedback').html(error[0]);
            });
        } else {
            toastr.error('An unexpected error occurred.');
        }
    };

    return {
        create: create,
        storeOrUpdate: storeOrUpdate,
        edit: edit,
        destroy: destroy,
        datatableReorder: datatableReorder,
    };
})();
