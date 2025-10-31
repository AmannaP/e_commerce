
// ================== ADMIN PRODUCT MANAGEMENT ==================

$(document).ready(function () {
    function resetForm() {
        $('#product_id').val('');
        $('#product-form')[0].reset();
        $('#save-product').text('Save Product');
    }

    function fetchProducts() {
        $.ajax({
            url: "../actions/fetch_product_action.php",
            method: "GET",
            dataType: "json",
            success: function (res) {
                const tbody = $('#product-table tbody');
                tbody.empty();

                if (res.status === "success" && Array.isArray(res.products) && res.products.length > 0) {
                    res.products.forEach(p => {
                        const imgUrl = p.product_image ? `../images/products/${p.product_image}` : `../images/products/default.jpg`;
                        tbody.append(`
                            <tr>
                                <td>${p.product_id}</td>
                                <td><img src="${imgUrl}" style="width:80px;height:60px;object-fit:cover"></td>
                                <td>${p.product_title}</td>
                                <td>${p.cat_name ?? '—'}</td>
                                <td>${p.brand_name ?? '—'}</td>
                                <td>${parseFloat(p.product_price).toFixed(2)}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-btn" data-id="${p.product_id}">Edit</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="7" class="text-center text-muted">No products found.</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                Swal.fire('Error', 'Failed to load products', 'error');
            }
        });
    }

    fetchProducts();

    // Save (add or update)
    $('#product-form').submit(function (e) {
        e.preventDefault();

        const title = $('#product_title').val().trim();
        const price = $('#product_price').val().trim();
        if (!title || !price) {
            Swal.fire('Error', 'Please fill all required fields', 'error');
            return;
        }

        const form = $('#product-form')[0];
        const data = new FormData(form);
        const product_id = $('#product_id').val();
        const url = product_id ? '../actions/update_product_action.php' : '../actions/add_product_action.php';

        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: () => {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function (res) {
                if (res.status === 'success') {
                    Swal.fire('Success', res.message, 'success').then(() => {
                        resetForm();
                        fetchProducts();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Failed', 'error');
                }
            },
            error: function (xhr, status, error) {
                Swal.close();
                console.error("AJAX Error:", status, error, xhr.responseText);
                Swal.fire("Error", "Server error occurred.", "error");
            }
        });
    });

    // Reset button
    $('#reset-form').click(resetForm);

    // Edit product (populate form)
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: '../actions/fetch_product_action.php',
            method: 'GET',
            data: { id },
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    const prod = res.products;
                    // .find(p => p.product_id == id);
                    $('#product_id').val(prod.product_id);
                    $('#cat_id').val(prod.product_cat);
                    $('#brand_id').val(prod.product_brand);
                    $('#product_title').val(prod.product_title);
                    $('#product_price').val(prod.product_price);
                    $('#product_description').val(prod.product_description);
                    $('#product_keywords').val(prod.product_keywords);
                    $('#save-product').text('Update Product');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    Swal.fire('Error', res.message || 'Failed to load product', 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                Swal.fire("Error", "Server error occurred.", "error");
            }
        });
    });
});

// ================== USER PRODUCT DISPLAY ==================
$(document).ready(function() {
    function fetchUserProducts(filters = {}) {
        $.ajax({
            url: "../actions/fetch_product_action.php",
            type: "GET",
            data: filters,
            dataType: "json",
            success: function(res) {
                const container = $("#product-list");
                container.empty();

                if (res.status === "success" && Array.isArray(res.products) && res.products.length > 0) {
                    res.products.forEach((p) => {
                        const imagePath = `../images/products/${p.product_image}`;
                        const imgUrl = (p.product_image && p.product_image.trim() !== "")
                            ? imagePath
                            : `../images/products/default.png`;
                        container.append(`
                            <div class="col-md-4">
                                <div class="card product-card p-3 shadow-sm">
                                    <img src="${imgUrl}" 
                                        onerror="this.onerror=null;this.src='../images/products/default.jpg';"
                                        class="card-img-top mb-3" 
                                        alt="${p.product_title}" 
                                        style="height:200px;object-fit:cover;border-radius:10px;">
                                    <h5 class="fw-bold">${p.product_title}</h5>
                                    <p class="text-muted mb-1"><strong>Category:</strong> ${p.cat_name}</p>
                                    <p class="text-muted mb-1"><strong>Brand:</strong> ${p.brand_name}</p>
                                    <p><strong>Price:</strong> GH₵${p.product_price}</p>
                                    <a href="../views/single_product.php?id=${p.product_id}" class="btn btn-custom w-100 mt-2">View Details</a>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    container.html("<p class='text-center text-muted'>No products found.</p>");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                Swal.fire("Error", "Server error occurred.", "error");
            }
        });
    }

    // Initial load
    fetchUserProducts();

    // Search & Filter listeners
    $("#search_btn").on("click", function() {
        const query = $("#search_box").val().trim();
        fetchUserProducts({ search: query });
    });

    $("#category_filter, #brand_filter").on("change", function() {
        const cat_id = $("#category_filter").val();
        const brand_id = $("#brand_filter").val();
        fetchUserProducts({ cat_id, brand_id });
    });
});
