<?php
// Include auth first (before any output)
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$current_user = getCurrentUser();

// Get author's posts
$conn = getDB();
$stmt = $conn->prepare("
    SELECT p.*, c.name as category_name, 
           COUNT(DISTINCT pl.id) as likes_count,
           COUNT(DISTINCT cm.id) as comments_count
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN post_likes pl ON p.id = pl.post_id
    LEFT JOIN comments cm ON p.id = cm.post_id AND cm.approved = 1
    WHERE p.author_id = ?
    GROUP BY p.id
    ORDER BY p.created_at DESC
");
$stmt->bind_param("i", $current_user['id']);
$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();

// Include header after authentication check
$page_title = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <?php if (isAuthor()): ?>
            <a href="new-post.php" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>New Post
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Total Posts</h3>
            <p class="text-3xl font-bold text-gray-900"><?php echo count($posts); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Published</h3>
            <p class="text-3xl font-bold text-green-600">
                <?php echo count(array_filter($posts, fn($p) => $p['status'] === 'published')); ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Drafts</h3>
            <p class="text-3xl font-bold text-yellow-600">
                <?php echo count(array_filter($posts, fn($p) => $p['status'] === 'draft')); ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Total Views</h3>
            <p class="text-3xl font-bold text-blue-600">
                <?php echo array_sum(array_column($posts, 'views')); ?>
            </p>
        </div>
    </div>
    
    <!-- Posts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">My Posts</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No posts yet. 
                                <?php if (isAuthor()): ?>
                                    <a href="new-post.php" class="text-blue-600 hover:text-blue-700">Create your first post</a>
                                <?php else: ?>
                                    <span class="text-gray-400">You need to be an author to create posts.</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="../post.php?slug=<?php echo $post['slug']; ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($post['category_name'] ?? 'Uncategorized'); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $status_colors = [
                                        'published' => 'bg-green-100 text-green-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'unpublished' => 'bg-red-100 text-red-800'
                                    ];
                                    $color = $status_colors[$post['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded <?php echo $color; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo $post['views']; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo formatDate($post['created_at']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="edit-post.php?id=<?php echo $post['id']; ?>" 
                                           class="text-blue-600 hover:text-blue-700" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($post['status'] === 'published'): ?>
                                            <a href="?unpublish=<?php echo $post['id']; ?>" 
                                               class="text-yellow-600 hover:text-yellow-700" 
                                               title="Unpublish"
                                               onclick="return confirm('Are you sure you want to unpublish this post?')">
                                                <i class="fas fa-eye-slash"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="?delete=<?php echo $post['id']; ?>" 
                                           class="text-red-600 hover:text-red-700" 
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this post?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Handle delete and unpublish actions
if (isset($_GET['delete'])) {
    $post_id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND author_id = ?");
    $stmt->bind_param("ii", $post_id, $current_user['id']);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php');
    exit;
}

if (isset($_GET['unpublish'])) {
    $post_id = (int)$_GET['unpublish'];
    $stmt = $conn->prepare("UPDATE posts SET status = 'unpublished' WHERE id = ? AND author_id = ?");
    $stmt->bind_param("ii", $post_id, $current_user['id']);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php');
    exit;
}
?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
