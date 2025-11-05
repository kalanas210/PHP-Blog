<?php
require_once __DIR__ . '/includes/header.php';

$author_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$author = getAuthorById($author_id);

if (!$author) {
    header('Location: index.php');
    exit;
}

// Get author's posts
$author_posts = getPosts(20, 0, null, 'published', $author_id);

$page_title = ($author['full_name'] ?? $author['username']) . ' - Author Profile';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Author Profile Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Author Photo -->
                <div class="flex-shrink-0">
                    <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($author['profile_photo']); ?>" 
                         alt="<?php echo htmlspecialchars($author['full_name'] ?? $author['username']); ?>"
                         class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg"
                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                </div>
                
                <!-- Author Info -->
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <?php echo htmlspecialchars($author['full_name'] ?? $author['username']); ?>
                    </h1>
                    <?php if ($author['username'] && $author['full_name']): ?>
                        <p class="text-gray-600 text-lg mb-3">@<?php echo htmlspecialchars($author['username']); ?></p>
                    <?php endif; ?>
                    
                    <?php if ($author['bio']): ?>
                        <p class="text-gray-700 mb-4 leading-relaxed"><?php echo nl2br(htmlspecialchars($author['bio'])); ?></p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                        <?php if ($author['country']): ?>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($author['country']); ?>
                            </span>
                        <?php endif; ?>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-calendar-alt"></i>
                            Joined <?php echo formatDate($author['created_at']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Author Stats -->
        <div class="p-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 mb-1"><?php echo number_format($author['post_count'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Published Posts</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 mb-1"><?php echo number_format($author['total_views'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Total Views</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 mb-1"><?php echo number_format($author['total_likes'] ?? 0); ?></div>
                    <div class="text-sm text-gray-600">Total Likes</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Author's Posts Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                Posts by <?php echo htmlspecialchars($author['full_name'] ?? $author['username']); ?>
                <span class="text-gray-500 text-lg font-normal">(<?php echo count($author_posts); ?>)</span>
            </h2>
        </div>
        
        <?php if (!empty($author_posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($author_posts as $post): 
                    $post_likes = getPostLikesCount($post['id']);
                    $post_views = $post['views'] ?? 0;
                ?>
                    <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                        <a href="post.php?slug=<?php echo $post['slug']; ?>">
                            <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($post['featured_image'] ?? 'default.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                 class="w-full h-48 object-cover"
                                 onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                        </a>
                        <div class="p-5">
                            <?php if ($post['category_name']): ?>
                                <span class="text-xs font-semibold text-gray-600 uppercase"><?php echo htmlspecialchars($post['category_name']); ?></span>
                            <?php endif; ?>
                            <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2 line-clamp-2">
                                <a href="post.php?slug=<?php echo $post['slug']; ?>" class="hover:text-blue-600">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h3>
                            <?php if ($post['excerpt']): ?>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo htmlspecialchars(truncate($post['excerpt'], 120)); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span><?php echo formatDate($post['published_at'] ?? $post['created_at']); ?></span>
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <?php echo number_format($post_views); ?>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-heart"></i>
                                        <?php echo number_format($post_likes); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <p class="text-gray-500 text-lg mb-4">No posts published yet.</p>
                <p class="text-gray-400">This author hasn't published any posts.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Back to Authors Link -->
    <div class="text-center mb-8">
        <a href="index.php#authors" class="text-blue-600 hover:text-blue-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Authors List
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

