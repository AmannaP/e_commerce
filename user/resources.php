<?php
require_once '../settings/core.php';
if (!checkLogin()) header("Location: ../login/login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safety Resources | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

<?php include '../views/navbar.php'; ?>

<div class="container my-5">
    <h2 class="text-center fw-bold mb-4" style="color: #c453eaff;">Safety Resources & Guides</h2>

    <div class="row g-4">
        <div class="col-md-12">
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-telephone-fill fs-2 me-3"></i>
                <div>
                    <h5 class="alert-heading fw-bold">Emergency Contacts (Ghana)</h5>
                    <p class="mb-0">DOVVSU Hotline: <strong>18555</strong> | Police Emergency: <strong>191</strong> | Ambulance: <strong>193</strong></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title fw-bold text" style="background-color: #c453eaff"><i class="bi bi-shield-check me-2"></i>Personal Safety Plan</h4>
                    <p>Steps to take if you feel unsafe at home or in your relationship.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Identify safe areas in your house with exits.</li>
                        <li class="list-group-item">Keep a charged phone with you at all times.</li>
                        <li class="list-group-item">Have a "code word" with trusted friends.</li>
                        <li class="list-group-item">Prepare an emergency bag with documents & cash.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title fw-bold text"style="background-color: #c453eaff"><i class="bi bi-journal-text me-2"></i>Know Your Rights</h4>
                    <p>Under the Domestic Violence Act 732 (2007) of Ghana:</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">You have the right to obtain a protection order.</li>
                        <li class="list-group-item">Police must offer medical assistance forms.</li>
                        <li class="list-group-item">Domestic violence includes physical, economic, and emotional abuse.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>