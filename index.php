<?php
// index.php

// require_once 'settings/core.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GBVAid | GBV Support Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fffaf9;
            color: #333;
        }

        .menu-tray {
            position: fixed;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.97);
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            padding: 8px 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            z-index: 1000;
        }

        .menu-tray a {
            margin-left: 8px;
        }

        .hero {
            height: 90vh;
            background: linear-gradient(to right, #b77a7a, #d59a9a);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            max-width: 700px;
        }

        .hero p {
            max-width: 650px;
            margin: 1rem auto;
            font-size: 1.15rem;
        }

        .btn-custom {
            background-color: #fff;
            color: #b77a7a;
            border: 2px solid white;
            font-weight: 600;
        }

        .btn-custom:hover {
            background-color: #f6f0f0;
            color: #a85c5c;
        }

        .features {
            padding: 5rem 0;
        }

        .feature-card {
            border: none;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s;
            background: white;
            border-radius: 12px;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        footer {
            background-color: #b77a7a;
            color: #fff;
            padding: 2.5rem 0;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Menu -->
    <div class="menu-tray">
		<a href="login/register.php" class="btn btn-sm btn-outline-light" style="background-color: #b77a7a; color: white;">Register</a>
		<a href="login/login.php" class="btn btn-sm btn-outline-light" style="background-color: #b77a7a; color: white;">Login</a>
		<!-- <a href="#about" class="btn btn-sm btn-outline-light" style="background-color: #b77a7a; color: white;">About</a>
		<a href="#contact" class="btn btn-sm btn-outline-light" style="background-color: #b77a7a; color: white;">Contact</a> -->
	</div>

    <!-- Hero Section -->
    <section class="hero">
		<h1 class="fw-bold mb-4">GBVAid: Your Digital Ally Against Gender-Based Violence</h1>
        <h2>Empowering Voices. Restoring Safety. Building Hope.</h2>
        <p>
            A digital platform connecting individuals affected by gender-based violence (GBV)  
            to verified support services — including legal, medical, counseling, and emergency aid — across Ghana and Africa.
        </p>
            <div class="mt-3">
                <a href="login/register.php" class="btn btn-lg btn-custom me-2">Join the Platform</a>
                <a href="login/login.php" class="btn btn-lg btn-outline-light">Login</a>
            </div>
    </section>

    <!-- Platform Summary -->
    <section class="features container text-center">
        <h2 class="fw-bold mb-5" style="color:#b77a7a;">What We Offer</h2>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card p-4 shadow-sm">
                   <!-- increase the font size of the icon and remove the margin-bottom -->
                    <a href="./user/product_page.php" class="fw-bold" style="color:#b77a7a; font-size: 1.5rem;">🩺 Access to Services</a>
                    <p>Find verified healthcare, legal, and counseling support near you. We ensure confidentiality and compassion at every step.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-4 shadow-sm">
                    <h4 style="color:#b77a7a;">🕊 Safe Reporting</h4>
                    <p>Report GBV incidents securely and anonymously. Our trusted partners respond promptly with the help you need.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-4 shadow-sm">
                    <h4 style="color:#b77a7a;">🤝 Community Empowerment</h4>
                    <p>Join awareness programs, advocacy campaigns, and survivor-led initiatives for lasting social change.</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="card feature-card p-4 shadow-sm">
                    <h4 style="color:#b77a7a;">📞 24/7 Support Lines</h4>
                    <p>Connect instantly with trained responders who provide real-time guidance and care, any time you need it.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-4 shadow-sm">
                    <h4 style="color:#b77a7a;">💡 Education & Resources</h4>
                    <p>Access articles, guides, and training materials to understand, prevent, and respond to GBV effectively.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card p-4 shadow-sm">
                    <h4 style="color:#b77a7a;">🔐 Data Privacy</h4>
                    <p>Your safety matters. All interactions and reports are encrypted and stored securely to protect your identity.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p class="mb-1 fw-semibold">©<?= date('Y'); ?> GBVAid | All Rights Reserved.</p>
        <p class="text-light small">Built in Ghana to empower GBV survivors, advocates, and communities through technology.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
