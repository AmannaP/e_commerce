<?php
// user/report_incident.php
require_once '../settings/core.php';
if (!checkLogin()) header("Location: ../login/login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Incident | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

<?php include '../views/navbar.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header text-white p-4" style="background-color: #c453eaff">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-shield-exclamation me-2"></i>Report an Incident</h4>
                    <small>Your safety is our priority. This report is confidential.</small>
                </div>
                <div class="card-body p-4">
                    <form action="../actions/submit_report_action.php" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type of Incident</label>
                            <select name="incident_type" class="form-select" required>
                                <option value="">Select type...</option>
                                <option value="Physical Violence">Physical Violence</option>
                                <option value="Emotional Abuse">Emotional Abuse</option>
                                <option value="Sexual Harassment">Sexual Harassment</option>
                                <option value="Cyber Stalking">Cyber Stalking/Bullying</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date of Incident</label>
                                <input type="date" name="incident_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Location (City/Area)</label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. Accra, Legon Campus" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Please describe what happened..." required></textarea>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonCheck">
                            <label class="form-check-label" for="anonCheck">
                                Submit this report anonymously (My name will not be shown to counselors)
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg" style="background-color: #c453eaff">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>