// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checkout page loaded');
    // Load checkout summary
    loadCheckoutSummary();
});

/**
 * Load checkout summary
 */
function loadCheckoutSummary() {
    const container = document.getElementById('checkoutItemsContainer');
    
    if (!container) {
        console.error('Container not found: checkoutItemsContainer');
        return;
    }
    
    // Show loading state with purple theme
    container.innerHTML = '<div style="text-align: center; padding: 40px; color: #c453eaff;">Loading order summary...</div>';
    
    console.log('Fetching cart data from get_cart_action.php...');
    
    fetch('../actions/get_cart_action.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log('Parsed data:', data);
            
            if (data.status === 'success') {
                if (data.items && data.items.length > 0) {
                    console.log('Displaying', data.items.length, 'items');
                    // Store items globally for payment processing
                    window.currentCartItems = data.items;
                    displayCheckoutItems(data.items, data.total_price);                
                } else {
                    console.log('Cart is empty, redirecting...');
                    // Cart is empty, redirect back
                    showToast('Your cart is empty', 'error');
                    setTimeout(() => {
                        window.location.href = 'cart.php';
                    }, 2000);
                }
            } else {
                console.error('Error status from server:', data.message);
                container.innerHTML = '<div style="text-align: center; padding: 40px; color: #c453eaff;">Error loading order summary: ' + (data.message || 'Unknown error') + '</div>';
            }
        } catch (e) {
            console.error('JSON Parse error:', e);
            console.error('Text was:', text);
            container.innerHTML = '<div style="text-align: center; padding: 40px; color: #c453eaff;">Server response error. Please check console.</div>';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        container.innerHTML = '<div style="text-align: center; padding: 40px; color: #c453eaff;">Failed to load order summary. Please check your connection.</div>';
    });
}

/**
 * Display checkout items with purple theme
 */
function displayCheckoutItems(items, total) {
    const container = document.getElementById('checkoutItemsContainer');
    container.innerHTML = '';
    
    items.forEach((item, index) => {
        const imagePath = item.product_image ? 
            (item.product_image.startsWith('../') ? item.product_image : '../uploads/products/' + item.product_image) :
            'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="60" height="60"%3E%3Crect width="60" height="60" fill="%23e598ffff"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-size="10" fill="%23c453eaff"%3ENo Image%3C/text%3E%3C/svg%3E';
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'checkout-item';
        itemDiv.style.cssText = 'display: flex; gap: 20px; padding: 20px; border-bottom: 1px solid #e598ffff; align-items: center; background-color: #fff;';
        
        itemDiv.innerHTML = `
            <img src="${escapeHtml(imagePath)}" 
                 alt="${escapeHtml(item.product_title)}" 
                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; flex-shrink: 0; border: 2px solid #e598ffff;"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22%3E%3Crect width=%2260%22 height=%2260%22 fill=%22%23e598ffff%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-size=%2210%22 fill=%22%23c453eaff%22%3ENo Image%3C/text%3E%3C/svg%3E'">
            <div style="flex: 1;">
                <div style="font-weight: 600; margin-bottom: 4px; color: #333;">${escapeHtml(item.product_title)}</div>
                <div style="font-size: 13px; color: #666;">Qty: ${item.qty} × GH₵ ${parseFloat(item.product_price).toFixed(2)}</div>
            </div>
            <div style="font-weight: 700; color: #c453eaff; font-size: 1.1rem;">
                GH₵ ${parseFloat(item.total_price).toFixed(2)}
            </div>
        `;
        
        container.appendChild(itemDiv);
    });
    
    const totalElement = document.getElementById('checkoutTotal');
    if (totalElement) {
        totalElement.textContent = `GH₵ ${parseFloat(total).toFixed(2)}`;
    }
    
    // Store total for payment modal
    window.checkoutTotal = parseFloat(total).toFixed(2);
}

/**
 * Show payment modal with purple theme
 */
function showPaymentModal() {
    const modal = document.getElementById('paymentModal');
    const amountDisplay = document.getElementById('paymentAmount');
    
    if (!modal) {
        console.error('Payment modal not found');
        return;
    }
    
    if (amountDisplay) {
        amountDisplay.textContent = `GH₵ ${window.checkoutTotal || '0.00'}`;
    }
    
    modal.style.display = 'flex';
    
    // Add animation
    setTimeout(() => {
        modal.style.opacity = '1';
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
        }
    }, 10);
}

/**
 * Close payment modal
 */
function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    if (!modal) return;
    
    modal.style.opacity = '0';
    
    const modalContent = modal.querySelector('.modal-content');
    if (modalContent) {
        modalContent.style.transform = 'scale(0.9)';
    }
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

/**
 * Process checkout via Paystack
 */
