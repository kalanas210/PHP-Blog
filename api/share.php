<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';

if (empty($slug)) {
    echo json_encode(['success' => false, 'message' => 'Post slug is required']);
    exit;
}

$share_url = SITE_URL . '/post.php?slug=' . urlencode($slug);

echo json_encode([
    'success' => true,
    'url' => $share_url,
    'message' => 'Share URL generated'
]);
?>
