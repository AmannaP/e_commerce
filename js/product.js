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
                if (res.status === 'success' && res.products.length > 0) {
                    res.products.forEach(p => {
                        const imgUrl = p.product_image ? `../images/products/${p.product_image}` : 'https://via.placeholder.com/80';
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
            error: function () {
                Swal.fire('Error', 'Failed to load products', 'error');
            }
        });
    }

    fetchProducts();

    // Save (add or update)
    $('#product-form').submit(function (e) {
        e.preventDefault();

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
            error: function () {
                Swal.fire('Error', 'Server error occurred', 'error');
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
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    const prod = res.products.find(p => p.product_id == id);
                    if (!prod) { Swal.fire('Error', 'Product not found', 'error'); return; }
                    $('#product_id').val(prod.product_id);
                    $('#cat_id').val(prod.cat_id);
                    $('#brand_id').val(prod.brand_id);
                    $('#product_title').val(prod.product_title);
                    $('#product_price').val(prod.product_price);
                    $('#product_description').val(prod.product_description);
                    $('#product_keywords').val(prod.product_keywords);
                    $('#save-product').text('Update Product');
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    Swal.fire('Error', res.message || 'Failed', 'error');
                }
            },
            error: function () { Swal.fire('Error', 'Server error', 'error'); }
        });
    });
});
