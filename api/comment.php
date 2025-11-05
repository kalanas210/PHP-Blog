<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$content = trim($_POST['content'] ?? '');

if (!$post_id || empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Post ID and content are required']);
    exit;
}

// Log received content for debugging (remove in production if needed)
error_log("Comment API: Received content: " . substr($content, 0, 100));

$conn = getDB();

// Check if user is banned (if logged in)
if (isLoggedIn()) {
    $current_user = getCurrentUser();
    if ($current_user && isBanned()) {
        echo json_encode(['success' => false, 'message' => 'Your account has been banned. You cannot post comments.']);
        exit;
    }
}

// Get post and author info to check auto_approve setting
$stmt = $conn->prepare("
    SELECT p.id, p.author_id, u.auto_approve 
    FROM posts p 
    LEFT JOIN users u ON p.author_id = u.id 
    WHERE p.id = ?
");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post_info = $result->fetch_assoc();
$stmt->close();

if (!$post_info) {
    echo json_encode(['success' => false, 'message' => 'Post not found']);
    exit;
}

// Determine if comment should be auto-approved
// Comments are auto-approved if the post author has auto_approve enabled
$approved = ($post_info['auto_approve'] == 1) ? 1 : 0;

$user_id = null;
$name = null;
$email = null;

if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
} else {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($name) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Name and email are required for guest comments']);
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, name, email, content, approved) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssi", $post_id, $user_id, $name, $email, $content, $approved);

if ($stmt->execute()) {
    $comment_id = $stmt->insert_id;
    $stmt->close();
    
    // Get the new comment with explicit field selection
    $stmt = $conn->prepare("
        SELECT c.id, c.post_id, c.user_id, c.name, c.email, c.content, c.approved, c.created_at, u.username, u.profile_photo
        FROM comments c
        LEFT JOIN users u ON c.user_id = u.id
        WHERE c.id = ?
    ");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comment = $result->fetch_assoc();
    $stmt->close();
    
    // Ensure content is properly set
    if (!isset($comment['content']) || $comment['content'] === null) {
        error_log("Warning: Comment content is missing for comment ID: " . $comment_id);
    }
    
    $message = $approved ? 'Comment posted successfully' : 'Comment submitted and pending approval';
    
    echo json_encode([
        'success' => true, 
        'comment' => $comment,
        'message' => $message,
        'approved' => $approved
    ]);
} else {
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Failed to post comment']);
}
?>
