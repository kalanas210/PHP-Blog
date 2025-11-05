<?php
// Include auth first (before any output)
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

// Include header after authentication check
require_once __DIR__ . '/../includes/header.php';

$conn = getDB();

// Stats
$stats = [
    'total_posts' => $conn->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'],
    'pending_posts' => $conn->query("SELECT COUNT(*) as count FROM posts WHERE status = 'pending'")->fetch_assoc()['count'],
    'total_users' => $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
    'total_authors' => $conn->query("SELECT COUNT(*) as count FROM users WHERE role IN ('author', 'admin')")->fetch_assoc()['count']
];

$page_title = 'Admin Dashboard';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>
    
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Total Posts</h3>
            <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total_posts']; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Pending Posts</h3>
            <p class="text-3xl font-bold text-yellow-600"><?php echo $stats['pending_posts']; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Total Users</h3>
            <p class="text-3xl font-bold text-blue-600"><?php echo $stats['total_users']; ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-gray-600 text-sm font-medium mb-2">Authors</h3>
            <p class="text-3xl font-bold text-green-600"><?php echo $stats['total_authors']; ?></p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="posts.php" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Manage Posts</h2>
            <p class="text-gray-600">Approve, unpublish, or delete posts</p>
        </a>
        <a href="users.php" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Manage Users</h2>
            <p class="text-gray-600">Ban users, grant permissions, manage roles</p>
        </a>
        <a href="categories.php" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Manage Categories</h2>
            <p class="text-gray-600">Add, edit, and configure categories</p>
        </a>
    </div>
    
    <!-- Recent Pending Posts -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Recent Pending Posts</h2>
        </div>
        <div class="p-6">
            <?php
            $pending = $conn->query("
                SELECT p.*, u.username, u.full_name, c.name as category_name
                FROM posts p
                LEFT JOIN users u ON p.author_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'pending'
                ORDER BY p.created_at DESC
                LIMIT 5
            ");
            
            if ($pending->num_rows > 0):
            ?>
                <div class="space-y-4">
                    <?php while ($post = $pending->fetch_assoc()): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="text-sm text-gray-600">by <?php echo htmlspecialchars($post['full_name'] ?? $post['username']); ?></p>
                            </div>
                            <div class="flex gap-2">
                                <a href="posts.php?approve=<?php echo $post['id']; ?>" 
                                   class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                    Approve
                                </a>
                                <a href="posts.php?unpublish=<?php echo $post['id']; ?>" 
                                   class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 text-sm">
                                    Unpublish
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="mt-4 text-center">
                    <a href="posts.php" class="text-blue-600 hover:text-blue-700">View all posts »</a>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No pending posts</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
