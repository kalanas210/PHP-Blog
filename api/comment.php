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

$conn = getDB();
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

$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, name, email, content) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisss", $post_id, $user_id, $name, $email, $content);

if ($stmt->execute()) {
    $comment_id = $stmt->insert_id;
    $stmt->close();
    
    // Get the new comment
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_photo
        FROM comments c
        LEFT JOIN users u ON c.user_id = u.id
        WHERE c.id = ?
    ");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comment = $result->fetch_assoc();
    $stmt->close();
    
    echo json_encode([
        'success' => true, 
        'comment' => $comment,
        'message' => 'Comment posted successfully'
    ]);
} else {
    $stmt->close();
    echo json_encode(['success' => false, 'message' => 'Failed to post comment']);
}
?>
