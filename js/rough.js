// RETRIEVE
    function fetchBrands() {
        $.ajax({
            url: "../actions/fetch_brand_action.php",
            type: "GET",
            dataType: "json",
            success: function (res) {
                let tbody = $("#brand-table tbody");
                tbody.empty();

                if (response.status === "success") {
                    const brands = response.brands;

                    if (brands.length === 0) {
                        tbody.append(
                            "<tr><td colspan='3' class='text-center'>No brands found</td></tr>"
                        );
                        return;
                    }

                    brands.forEach((brand) => {
                        tbody.append(`
                            <tr>
                                <td>${brand.brand_id}</td>
                                <td>${brand.brand_name}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm update-btn" data-id="${brand.brand_id}" data-name="${brand.brand_name}">Update</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${brand.brand_id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(`<tr><td colspan="5" class="text-center text-muted">No brands found.</td></tr>`);
                }
            },
            error: function () {
                Swal.fire("Error", "Failed to load brands.", "error");
            }
        });
    }

    fetchBrands();

    // UPDATE
    $(document).on("click", ".update-btn", function () {
        const brand_id = $(this).data("id");
        const oldName = $(this).data("name");

        Swal.fire({
            title: "Update Brand Name",
            input: "text",
            inputLabel: "New brand name",
            inputValue: oldName,
            showCancelButton: true,
            confirmButtonText: "Update",
        }).then((result) => {
            if (result.isConfirmed && validateBrandName(result.value)) {
                $.ajax({
                    url: "../actions/update_brand_action.php",
                    type: "POST",
                    dataType: "json",
                    data: { brand_id, brand_name: result.value.trim() },
                    success: function (res) {
                        if (res.status === "success") {
                            Swal.fire("Updated!", res.message, "success").then(fetchBrands);
                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    },
            success: function (res) {
                if (res.status === "success") {
                    Swal.fire("Success", res.message, "success").then(() => {
                        $("#add-brand-form")[0].reset();
                        fetchBrands();
                    });
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Server error occurred.", "error");
            }
        });
    }
    });
    });