<?php
require_once '../settings/core.php';
require_once '../controllers/order_controller.php';

// 1. Set Timezone to Ghana
date_default_timezone_set('Africa/Accra');

requireLogin('../login/login.php');

$customer_id = getUserId();
$invoice_no = isset($_GET['invoice']) ? htmlspecialchars($_GET['invoice']) : '';
$reference = isset($_GET['reference']) ? htmlspecialchars($_GET['reference']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* 1. Purple Background */
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #c453eaff; 
            min-height: 100vh;
        }
        
        /* Navbar Styling */
        .navbar { 
            background: black; 
            padding: 20px 0; 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); 
        }
        .nav-container { 
            max-width: 1400px; 
            margin: 0 auto; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0 40px; 
        }
        .logo { 
            font-family: 'Segoe UI', serif; 
            font-weight: bold;
            font-size: 28px; 
            color: #c453eaff; 
            text-decoration: none; 
        }
        .nav-link {
            color: #c453eaff;
            text-decoration: none;
            font-weight: 500;
        }
        .nav-link:hover {
            text-decoration: underline;
        }
        
        .container { max-width: 700px; margin: 60px auto; padding: 0 20px; }
        
        /* 2. Success Box (White Card) */
        .success-box { 
            background: white; 
            border-radius: 20px; 
            padding: 50px 40px; 
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .success-icon { 
            font-size: 80px; 
            margin-bottom: 20px; 
            animation: bounce 1s ease-in-out;
            color: #c453eaff;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        h1 { 
            font-family: 'Segoe UI', serif; 
            font-size: 2.5rem; 
            color: #c453eaff; 
            margin-bottom: 10px; 
        }
        
        .subtitle { 
            font-size: 18px; 
            color: #6b7280; 
            margin-bottom: 30px; 
        }
        
        .order-details { 
            background: #f9fafb; 
            padding: 30px; 
            border-radius: 12px; 
            margin: 30px 0; 
            text-align: left;
            border: 1px solid #e5e7eb;
        }
        
        .detail-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; }
        .detail-value { color: #6b7280; word-break: break-all; }
        
        /* 3. Buttons */
        .btn { 
            padding: 14px 30px; 
            border: none; 
            border-radius: 50px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: all 0.3s ease; 
            text-decoration: none; 
            display: inline-block;
            margin: 10px;
        }
        
        .btn-primary { 
            background-color: #c453eaff; 
            color: white; 
            border: 2px solid #c453eaff;
            box-shadow: 0 4px 15px rgba(196, 83, 234, 0.4); 
        }
        
        .btn-primary:hover { 
            background-color: white; 
            color: #c453eaff;
            transform: translateY(-2px);
        }
        
        .btn-secondary { 
            background: white; 
            color: #c453eaff; 
            border: 2px solid #c453eaff; 
        }
        
        .btn-secondary:hover { 
            background: #f3e8ff; 
        }
        
        .buttons-container { 
            display: flex; 
            justify-content: center; 
            margin-top: 30px; 
            flex-wrap: wrap;
        }
        
        /* 4. Confirmation Message */
        .confirmation-message { 
            background: #f3e8ff; 
            border: 1px solid #d8b4fe; 
            padding: 20px; 
            border-radius: 12px; 
            color: #7e22ce;
            margin-bottom: 20px;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="../index.php" class="logo">GBVAid</a>
            <div style="display: flex; gap: 20px;">
                <a href="../user/product_page.php" class="nav-link">← Continue Shopping</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="success-box">
            <div class="success-icon">🎉</div>
            <h1>Order Successful!</h1>
            <p class="subtitle">Your payment has been processed successfully</p>
            
            <div class="confirmation-message">
                <strong><i class="bi bi-check-circle-fill"></i> Payment Confirmed</strong><br>
                Thank you for your purchase! Your order has been confirmed and will be processed shortly.
            </div>
            
            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">Invoice Number</span>
                    <span class="detail-value" style="color: #c453eaff; font-weight: bold;"><?php echo $invoice_no; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Reference</span>
                    <span class="detail-value"><?php echo $reference; ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Date</span>
                    <span class="detail-value"><?php echo date('F j, Y - h:i A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #10b981; font-weight: 600;">Paid <i class="bi bi-check-lg"></i></span>
                </div>
            </div>
            
            <div class="buttons-container">
                <a href="my_orders.php" class="btn btn-primary">📦 View My Orders</a>
                <a href="../user/product_page.php" class="btn btn-secondary">Continue Shopping</a>
            </div>
        </div>
    </div>

    <script>
        // Confetti effect (Updated colors for Purple Theme)
        function createConfetti() {
            // Purple, Dark Purple, White, Gold, Green
            const colors = ['#c453eaff', '#a020f0', '#ffffff', '#ffd700', '#10b981'];
            const confettiCount = 60;
            
            for (let i = 0; i < confettiCount; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    
                    confetti.style.cssText = `
                        position: fixed;
                        width: 10px;
                        height: 10px;
                        background: ${color};
                        left: ${Math.random() * 100}%;
                        top: -10px;
                        opacity: 1;
                        transform: rotate(${Math.random() * 360}deg);
                        z-index: 10001;
                        pointer-events: none;
                        border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                    `;
                    
                    document.body.appendChild(confetti);
                    
                    const duration = 2000 + Math.random() * 1000;
                    const startTime = Date.now();
                    
                    function animateConfetti() {
                        const elapsed = Date.now() - startTime;
                        const progress = elapsed / duration;
                        
                        if (progress < 1) {
                            const top = progress * (window.innerHeight + 50);
                            // Add horizontal sway
                            const wobble = Math.sin(progress * 10) * 30;
                            
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
        
        // Trigger confetti on page load
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>