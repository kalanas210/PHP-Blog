<?php
require_once __DIR__ . '/db.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user data
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, username, email, full_name, country, bio, profile_photo, role, auto_approve, banned FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    return $user;
}

// Check if user is admin
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

// Check if user is author
function isAuthor() {
    $user = getCurrentUser();
    return $user && ($user['role'] === 'author' || $user['role'] === 'admin');
}

// Check if user is banned
function isBanned() {
    $user = getCurrentUser();
    return $user && $user['banned'] == 1;
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// Require admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

// Require author
function requireAuthor() {
    requireLogin();
    if (!isAuthor()) {
        header('Location: index.php');
        exit;
    }
}

// Login user
function loginUser($username, $password) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id, username, email, password, role, banned FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user && password_verify($password, $user['password'])) {
        if ($user['banned'] == 1) {
            return ['success' => false, 'message' => 'Your account has been banned.'];
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        return ['success' => true, 'user' => $user];
    }
    
    return ['success' => false, 'message' => 'Invalid username or password.'];
}

// Register user
function registerUser($username, $email, $password, $full_name = '') {
    $conn = getDB();
    
    // Check if username or email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'Username or email already exists.'];
    }
    $stmt->close();
    
    // Insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $default_avatar = 'default-avatar.png';
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, profile_photo, role) VALUES (?, ?, ?, ?, ?, 'user')");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $default_avatar);
    
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $stmt->close();
        
        // Auto login
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        
        return ['success' => true, 'user_id' => $user_id];
    }
    
    $stmt->close();
    return ['success' => false, 'message' => 'Registration failed. Please try again.'];
}
?>

