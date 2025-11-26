<?php
require_once '../settings/core.php';
if (!checkLogin()) header("Location: ../login/login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with Counselor | GBVAid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box { height: 400px; background: #fff; border-radius: 10px; overflow-y: auto; padding: 20px; border: 1px solid #eee; }
        .message { margin-bottom: 15px; padding: 10px 15px; border-radius: 15px; max-width: 75%; }
        .message.sent { background-color: #c453eaff; color: white; margin-left: auto; border-bottom-right-radius: 2px; }
        .message.received { background-color: #f1f0f0; color: #333; margin-right: auto; border-bottom-left-radius: 2px; }
    </style>
</head>
<body style="background-color: #f8f9fa;">

<?php include '../views/navbar.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div style="width: 40px; height: 40px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #059669; margin-right: 10px;">
                            <i class="bi bi-person-heart"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Counselor Support</h5>
                            <small class="text-muted">Online • Replies typically in 1 hour</small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body bg-light">
                    <div class="chat-box mb-3" id="chatBox">
                        <div class="message received">
                            Hello <?= htmlspecialchars($_SESSION['name']); ?>. I am a certified counselor with GBVAid. This is a safe space. How are you feeling today?
                        </div>
                        </div>

                    <form action="" method="POST" class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Type your message here..." required>
                        <button type="button" class="btn text-white" style="background-color: #c453eaff;" onclick="alert('Real-time chat server not connected yet. This is a UI Demo.')">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>