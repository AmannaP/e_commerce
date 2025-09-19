<?php
// Settings/core.php

// Start session only if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For header redirection
ob_start();

/**
 * Redirect user to login page if not logged in
 */
function checkLogin() {
    if (!isset($_SESSION['id'])) {
        header("Location: ../Login/login_register.php");
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
 * Force only admins to access a page
 */
function requireAdmin() {
    if (!checkUserRole('admin')) {
        header("Location: ../errors/403.php"); // optional forbidden page
        exit;
    }
}

/**
 * Force only customers to access a page
 */
function requireCustomer() {
    if (!checkUserRole('customer')) {
        header("Location: ../errors/403.php");
        exit;
    }
}
