<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$post = getPostBySlug($slug);

if (!$post || $post['status'] !== 'published') {
    header('Location: index.php');
    exit;
}

// Increment views
incrementPostViews($post['id']);

// Get likes count and check if current user liked
$likes_count = getPostLikesCount($post['id']);
$user_liked = false;
if (isLoggedIn()) {
    $user_liked = hasUserLiked($post['id'], $_SESSION['user_id']);
}

// Get comments
$comments = getComments($post['id']);

$page_title = $post['title'];
?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <article class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <?php if ($post['featured_image']): ?>
            <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                 class="w-full h-95 object-cover"
                 onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
        <?php endif; ?>
        
        <div class="p-8">
            <?php if ($post['category_name']): ?>
                <span class="text-xs font-semibold text-gray-600 uppercase"><?php echo htmlspecialchars($post['category_name']); ?></span>
            <?php endif; ?>
            
            <h1 class="text-4xl font-bold text-gray-900 mt-4 mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
            
            <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                <span><?php echo formatDate($post['published_at'] ?? $post['created_at']); ?></span>
                <?php if ($post['full_name']): ?>
                    <span>by <?php echo htmlspecialchars($post['full_name']); ?></span>
                <?php endif; ?>
                <span><?php echo $post['views']; ?> views</span>
            </div>
            
            <?php if ($post['excerpt']): ?>
                <p class="text-xl text-gray-600 mb-6 italic"><?php echo htmlspecialchars($post['excerpt']); ?></p>
            <?php endif; ?>
            
            <div class="prose max-w-none mb-8">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>
            
            <!-- Like, Share, Comment Section -->
            <div class="border-t border-gray-200 pt-6 mt-8">
                <div class="flex items-center gap-6 mb-6">
                    <!-- Like Button -->
                    <button id="like-btn" 
                            data-post-id="<?php echo $post['id']; ?>"
                            class="flex items-center gap-2 px-4 py-2 rounded <?php echo $user_liked ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>">
                        <i class="fas fa-heart <?php echo $user_liked ? 'fas' : 'far'; ?>"></i>
                        <span id="likes-count"><?php echo $likes_count; ?></span>
                    </button>
                    
                    <!-- Share Button -->
                    <button id="share-btn" 
                            data-post-slug="<?php echo $post['slug']; ?>"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                        <i class="fas fa-share-alt"></i>
                        <span>Share</span>
                    </button>
                </div>
                
                <!-- Share URL Display (hidden by default) -->
                <div id="share-url-container" class="hidden mb-4 p-4 bg-gray-50 rounded">
                    <p class="text-sm text-gray-600 mb-2">Share this post:</p>
                    <div class="flex items-center gap-2">
                        <input type="text" 
                               id="share-url" 
                               value="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" 
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded text-sm">
                        <button onclick="copyShareUrl()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                            Copy
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 id="comments-heading" class="text-xl font-bold text-gray-900 mb-4">Comments (<?php echo count($comments); ?>)</h3>
                
                <!-- Comment Form -->
                <form id="comment-form" class="mb-8">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <div class="mb-4">
                        <?php if (!isLoggedIn()): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <input type="text" 
                                       name="name" 
                                       placeholder="Your Name" 
                                       required
                                       class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="email" 
                                       name="email" 
                                       placeholder="Your Email" 
                                       required
                                       class="px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        <?php endif; ?>
                        <textarea name="content" 
                                  rows="4" 
                                  placeholder="Write a comment..." 
                                  required
                                  class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-black text-white rounded hover:bg-gray-900">
                        Post Comment
                    </button>
                </form>
                
                <!-- Comments List -->
                <div id="comments-list" class="space-y-6">
                    <?php foreach ($comments as $comment): ?>
                        <div class="border-b border-gray-200 pb-4 last:border-0">
                            <div class="flex items-start gap-4">
                                <?php if ($comment['profile_photo']): ?>
                                    <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($comment['profile_photo']); ?>" 
                                         alt="<?php echo htmlspecialchars($comment['name'] ?? $comment['username']); ?>"
                                         class="w-10 h-10 rounded-full object-cover"
                                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($comment['name'] ?? $comment['username'] ?? 'Anonymous'); ?></h4>
                                        <span class="text-xs text-gray-500"><?php echo formatDate($comment['created_at']); ?></span>
                                    </div>
                                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($comments)): ?>
                        <p class="text-gray-500 text-center py-8">No comments yet. Be the first to comment!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </article>
</div>

<script>
function copyShareUrl() {
    const urlInput = document.getElementById('share-url');
    urlInput.select();
    urlInput.setSelectionRange(0, 99999); // For mobile devices
    document.execCommand('copy');
    alert('URL copied to clipboard!');
}
</script>

<?php 
$page_title = $post['title'];
require_once __DIR__ . '/includes/footer.php'; 
?>
