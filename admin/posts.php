<?php
// Include auth first (before any output)
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$conn = getDB();

// Handle actions (before including header to avoid output before redirect)
if (isset($_GET['approve'])) {
    $post_id = (int)$_GET['approve'];
    $stmt = $conn->prepare("UPDATE posts SET status = 'published', published_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();
    header('Location: posts.php');
    exit;
}

if (isset($_GET['unpublish'])) {
    $post_id = (int)$_GET['unpublish'];
    $stmt = $conn->prepare("UPDATE posts SET status = 'unpublished' WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();
    header('Location: posts.php');
    exit;
}

if (isset($_GET['delete'])) {
    $post_id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();
    header('Location: posts.php');
    exit;
}

// Get all posts
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$query = "
    SELECT p.*, u.username, u.full_name, c.name as category_name
    FROM posts p
    LEFT JOIN users u ON p.author_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE 1=1
";

if ($status_filter) {
    $query .= " AND p.status = '" . $conn->real_escape_string($status_filter) . "'";
}

$query .= " ORDER BY p.created_at DESC";

$posts = $conn->query($query);

// Include header after all redirects are handled
$page_title = 'Manage Posts';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Posts</h1>
        <div class="flex gap-2">
            <a href="index.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Back to Dashboard
            </a>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex gap-2">
            <a href="posts.php" class="px-4 py-2 <?php echo !$status_filter ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded">
                All
            </a>
            <a href="posts.php?status=pending" class="px-4 py-2 <?php echo $status_filter === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded">
                Pending
            </a>
            <a href="posts.php?status=published" class="px-4 py-2 <?php echo $status_filter === 'published' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded">
                Published
            </a>
            <a href="posts.php?status=draft" class="px-4 py-2 <?php echo $status_filter === 'draft' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'; ?> rounded">
                Draft
            </a>
        </div>
    </div>
    
    <!-- Posts Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if ($posts->num_rows === 0): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">No posts found</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($post = $posts->fetch_assoc()): ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="../post.php?slug=<?php echo $post['slug']; ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo htmlspecialchars($post['full_name'] ?? $post['username']); ?>
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
                                        <?php if ($post['status'] === 'pending'): ?>
                                            <a href="?approve=<?php echo $post['id']; ?>" 
                                               class="text-green-600 hover:text-green-700" 
                                               title="Approve">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($post['status'] === 'published'): ?>
                                            <a href="?unpublish=<?php echo $post['id']; ?>" 
                                               class="text-yellow-600 hover:text-yellow-700" 
                                               title="Unpublish">
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
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
