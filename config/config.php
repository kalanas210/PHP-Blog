<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '12345');
define('DB_NAME', 'blog_app');

// Site Configuration
define('SITE_NAME', 'KSA News');
define('SITE_URL', 'http://localhost/blog');
define('SITE_LOGO', 'logo.png'); // Set to false or empty string to use text only

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../assets/images/');
define('UPLOAD_MAX_SIZE', 5242880); // 5MB

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

