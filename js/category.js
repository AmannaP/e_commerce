$(document).ready(function () {
    // Utility: validate category name
    function validateCategoryName(name) {
        if (!name || typeof name !== "string" || name.trim().length < 2) {
            Swal.fire({
                icon: "error",
                title: "Invalid Input",
                text: "Category name must be at least 2 characters long.",
            });
            return false;
        }
        return true;
    }

    // CREATE category
    $("#add-category-form").submit(function (e) {
        e.preventDefault();

        const cat_name = $("#cat_name").val().trim();

        if (!validateCategoryName(cat_name)) return;

        $.ajax({
            url: "../actions/add_category_action.php",
            method: "POST",
            dataType: "json",
            data: { cat_name },
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire("Success", response.message, "success").then(() => {
                        fetchCategories(); // refresh list
                        $("#cat_name").val("");
                    });
                } else {
                    Swal.fire("Error", response.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Server error occurred", "error");
            },
        });
    });

    // RETRIEVE categories
    function fetchCategories() {
        $.ajax({
            url: "../actions/fetch_category_action.php",
            method: "GET",
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    let tableBody = $("#category-table tbody");
                    tableBody.empty();

                    if (response.categories.length === 0) {
                        tableBody.append(
                            "<tr><td colspan='4' class='text-center'>No categories found</td></tr>"
                        );
                        return;
                    }

                    response.categories.forEach((cat) => {
                        tableBody.append(`
                            <tr>
                                <td>${cat.cat_id}</td>
                                <td>${cat.cat_name}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm update-btn" data-id="${cat.cat_id}" data-name="${cat.cat_name}">Update</button>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${cat.cat_id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                }
            },
            error: function () {
                Swal.fire("Error", "Failed to load categories", "error");
            },
        });
    }

    fetchCategories(); // load on page load

    // UPDATE category
    $(document).on("click", ".update-btn", function () {
        const cat_id = $(this).data("id");
        const oldName = $(this).data("name");

        Swal.fire({
            title: "Update Category",
            input: "text",
            inputLabel: "New category name",
            inputValue: oldName,
            showCancelButton: true,
            confirmButtonText: "Update",
        }).then((result) => {
            if (result.isConfirmed && validateCategoryName(result.value)) {
                $.ajax({
                    url: "../actions/update_category_action.php",
                    method: "POST",
                    dataType: "json",
                    data: { cat_id, new_name: result.value.trim() },
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire("Success", response.message, "success").then(() =>
                                fetchCategories()
                            );
                        } else {
                            Swal.fire("Error", response.message, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Server error occurred", "error");
                    },
                });
            }
        });
    });

    // DELETE category
    $(document).on("click", ".delete-btn", function () {
        const cat_id = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Delete",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../actions/delete_category_action.php",
                    method: "POST",
                    dataType: "json",
                    data: { cat_id },
                    success: function (response) {
                        if (response.status === "success") {
                            Swal.fire("Deleted", response.message, "success").then(() =>
                                fetchCategories()
                            );
                        } else {
                            Swal.fire("Error", response.message, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Server error occurred", "error");
                    },
                });
            }
        });
    });
});
