<?php
require_once '../settings/core.php';

// Ensure user is logged in
if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}

// If the user is an admin, redirect to admin dashboard
if (isAdmin()) {
    header("Location: ../admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | GBVAid Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background-color: #b77a7a;
        }
        .dashboard-card {
            border: none;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .dashboard-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-custom {
            background-color: #b77a7a;
            color: white;
        }
        .btn-custom:hover {
            background-color: #a46868;
            color: white;
        }
        h2 {
            color: #b77a7a;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">GBVAid</a>
    <div class="d-flex">
        <span class="navbar-text text-white me-3">
            Welcome, <?= htmlspecialchars($_SESSION['name']); ?> 💜
        </span>
        <a href="../login/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="fw-bold text-center mb-4">My GBVAid Dashboard</h2>
    <p class="text-center text-muted mb-5">
        Access support services, get help, and find safety through trusted organizations near you.
    </p>

    <div class="row g-4 justify-content-center">

        <!-- Report an Incident -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5>📢 Report an Incident</h5>
                <p>If you or someone you know is in danger or has experienced GBV, report it confidentially.</p>
                <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
<!--                <a href="/register_sample/user/report_incident.php" class="btn btn-custom mt-2">Report Now</a> -->
            </div>
        </div>

        <!-- Find Support Services -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5>🤝 Find Support Services</h5>
                <p>Browse available support services including counseling, legal aid, and shelters near you.</p>
                <a href="../user/product_page.php" class="btn btn-custom mt-2">View Services</a>
            </div>
        </div>

        <!-- Chat with Counselor -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5>💬 Chat with a Counselor</h5>
                <p>Get emotional or psychological support through our chat system with certified counselors.</p>
                <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
<!--                <a href="/register_sample/user/chat.php" class="btn btn-custom mt-2">Start Chat</a>  -->
            </div>
        </div>

        <!-- Safety Resources -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5>🛡️ Safety Resources</h5>
                <p>Learn about protection strategies, emergency contacts, and community-based support.</p>
                <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
<!--                <a href="/register_sample/user/resources.php" class="btn btn-custom mt-2">Access Resources</a>  -->
            </div>
        </div>

        <!-- My Profile -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5>👤 My Profile</h5>
                <p>Update your profile, view your reports, and manage your preferences securely.</p>
                <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
<!--                <a href="/register_sample/user/profile.php" class="btn btn-custom mt-2">Manage Profile</a>  -->
            </div>
        </div>

        <!-- Contact Support -->
        <div class="col-md-4">
            <div class="card dashboard-card p-4 text-center">
                <h5>📞 Contact Support</h5>
                <p>Reach out to GBVAid’s help desk if you need immediate assistance or follow-up.</p>
                <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
<!--                <a href="/register_sample/user/support.php" class="btn btn-custom mt-2">Contact Us</a>  -->
            </div>
        </div>

    </div>
</div>

<footer class="text-center text-muted mt-5 mb-3">
    <small>© <?= date('Y'); ?> GBVAid — Empowering safety and support for all.</small>
</footer>

</body>
</html>
