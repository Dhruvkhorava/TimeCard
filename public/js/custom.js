/**
 * Custom JS for Timecard Management System
 */

document.addEventListener("DOMContentLoaded", function () {
    // 1. Initializing AOS (Animate On Scroll)
    if (typeof AOS !== "undefined") {
        AOS.init({
            duration: 800,
            easing: "ease-in-out",
            once: true,
            offset: 50,
        });
    }

    // 2. SweetAlert2 Global Toast Config & Delete Confirmation
    if (typeof Swal !== "undefined") {
        window.Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });

        // Global Delete Confirmation
        $(document).on("submit", ".delete-form", function (e) {
            e.preventDefault();
            const form = this;
            const message =
                $(this).data("message") ||
                "Are you sure you want to delete this record?";

            Swal.fire({
                title: "Are you sure?",
                text: message,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                customClass: {
                    popup: "border-radius-lg shadow-lg",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

    // 3. Auto-Initialize DataTables for elements with .datatable class
    $(".datatable-init").each(function () {
        const id = $(this).attr("id");
        if (id && !$.fn.DataTable.isDataTable("#" + id)) {
            const orderIndex = $(this).data("order-index") || 0;
            const orderDir = $(this).data("order-dir") || "asc";
            initDataTable("#" + id, {
                order: [[orderIndex, orderDir]],
            });
        }
    });

    // 4. Auto-Initialize Select2 for elements with .select2 class
    if ($.fn.select2) {
        $(".select2-init").each(function () {
            const placeholder = $(this).data("placeholder") || "Select option";
            $(this).select2({
                placeholder: placeholder,
                width: "100%",
            });
        });
    }

    // 5. Perfect Scrollbar Initialization for Sidenav
    var win = navigator.platform.indexOf("Win") > -1;
    if (win && document.querySelector("#sidenav-scrollbar")) {
        if (typeof Scrollbar !== "undefined") {
            var options = {
                damping: "0.5",
            };
            Scrollbar.init(
                document.querySelector("#sidenav-scrollbar"),
                options,
            );
        }
    }

    // Sidebar Toggle Enhancement
    const iconNavbarSidenav = document.getElementById("iconNavbarSidenav");
    if (iconNavbarSidenav) {
        iconNavbarSidenav.addEventListener("click", function (e) {
            // Note: The actual toggle logic is in the theme's core JS
            e.stopPropagation();
        });
    }

    // Initialize Tooltips if any
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]'),
    );
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        if (typeof bootstrap !== "undefined") {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        }
    });

    // Project Phase Management (used in create/edit project forms)
    const addPhaseBtn = document.getElementById("add-phase");
    const phasesContainer = document.getElementById("phases-container");

    if (addPhaseBtn && phasesContainer) {
        addPhaseBtn.addEventListener("click", function () {
            const newRow = document.createElement("div");
            newRow.className = "input-group mb-2 phase-row";
            newRow.innerHTML = `
                <input type="text" name="phases[]" class="form-control" placeholder="Phase name" required>
                <button type="button" class="btn btn-outline-danger mb-0 remove-phase">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            phasesContainer.appendChild(newRow);
            updateRemoveButtons();
        });

        phasesContainer.addEventListener("click", function (e) {
            const btn = e.target.closest(".remove-phase");
            if (btn) {
                const row = btn.closest(".phase-row");
                if (document.querySelectorAll(".phase-row").length > 1) {
                    row.remove();
                    updateRemoveButtons();
                }
            }
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll(".phase-row");
            const removeButtons = document.querySelectorAll(".remove-phase");
            if (rows.length === 1) {
                if (removeButtons[0]) removeButtons[0].style.display = "none";
            } else {
                removeButtons.forEach((btn) => (btn.style.display = "block"));
            }
        }

        // Initial setup
        updateRemoveButtons();
    }

    // Daily Updates Manager
    const dailyUpdateForm = document.getElementById("updates-form");
    if (dailyUpdateForm) {
        const $dateInput = $('input[name="date"]');
        const fetchByDateUrl = dailyUpdateForm.dataset.fetchByDateUrl;
        const fetchTasksUrl = dailyUpdateForm.dataset.fetchTasksUrl;
        const projectsData = JSON.parse(
            dailyUpdateForm.dataset.projects || "[]",
        );

        // Core row generation function
        window.addUpdateRow = function (data, index) {
            console.log("asfsadf");
            const $tableBody = $("#updates-table tbody");
            let projectOptions = '<option value="">Select Project</option>';
            projectsData.forEach((project) => {
                projectOptions += `<option value="${project.id}">${project.name}</option>`;
            });

            let template = `
                <tr class="update-row">
                    <td>
                        <select class="form-control form-control-sm project-select" name="updates[${index}][project_id]" required>
                            ${projectOptions}
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="updates[${index}][id]" value="${
                            data ? data.id : ""
                        }">
                        <select class="form-control form-control-sm task-select" name="updates[${index}][task_id]" 
                            data-selected-task-id="${
                                data ? data.task_id : ""
                            }" required disabled>
                            <option value="">Select Project First</option>
                        </select>
                    </td>
                    <td>
                        <input type="time" class="form-control form-control-sm" name="updates[${index}][start_time]" 
                            value="${data ? data.start_time : ""}" required>
                    </td>
                    <td>
                        <input type="time" class="form-control form-control-sm" name="updates[${index}][end_time]" 
                            value="${data ? data.end_time : ""}" required>
                    </td>
                    <td>
                        <textarea class="form-control form-control-sm" name="updates[${index}][work_done]" rows="4" required
                            placeholder="Work description...">${
                                data ? data.work_done : ""
                            }</textarea>
                    </td>
                    <td class="text-end px-3">
                        <button type="button" class="btn btn-link text-danger mb-0 remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            const $row = $(template);
            $tableBody.append($row);

            if (data && data.task && data.task.project_id) {
                $row.find(".project-select")
                    .val(data.task.project_id)
                    .trigger("change");
            } else if (data && data.project_id) {
                // Handle cases where data has project_id but not nested task.project_id
                $row.find(".project-select")
                    .val(data.project_id)
                    .trigger("change");
            }
        };

        // AJAX to fetch updates when date changes
        $dateInput.on("change", function () {
            const date = $(this).val();
            if (!date || !fetchByDateUrl) return;

            // Show loading state
            const $tableBody = $("#updates-table tbody");
            $tableBody.html('<tr><td colspan="6" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Loading updates...</td></tr>');

            $.ajax({
                url: fetchByDateUrl,
                method: "GET",
                data: { date: date },
                success: function (updates) {
                    $tableBody.empty();
                    if (updates && updates.length > 0) {
                        updates.forEach((update, index) => {
                            window.addUpdateRow(update, index);
                        });
                    } else {
                        // Add one empty row if no updates exist
                        window.addUpdateRow(null, 0);
                    }
                    updateDailyUpdateRemoveButtons();
                },
                error: function() {
                    $tableBody.html('<tr><td colspan="6" class="text-center py-4 text-danger">Error loading updates. Please try again.</td></tr>');
                }
            });
        });

        // Trigger change to load updates for the default selected date on page load
        if ($dateInput.val()) {
            $dateInput.trigger("change");
        }

        // Project -> Task Dependency
        $(document).on("change", ".project-select", function () {
            const projectId = $(this).val();
            const $row = $(this).closest(".update-row");
            const $taskSelect = $row.find(".task-select");
            const currentTaskId = $taskSelect.data("selected-task-id");

            if (!projectId) {
                $taskSelect
                    .html('<option value="">Select Project First</option>')
                    .prop("disabled", true);
                return;
            }

            if (!fetchTasksUrl) return;

            $taskSelect
                .html('<option value="">Loading tasks...</option>')
                .prop("disabled", true);

            $.ajax({
                url: fetchTasksUrl + "/" + projectId,
                method: "GET",
                success: function (tasks) {
                    let options = '<option value="">Select Task</option>';
                    tasks.forEach((task) => {
                        options += `<option value="${task.id}" ${
                            currentTaskId == task.id ? "selected" : ""
                        }>${task.name}</option>`;
                    });
                    $taskSelect.html(options).prop("disabled", false);
                    $taskSelect.removeAttr("data-selected-task-id");
                },
            });
        });

        // Add Row Button
        $("#add-row").on("click", function () {
            if (typeof window.addUpdateRow === "function") {
                const index = $(".update-row").length;
                window.addUpdateRow(null, index);
                updateDailyUpdateRemoveButtons();
            }
        });

        $(document).on("click", ".remove-row", function () {
            const $row = $(this).closest(".update-row");
            if ($(".update-row").length > 1) {
                $row.remove();
                updateDailyUpdateRemoveButtons();
            }
        });

        function updateDailyUpdateRemoveButtons() {
            const rows = $(".update-row");
            $(".remove-row").toggle(rows.length > 1);
        }
    }

    // Profile Image Preview Management
    const profileInput = document.getElementById("profile_input");
    const imagePreview = document.getElementById("image-preview");
    const previewPlaceholder = document.getElementById("preview-placeholder");

    if (profileInput && imagePreview) {
        profileInput.addEventListener("change", function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove("d-none");
                    if (previewPlaceholder) {
                        previewPlaceholder.classList.add("d-none");
                    }
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});

/**
 * Global DataTable Initialization
 * Standardizes the look and feel of all tables in the application
 *
 * @param {string} selector - The CSS selector for the table
 * @param {object} customOptions - Optional custom overrides for DataTable
 */
function initDataTable(selector, customOptions = {}) {
    if (!$(selector).length) return null;

    const defaultOptions = {
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>t<"row mt-3"<"col-md-5"i><"col-md-7"p>>',
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search...",
            paginate: {
                previous: "<i class='fas fa-chevron-left text-xs'></i>",
                next: "<i class='fas fa-chevron-right text-xs'></i>",
            },
        },
        drawCallback: function () {
            // Apply theme styles after each draw
            $(".dataTables_filter input").addClass(
                "form-control form-control-sm ms-auto w-auto",
            );
            $(".dataTables_filter label").addClass(
                "mb-0 w-100 text-end d-flex justify-content-end align-items-center",
            );
            $(".dataTables_info").addClass("text-sm text-secondary");
            $(".dataTables_paginate").addClass("d-flex justify-content-end");
            $(".dataTables_length select").addClass(
                "form-select form-select-sm ms-2",
            );
        },
    };

    const options = $.extend(true, {}, defaultOptions, customOptions);
    return $(selector).DataTable(options);
}
