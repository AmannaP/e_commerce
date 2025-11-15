// checkout.js

// -------------------------------
// Utility: Display feedback
// -------------------------------
function notify(type, message) {
    const box = document.createElement("div");
    box.className = `alert alert-${type}`; 
    box.textContent = message;

    document.body.appendChild(box);
    setTimeout(() => box.remove(), 3000);
}

// -------------------------------
// Show the Simulated Payment Modal
// -------------------------------
function openPaymentModal() {
    document.getElementById("paymentModal").style.display = "block";
}

// Hide the modal
function closePaymentModal() {
    document.getElementById("paymentModal").style.display = "none";
}

// -------------------------------
// Handle "Yes, I’ve paid" Click
// -------------------------------
async function confirmPayment(customer_id) {

    // Disable the button while processing
    const confirmBtn = document.getElementById("confirmPaymentBtn");
    confirmBtn.disabled = true;
    confirmBtn.textContent = "Processing...";

    const formData = new FormData();
    formData.append("customer_id", customer_id);

    try {
        const response = await fetch("actions/process_checkout_action.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.status === "success") {
            closePaymentModal();
            notify("success", "Payment successful! Order confirmed.");

            displayOrderConfirmation(result);

            // Smooth transition to confirmation page
            transitionToConfirmation();

        } else {
            notify("danger", result.message || "Checkout failed.");
        }

    } catch (error) {
        console.error(error);
        notify("danger", "Something went wrong. Try again.");
    }

    // Re-enable button
    confirmBtn.disabled = false;
    confirmBtn.textContent = "Yes, I’ve paid";
}

// -------------------------------
// Handle "Cancel Payment"
// -------------------------------
function cancelPayment() {
    closePaymentModal();
    notify("warning", "Payment was cancelled.");
}

// -------------------------------
// Display Confirmation Screen
// -------------------------------
function displayOrderConfirmation(orderData) {
    const container = document.getElementById("confirmation-section");

    container.innerHTML = `
        <h2>Order Successful!</h2>
        <p>Thank you for your purchase.</p>
        <p><strong>Order Reference:</strong> ${orderData.order_reference}</p>
        <p><strong>Order ID:</strong> ${orderData.order_id}</p>
        <p>${orderData.message}</p>
        <button onclick="window.location.href='shop.php'">
            Continue Shopping
        </button>
    `;
}

// ---------------------------------------------
// Transition from Checkout → Confirmation
// ---------------------------------------------
function transitionToConfirmation() {
    document.getElementById("cart-section").style.display = "none";
    document.getElementById("checkout-section").style.display = "none";

    const confirmSec = document.getElementById("confirmation-section");
    confirmSec.style.display = "block";

    confirmSec.scrollIntoView({ behavior: "smooth" });
}

// -----------------
// Initialize Page
// -----------------
document.addEventListener("DOMContentLoaded", () => {
    // Hide confirmation section initially
    const confirmSec = document.getElementById("confirmation-section");
    if (confirmSec) confirmSec.style.display = "none";
});
