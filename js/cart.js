//cart.js
document.addEventListener("DOMContentLoaded", function () {
    console.log("Cart.js loaded");

    // ADD TO CART BUTTON HANDLER
    document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
        btn.addEventListener("click", function () {

            const productId = this.dataset.id;
            const title = this.dataset.title;
            const price = this.dataset.price;
            const image = this.dataset.image;

            console.log("Adding product:", productId, title);

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('qty', 1);

            fetch("../actions/add_to_cart_action.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())  // Changed to .text() to see raw response
            .then(text => {
                console.log("Raw response:", text);  // Log the actual response
                try {
                    const data = JSON.parse(text);
                    console.log("Parsed data:", data);

                    if (data.status === "success") {
                        alert("Item added to cart!");
                    } else {
                        alert("Failed to add to cart: " + (data.message || "Unknown error"));
                    }
                } catch (e) {
                    console.error("JSON parse error:", e);
                    console.error("Response was:", text);
                    alert("Server returned an error. Check console for details.");
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                alert("An error occurred. Check console for details.");
            });
        });
    });


    // QUANTITY UPDATE HANDLER
    document.querySelectorAll(".qty-input").forEach(input => {
        input.addEventListener("change", function () {
            const id = this.dataset.id;
            const quantity = this.value;

            const formData = new FormData();
            formData.append('product_id', id);
            formData.append('qty', quantity);

            fetch("../actions/update_cart_action.php", {  // Updated path
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(() => location.reload());
        });
    });

    // REMOVE ITEM
    document.querySelectorAll(".remove-item").forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;

            const formData = new FormData();
            formData.append('product_id', id);

            fetch("../actions/remove_from_cart_action.php", {  // Updated path
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(() => location.reload());
        });
    });
});

// STANDALONE FUNCTIONS FOR INLINE ONCLICK HANDLERS

function updateCartQty(productId, quantity) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('qty', quantity);

    fetch("../actions/update_cart_action.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            location.reload(); // Refresh to show updated totals
        } else {
            alert("Failed to update quantity: " + (data.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("An error occurred while updating quantity.");
    });
}

function removeFromCart(productId) {
    if (!confirm("Remove this item from cart?")) return;
    
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch("../actions/remove_from_cart_action.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            location.reload();
        } else {
            alert("Failed to remove item: " + (data.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("An error occurred while removing item.");
    });
}

function emptyCart() {
    if (!confirm("Are you sure you want to empty your cart?")) return;
    
    fetch("../actions/empty_cart_action.php", {
        method: "POST"
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            location.reload();
        } else {
            alert("Failed to empty cart: " + (data.message || "Unknown error"));
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("An error occurred while emptying cart.");
    });
}