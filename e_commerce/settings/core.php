// Settings/core.php
<?php
session_start();


//for header redirection
ob_start();

//funtion to check for login
function check_login() {
    if (!isset($_SESSION['id'])) {
        header("Location: ../Login/login_register.php");
        return false;
    } else {
        return true;
    }
}


//redirect to Admin dashboard if user is admin
function isAdmin() {
    if (check_login()) {
        return $_SESSION['role'] === '2';
    }
    return false;
}

?>