function processCheckout() {
    const confirmBtn = document.getElementById('confirmPaymentBtn');
    
    if (!confirmBtn) {
        console.error('Confirm button not found');
        return;
    }
    
    const originalText = confirmBtn.textContent;
    
    // Disable button and show loading
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Redirecting to Paystack...';
    confirmBtn.style.backgroundColor = '#e598ffff';
    
    // Get customer email from session or prompt
    const customerEmail = prompt('Please enter your email for payment:', '');
    
    if (!customerEmail || !validateEmail(customerEmail)) {
        confirmBtn.disabled = false;
        confirmBtn.textContent = originalText;
        confirmBtn.style.backgroundColor = '#c453eaff';
        showToast('Please enter a valid email address', 'error');
        return;
    }
    
    console.log('Initializing Paystack transaction...');
    
    // Step 1: Initialize Paystack transaction
    fetch('../actions/paystack_init_transaction.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            amount: window.checkoutTotal,
            email: customerEmail
        })
    })
    .then(response => {
        console.log('Paystack response status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log('Paystack init response:', data);
            
            if (data.status === 'success') {
                // Store data for verification after payment
                window.paymentReference = data.reference;
                window.cartItems = window.currentCartItems || null;
                window.totalAmount = window.checkoutTotal;
                
                // Redirect to Paystack payment page
                closePaymentModal();
                showToast('Redirecting to secure payment...', 'success');
                
                setTimeout(() => {
                    window.location.href = data.authorization_url;
                }, 1500);
            } else {
                showToast(data.message || 'Failed to initialize payment', 'error');
                confirmBtn.disabled = false;
                confirmBtn.textContent = originalText;
                confirmBtn.style.backgroundColor = '#c453eaff';
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            showToast('Server response error. Please try again.', 'error');
            confirmBtn.disabled = false;
            confirmBtn.textContent = originalText;
            confirmBtn.style.backgroundColor = '#c453eaff';
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('Payment initialization failed. Please try again.', 'error');
        confirmBtn.disabled = false;
        confirmBtn.textContent = originalText;
        confirmBtn.style.backgroundColor = '#c453eaff';
    });
}

/**
 * Validate email address
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Show success modal with order details (purple theme)
 */
function showSuccessModal(orderData) {
    const modal = document.getElementById('successModal');
    
    if (!modal) {
        console.error('Success modal not found');
        return;
    }
    
    // Populate success modal with order details
    const invoiceEl = document.getElementById('successInvoice');
    const amountEl = document.getElementById('successAmount');
    const dateEl = document.getElementById('successDate');
    const itemsEl = document.getElementById('successItems');
    
    if (invoiceEl) invoiceEl.textContent = orderData.invoice_no || 'N/A';
    if (amountEl) amountEl.textContent = `GH₵ ${orderData.total_amount || '0.00'}`;
    if (dateEl) dateEl.textContent = orderData.order_date || new Date().toLocaleString();
    if (itemsEl) itemsEl.textContent = orderData.item_count || '0';
    
    modal.style.display = 'flex';
    
    // Add animation
    setTimeout(() => {
        modal.style.opacity = '1';
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.transform = 'scale(1)';
        }
    }, 10);
    
    // Update cart count
    updateCartCount(0);
    
    // Confetti effect
    createConfetti();
}

/**
 * Continue shopping after successful order
 */
function continueShopping() {
    window.location.href = '../user/product_page.php';
}

/**
 * View orders after successful checkout
 */
function viewOrders() {
    window.location.href = 'my_orders.php';
}

/**
 * Create confetti effect for successful order (purple theme)
 */
function createConfetti() {
    const colors = ['#c453eaff', '#e598ffff', '#a020f0', '#d946ef'];
    const confettiCount = 50;
    
    for (let i = 0; i < confettiCount; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                left: ${Math.random() * 100}%;
                top: -10px;
                opacity: 1;
                transform: rotate(${Math.random() * 360}deg);
                z-index: 10001;
                pointer-events: none;
            `;
            
            document.body.appendChild(confetti);
            
            const duration = 2000 + Math.random() * 1000;
            const startTime = Date.now();
            
            function animateConfetti() {
                const elapsed = Date.now() - startTime;
                const progress = elapsed / duration;
                
                if (progress < 1) {
                    const top = progress * (window.innerHeight + 50);
                    const wobble = Math.sin(progress * 10) * 50;
                    
                    confetti.style.top = top + 'px';
                    confetti.style.left = `calc(${parseFloat(confetti.style.left)}% + ${wobble}px)`;
                    confetti.style.opacity = 1 - progress;
                    confetti.style.transform = `rotate(${progress * 720}deg)`;
                    
                    requestAnimationFrame(animateConfetti);
                } else {
                    confetti.remove();
                }
            }
            
            animateConfetti();
        }, i * 30);
    }
}

/**
 * Update cart count badge
 */
function updateCartCount(count) {
    const badges = document.querySelectorAll('.cart-count-badge');
    badges.forEach(badge => {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline' : 'none';
    });
}

/**
 * Show toast notification (purple theme)
 */
function showToast(message, type) {
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;
    
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.padding = '16px 24px';
    toast.style.borderRadius = '8px';
    toast.style.fontWeight = '600';
    toast.style.fontSize = '14px';
    toast.style.zIndex = '10000';
    toast.style.maxWidth = '400px';
    toast.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
    toast.style.animation = 'slideIn 0.3s ease';
    
    if (type === 'success') {
        toast.style.backgroundColor = '#c453eaff';
        toast.style.color = '#fff';
        toast.style.border = '2px solid #e598ffff';
    } else {
        toast.style.backgroundColor = '#fee2e2';
        toast.style.color = '#991b1b';
        toast.style.border = '2px solid #fecaca';
    }
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Close modals when clicking outside
window.onclick = function(event) {
    const paymentModal = document.getElementById('paymentModal');
    if (event.target === paymentModal) {
        closePaymentModal();
    }
    
    const successModal = document.getElementById('successModal');
    if (event.target === successModal) {
        successModal.style.display = 'none';
    }
};

// Add CSS animations if not already present
if (!document.getElementById('toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}