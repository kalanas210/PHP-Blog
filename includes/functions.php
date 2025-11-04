<?php
require_once __DIR__ . '/db.php';

// Generate slug from title
function generateSlug($title) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    
    // Make unique
    $conn = getDB();
    $original_slug = $slug;
    $counter = 1;
    
    while (true) {
        $stmt = $conn->prepare("SELECT id FROM posts WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result->num_rows == 0) {
            break;
        }
        
        $slug = $original_slug . '-' . $counter;
        $counter++;
    }
    
    return $slug;
}

// Get post by ID
function getPost($id) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT p.*, u.username, u.full_name, u.profile_photo, c.name as category_name, c.slug as category_slug
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    
    return $post;
}

// Get post by slug
function getPostBySlug($slug) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT p.*, u.username, u.full_name, u.profile_photo, c.name as category_name, c.slug as category_slug
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.slug = ?
    ");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    
    return $post;
}

// Get posts with filters
function getPosts($limit = 10, $offset = 0, $category = null, $status = 'published', $author_id = null) {
    $conn = getDB();
    
    $query = "
        SELECT p.*, u.username, u.full_name, u.profile_photo, c.name as category_name, c.slug as category_slug
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE 1=1
    ";
    
    $params = [];
    $types = '';
    
    if ($status) {
        $query .= " AND p.status = ?";
        $params[] = $status;
        $types .= 's';
    }
    
    if ($category) {
        $query .= " AND c.slug = ?";
        $params[] = $category;
        $types .= 's';
    }
    
    if ($author_id) {
        $query .= " AND p.author_id = ?";
        $params[] = $author_id;
        $types .= 'i';
    }
    
    $query .= " ORDER BY p.published_at DESC, p.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];
    
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    $stmt->close();
    return $posts;
}

// Get popular posts (based on views and likes)
function getPopularPosts($limit = 4) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT p.*, 
               u.username, 
               u.full_name, 
               u.profile_photo, 
               c.name as category_name, 
               c.slug as category_slug,
               COUNT(DISTINCT pl.id) as likes_count,
               p.views
        FROM posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN post_likes pl ON p.id = pl.post_id
        WHERE p.status = 'published'
        GROUP BY p.id
        ORDER BY (p.views * 1 + COUNT(DISTINCT pl.id) * 5) DESC, p.published_at DESC
        LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];
    
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    $stmt->close();
    return $posts;
}

// Check if user liked a post
function hasUserLiked($post_id, $user_id) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $liked = $result->num_rows > 0;
    $stmt->close();
    return $liked;
}

// Get post likes count
function getPostLikesCount($post_id) {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'];
}

// Get comments for a post
function getComments($post_id) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT c.*, u.username, u.profile_photo
        FROM comments c
        LEFT JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ? AND c.approved = 1
        ORDER BY c.created_at ASC
    ");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];
    
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    
    $stmt->close();
    return $comments;
}

// Get all categories
function getCategories() {
    $conn = getDB();
    $result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = [];
    
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    
    return $categories;
}

// Get authors
function getAuthors($limit = 10) {
    $conn = getDB();
    $stmt = $conn->prepare("
        SELECT u.*, COUNT(p.id) as post_count
        FROM users u
        LEFT JOIN posts p ON u.id = p.author_id AND p.status = 'published'
        WHERE u.role IN ('author', 'admin') AND u.banned = 0
        GROUP BY u.id
        ORDER BY post_count DESC
        LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $authors = [];
    
    while ($row = $result->fetch_assoc()) {
        $authors[] = $row;
    }
    
    $stmt->close();
    return $authors;
}

// Increment post views
function incrementPostViews($post_id) {
    $conn = getDB();
    $stmt = $conn->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();
}

// Format date
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

// Truncate text
function truncate($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}
?>

