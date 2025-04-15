class DataTableManager {
    constructor(config) {
        this.tableId = config.tableId;
        this.modelName = config.modelName;
        this.routeName = config.routeName;
        this.formType = config.formType || "page"; // 'page' or 'modal'
        this.columns = config.columns;
        this.relationships = config.relationships || [];
        this.table = null;
        this.modal = null;

        this.init();
    }

    init() {
        this.initializeDataTable();

        this.initializeEventListeners();
        if (this.formType === "modal") {
            this.initializeModal();
        }
    }

    initializeDataTable() {
        const self = this;
        this.table = $(`#${this.tableId}`).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `/${this.routeName}`,
                type: "GET",
                dataSrc: function (json) {
                    return json.data || [];
                },
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    className: "select-checkbox",
                    defaultContent: "",
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                },
                ...this.columns,
                {
                    data: "actions",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return self.renderActionButtons(row);
                    },
                },
            ],
            select: {
                style: "multi",
                selector: "td:first-child .dt-checkboxes",
            },
            buttons: this.getExportButtons(),
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "&laquo;",
                    next: "&raquo;",
                },
            },
        });
    }

    initializeModal() {
        const modalHtml = `
            <div class="modal fade" id="dataTableModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body"></div>
                    </div>
                </div>
            </div>
        `;
        $("body").append(modalHtml);
        this.modal = new bootstrap.Modal(
            document.getElementById("dataTableModal")
        );
    }

    initializeEventListeners() {
        this.initializeSelectAll();
        this.initializeRowSelect();
        this.initializeBulkDelete();
        this.initializeExportHandlers();
        this.initializeCrudHandlers();
    }

    initializeSelectAll() {
        const self = this;
        $("#select-all").on("change", function () {
            const rows = self.table.rows({ search: "applied" }).nodes();
            $('input[type="checkbox"]', rows).prop("checked", this.checked);
            if (this.checked) {
                self.table.rows({ search: "applied" }).select();
            } else {
                self.table.rows({ search: "applied" }).deselect();
            }
            self.updateBulkDeleteButton();
        });
    }

    initializeRowSelect() {
        const self = this;
        $(`#${this.tableId} tbody`).on("change", ".dt-checkboxes", function () {
            const row = $(this).closest("tr");
            if (this.checked) {
                self.table.row(row).select();
            } else {
                self.table.row(row).deselect();
            }
            self.updateBulkDeleteButton();
        });
    }

    initializeBulkDelete() {
        const self = this;
        $("#bulk-action-btn").on("click", function () {
            const selectedRows = self.table.rows({ selected: true });
            const ids = selectedRows
                .data()
                .map((item) => item.id)
                .toArray();

            if (ids.length === 0) return;

            self.confirmDelete("bulk", ids);
        });
    }

    initializeExportHandlers() {
        const self = this;
        $("[data-export-type]").on("click", function () {
            const exportType = $(this).data("export-type");
            self.table.button(`.buttons-${exportType}`).trigger();
        });
    }

    initializeCrudHandlers() {
        const self = this;

        // Add New Record
        $("#add-new-record-btn").on("click", function (e) {
            if (self.formType === "modal") {
                e.preventDefault();
                self.handleCreate();
            }
        });

        // Edit and View handlers
        $(`#${this.tableId}`).on("click", ".edit-btn, .view-btn", function (e) {
            if (self.formType === "modal") {
                e.preventDefault();
                const id = $(this).data("id");
                const action = $(this).hasClass("edit-btn") ? "edit" : "view";
                self.handleAction(action, id);
            }
        });

        // Delete handler
        $(`#${this.tableId}`).on("click", ".delete-btn", function (e) {
            e.preventDefault();
            const id = $(this).data("id");
            self.confirmDelete("single", id);
        });
    }

    async handleCreate() {
        try {
            const response = await fetch(`/${this.routeName}/create`);
            const html = await response.text();
            this.showModal("Add New Record", html);
        } catch (error) {
            this.showError("Error loading create form");
        }
    }

    async handleAction(action, id) {
        try {
            const response = await fetch(`/${this.routeName}/${id}/${action}`);
            const html = await response.text();
            this.showModal(
                action === "edit" ? "Edit Record" : "View Record",
                html
            );
        } catch (error) {
            this.showError(`Error loading ${action} form`);
        }
    }

    showModal(title, content) {
        const modalElement = document.getElementById("dataTableModal");
        modalElement.querySelector(".modal-title").textContent = title;
        modalElement.querySelector(".modal-body").innerHTML = content;
        this.modal.show();
    }

    confirmDelete(type, ids) {
        const self = this;
        Swal.fire({
            title: "Are you sure?",
            text: `You are about to delete ${
                type === "bulk" ? ids.length + " items" : "this item"
            }`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                self.performDelete(type, ids);
            }
        });
    }

    async performDelete(type, ids) {
        try {
            const url =
                type === "bulk"
                    ? `/bulk-delete/${this.modelName}`
                    : `/${this.routeName}/${ids}`;

            const response = await fetch(url, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                body: JSON.stringify({ ids: type === "bulk" ? ids : [ids] }),
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccess(data.message);
                this.table.ajax.reload();
                this.updateBulkDeleteButton();
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            this.showError("Error performing delete operation");
        }
    }

    updateBulkDeleteButton() {
        const selectedRows = this.table.rows({ selected: true }).count();
        $("#bulk-action-btn")
            .prop("disabled", selectedRows === 0)
            .text(`Bulk Delete (${selectedRows} selected)`);
    }

    getExportButtons() {
        return [
            {
                extend: "collection",
                className: "btn btn-light dropdown-toggle me-2",
                text: '<i class="bx bx-export me-1"></i>Export',
                buttons: [
                    {
                        extend: "print",
                        text: '<i class="bx bx-printer me-1"></i>Print',
                        className: "dropdown-item",
                        exportOptions: {
                            columns:
                                ":visible:not(:first-child):not(:last-child)",
                        },
                    },
                    {
                        extend: "csv",
                        text: '<i class="bx bx-file me-1"></i>CSV',
                        className: "dropdown-item",
                        exportOptions: {
                            columns:
                                ":visible:not(:first-child):not(:last-child)",
                        },
                    },
                    {
                        extend: "excel",
                        text: '<i class="bx bxs-file-export me-1"></i>Excel',
                        className: "dropdown-item",
                        exportOptions: {
                            columns:
                                ":visible:not(:first-child):not(:last-child)",
                        },
                    },
                    {
                        extend: "pdf",
                        text: '<i class="bx bxs-file-pdf me-1"></i>PDF',
                        className: "dropdown-item",
                        exportOptions: {
                            columns:
                                ":visible:not(:first-child):not(:last-child)",
                        },
                    },
                ],
            },
        ];
    }

    renderActionButtons(row) {
        return `
            <div class="d-flex gap-2">
                <a href="/${this.routeName}/${row.id}"
                   class="btn btn-sm btn-icon btn-primary view-btn"
                   data-id="${row.id}">
                    <i class="bx bx-show"></i>
                </a>
                <a href="/${this.routeName}/${row.id}/edit"
                   class="btn btn-sm btn-icon btn-warning edit-btn"
                   data-id="${row.id}">
                    <i class="bx bx-edit"></i>
                </a>
                <button class="btn btn-sm btn-icon btn-danger delete-btn"
                        data-id="${row.id}">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        `;
    }

    showSuccess(message) {
        Swal.fire({
            title: "Success!",
            text: message,
            icon: "success",
            confirmButtonText: "OK",
        });
    }

    showError(message) {
        Swal.fire({
            title: "Error!",
            text: message,
            icon: "error",
            confirmButtonText: "OK",
        });
    }
}
