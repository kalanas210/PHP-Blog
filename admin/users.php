<?php
// Include auth first (before any output)
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$conn = getDB();

// Handle actions (before including header to avoid output before redirect)
if (isset($_GET['ban'])) {
    $user_id = (int)$_GET['ban'];
    $stmt = $conn->prepare("UPDATE users SET banned = 1 WHERE id = ? AND id != ?");
    $stmt->bind_param("ii", $user_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php');
    exit;
}

if (isset($_GET['unban'])) {
    $user_id = (int)$_GET['unban'];
    $stmt = $conn->prepare("UPDATE users SET banned = 0 WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php');
    exit;
}

if (isset($_GET['auto_approve'])) {
    $user_id = (int)$_GET['auto_approve'];
    $value = isset($_GET['enable']) ? 1 : 0;
    $stmt = $conn->prepare("UPDATE users SET auto_approve = ? WHERE id = ?");
    $stmt->bind_param("ii", $value, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php');
    exit;
}

if (isset($_GET['make_author'])) {
    $user_id = (int)$_GET['make_author'];
    $stmt = $conn->prepare("UPDATE users SET role = 'author' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: users.php');
    exit;
}

// Get all users
$users = $conn->query("
    SELECT u.*, COUNT(p.id) as post_count
    FROM users u
    LEFT JOIN posts p ON u.id = p.author_id
    GROUP BY u.id
    ORDER BY u.created_at DESC
");

// Include header after all redirects are handled
$page_title = 'Manage Users';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
        <a href="index.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
            Back to Dashboard
        </a>
    </div>
    
    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($user['profile_photo']); ?>" 
                                         alt="<?php echo htmlspecialchars($user['username']); ?>"
                                         class="w-10 h-10 rounded-full object-cover"
                                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                                    <div>
                                        <p class="font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></p>
                                        <?php if ($user['full_name']): ?>
                                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['full_name']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : ($user['role'] === 'author' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'); ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo $user['post_count']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($user['banned']): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Banned</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Active</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <?php if ($user['auto_approve']): ?>
                                    <span class="text-green-600">Auto-approve</span>
                                <?php else: ?>
                                    <span class="text-gray-500">Manual</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <?php if ($user['banned']): ?>
                                            <a href="?unban=<?php echo $user['id']; ?>" 
                                               class="text-green-600 hover:text-green-700" 
                                               title="Unban">
                                                <i class="fas fa-unlock"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?ban=<?php echo $user['id']; ?>" 
                                               class="text-red-600 hover:text-red-700" 
                                               title="Ban"
                                               onclick="return confirm('Are you sure you want to ban this user?')">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($user['role'] !== 'author' && $user['role'] !== 'admin'): ?>
                                            <a href="?make_author=<?php echo $user['id']; ?>" 
                                               class="text-blue-600 hover:text-blue-700" 
                                               title="Make Author">
                                                <i class="fas fa-user-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($user['role'] === 'author' || $user['role'] === 'admin'): ?>
                                            <?php if ($user['auto_approve']): ?>
                                                <a href="?auto_approve=<?php echo $user['id']; ?>&enable=0" 
                                                   class="text-yellow-600 hover:text-yellow-700" 
                                                   title="Disable Auto-approve">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="?auto_approve=<?php echo $user['id']; ?>&enable=1" 
                                                   class="text-green-600 hover:text-green-700" 
                                                   title="Enable Auto-approve">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
