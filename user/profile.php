<?php
require_once '../settings/core.php';
require_once '../classes/customer_class.php';
require_once '../controllers/customer_controller.php'; 

if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch fresh user data from database
$user_info = get_customer_ctr($_SESSION['id']);

// Fallback values in case database fetch fails
$user_name = $user_info['customer_name'] ?? $_SESSION['name'];
$user_email = $user_info['customer_email'] ?? 'Email not found';
$user_phone = $user_info['customer_contact'] ?? ''; 
$user_image = $user_info['customer_image'] ?? ''; // If you have images later
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

<?php 
if (file_exists('../includes/navbar.php')) {
    include '../includes/navbar.php';
} else {
    include '../views/navbar.php';
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow p-4" style="border-radius: 15px;">
                <h3 class="fw-bold mb-4 text-center" style="color: #c453eaff;">Profile Settings</h3>
                
                <div class="text-center mb-4">
                    <div style="width: 100px; height: 100px; background: #f3e8ff; color: #c453eaff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 40px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user_name); ?>" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user_email); ?>" disabled>
                        <small class="text-muted"><i class="bi bi-info-circle"></i> To change your email, please contact support.</small>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user_phone); ?>" placeholder="+233 XX XXX XXXX">
                    </div>
                    
                    <button type="button" class="btn w-100 text-white fw-bold" style="background-color: #c453eaff; border-radius: 50px; padding: 12px;">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>