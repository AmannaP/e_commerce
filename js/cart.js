// cart.js (Updated and Safe for All Pages)

// -------------------------------
// Utility: Show feedback messages
// -------------------------------
function showMessage(type, message) {
    const box = document.createElement("div");
    box.className = `alert alert-${type}`; // success, danger, warning
    box.textContent = message;

    box.style.position = "fixed";
    box.style.top = "20px";
    box.style.right = "20px";
    box.style.zIndex = "9999";
    box.style.padding = "10px 20px";

    document.body.appendChild(box);

    setTimeout(() => box.remove(), 3000);
}

// -------------------------------
// Add Item to Cart (works on ANY page)
// -------------------------------
async function addToCart(product_id, qty = 1) {
    const formData = new FormData();
    formData.append("product_id", product_id);
    formData.append("qty", qty);

    try {
        const response = await fetch("actions/add_to_cart_action.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            showMessage("success", "Item added to cart!");

            // refresh cart UI only if available
            if (document.getElementById("cart-items")) {
                loadCartItems();
            }
        } else {
            showMessage("danger", result.message);
        }
    } catch (error) {
        showMessage("danger", "Error adding item.");
        console.error(error);
    }
}

// -------------------------------
// Update Item Quantity
// -------------------------------
async function updateCartItem(cart_id, qty) {
    const formData = new FormData();
    formData.append("cart_id", cart_id);
    formData.append("qty", qty);

    try {
        const response = await fetch("actions/update_cart_item_action.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            showMessage("success", "Cart updated!");
            loadCartItems();
        } else {
            showMessage("danger", result.message);
        }
    } catch (error) {
        showMessage("danger", "Could not update cart.");
        console.error(error);
    }
}

// -------------------------------
// Remove Cart Item
// -------------------------------
async function removeCartItem(cart_id) {
    const formData = new FormData();
    formData.append("cart_id", cart_id);

    try {
        const response = await fetch("actions/remove_from_cart_action.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            showMessage("success", "Item removed!");
            loadCartItems();
        } else {
            showMessage("danger", result.message);
        }

    } catch (error) {
        showMessage("danger", "Could not remove item.");
        console.error(error);
    }
}

// -------------------------------
// Empty Cart
// -------------------------------
async function emptyCart(customer_id) {
    const formData = new FormData();
    formData.append("customer_id", customer_id);

    try {
        const response = await fetch("actions/empty_cart_action.php", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();

        if (result.status === "success") {
            showMessage("success", "Cart emptied!");
            loadCartItems();
        } else {
            showMessage("danger", result.message);
        }

    } catch (error) {
        showMessage("danger", "Could not empty cart.");
        console.error(error);
    }
}

// -------------------------------
// Load Cart Items (only runs on cart.php / checkout.php)
// -------------------------------
async function loadCartItems() {
    // If page does NOT have cart elements, stop silently
    const cartContainer = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");

    if (!cartContainer || !cartTotal) return;

    try {
        const response = await fetch("actions/get_cart_action.php");
        const result = await response.json();

        cartContainer.innerHTML = ""; // clear old items

        if (result.items.length === 0) {
            cartContainer.innerHTML = "<p>Your cart is empty.</p>";
            cartTotal.textContent = "0.00";
            return;
        }

        result.items.forEach(item => {
            cartContainer.innerHTML += `
                <div class="cart-item">
                    <span>${item.product_name}</span>

                    <input type="number" 
                           min="1" 
                           value="${item.qty}" 
                           onchange="updateCartItem(${item.cart_id}, this.value)">

                    <span>₵${item.total_price}</span>

                    <button onclick="removeCartItem(${item.cart_id})"
                            class="btn btn-sm btn-danger">
                        Remove
                    </button>
                </div>
            `;
        });

        cartTotal.textContent = result.total_price;

    } catch (error) {
        showMessage("danger", "Unable to load cart.");
        console.error(error);
    }
}

// Run loader **only on pages that have cart elements**
document.addEventListener("DOMContentLoaded", loadCartItems);
