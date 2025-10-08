<?php
// settings/core.php

// Start session only if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For header redirection
ob_start();

/**
 * Redirect user to login page if not logged in
 * @return bool
 */
function checkLogin() {
    if (!isset($_SESSION['id']) && empty($_SESSION['id'])) {
        header("Location: ../login/login.php");
        exit;
    }
}

/**
 * Force login: redirect to login page if user not logged in
 */
function requireLogin(): void {
    if (!checkLogin()) {
        header("Location: ../login/login.php");
        exit;
    }
}

/**
 * Check if current user is an admin
 * @return bool
 */
function isAdmin(): bool {

    if (checkLogin() && isset($_SESSION['role']) && $_SESSION['role'] === '2'){
        return true;
    }
    return false;
}

/**
 * Force only admins to access a page redirect if not admin
 */
function requireAdmin(): void {
    if (!isAdmin()) {
        header("Location: ../admin/category.php");
        exit;
    }
}

/**
 * Get logged-in user ID
 * @return int|null
 */
function getUserId() {
    return isset($_SESSION['id']) ? $_SESSION['id'] : null;
}

/**
 * Get logged-in user role
 * @return string|null
 */
function getUserRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

/**
 * Check if user has a specific role
 * @param string $role
 * @return bool
 */
function checkUserRole($role) {
    return (isset($_SESSION['role']) && $_SESSION['role'] === $role);
}


/**
 * Securely log out user
 */
function logout(): void {
    // Clear session data
    $_SESSION = [];
    session_unset();
    session_destroy();

    // Redirect to login page
    header("Location: ../login/login.php");
    exit;
}

?>
