<?php
session_start();

echo "<h2>Session Test</h2>";

// Set a test session
if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = 'Session working!';
    $_SESSION['timestamp'] = time();
}

echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session Save Path: " . session_save_path() . "\n";
echo "Session Data:\n";
print_r($_SESSION);
echo "\n\nPHP Info:\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Session Cookie Params:\n";
print_r(session_get_cookie_params());
echo "</pre>";

echo "<p><a href='test_session.php'>Refresh Page</a></p>";
echo "<p>If 'test' value persists after refresh, sessions are working.</p>";
?>