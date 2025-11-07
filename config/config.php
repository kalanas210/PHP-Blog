<?php
/**
 * Load environment variables from .env file
 * 
 * @param string $path Path to .env file
 * @return void
 */
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE format
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }
            
            // Set environment variable if not already set
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Load .env file from project root
loadEnv(__DIR__ . '/../.env');

// Helper function to get env variable with default
function env($key, $default = null) {
    $value = getenv($key);
    if ($value === false) {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
    return $value === '' ? $default : $value;
}

// Database Configuration
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'blog_app'));

// Site Configuration
define('SITE_NAME', env('SITE_NAME', 'KSA News'));
define('SITE_URL', env('SITE_URL', 'http://localhost/blog'));
define('SITE_LOGO', env('SITE_LOGO', 'logo.png')); // Set to false or empty string to use text only

// File Upload Configuration
define('UPLOAD_DIR', env('UPLOAD_DIR', __DIR__ . '/../assets/images/'));
define('UPLOAD_MAX_SIZE', (int)env('UPLOAD_MAX_SIZE', 5242880)); // 5MB

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

// Error Reporting (set to 0 in production)
$errorReporting = env('ERROR_REPORTING', 'E_ALL');
$displayErrors = env('DISPLAY_ERRORS', '1');
error_reporting($errorReporting === '0' ? 0 : constant($errorReporting));
ini_set('display_errors', (int)$displayErrors);
?>

