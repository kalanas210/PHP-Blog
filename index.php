<?php
require_once __DIR__ . '/includes/header.php';

$category_filter = isset($_GET['category']) ? $_GET['category'] : null;
$search_query = isset($_GET['search']) ? trim($_GET['search']) : null;

// Get featured posts (hero section)
$featured_posts = getPosts(1, 0, $category_filter, 'published');
$featured_post = !empty($featured_posts) ? $featured_posts[0] : null;

// Get popular posts (most popular section) - only if not filtering by category
$popular_posts = $category_filter ? [] : getPopularPosts(4);

// Get latest posts for sidebar
$latest_posts = getPosts(5, 0, $category_filter, 'published');

// Get posts for left column
$left_posts = getPosts(2, 1, $category_filter, 'published');

// Handle search
$search_results = [];
if ($search_query) {
    $search_results = searchPosts($search_query, 20, 0);
}

// Get posts by category
$category_sections = [];
if (!$category_filter && !$search_query) {
    $categories = getCategories();
    foreach ($categories as $cat) {
        $cat_posts = getPosts(5, 0, $cat['slug'], 'published');
        if (!empty($cat_posts)) {
            $category_sections[$cat['name']] = [
                'slug' => $cat['slug'],
                'posts' => $cat_posts
            ];
        }
    }
} else {
    // If filtering by category, show only that category
    $cat = getCategories();
    $selected_cat = null;
    foreach ($cat as $c) {
        if ($c['slug'] == $category_filter) {
            $selected_cat = $c;
            break;
        }
    }
    if ($selected_cat) {
        $category_sections[$selected_cat['name']] = [
            'slug' => $selected_cat['slug'],
            'posts' => getPosts(10, 0, $category_filter, 'published')
        ];
    }
}

$authors = getAuthors(6);
?>

<div class="container mx-auto px-4 py-8">
    <?php if ($search_query): ?>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Search Results</h1>
            <p class="text-gray-600">Searching for: "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
            <p class="text-gray-500 text-sm mt-1">Found <?php echo count($search_results); ?> result(s)</p>
            <a href="index.php" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">← Back to all posts</a>
        </div>
    <?php elseif ($category_filter && $selected_cat): ?>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($selected_cat['name']); ?></h1>
            <?php if ($selected_cat['description']): ?>
                <p class="text-gray-600"><?php echo htmlspecialchars($selected_cat['description']); ?></p>
            <?php endif; ?>
            <a href="index.php" class="text-blue-600 hover:text-blue-700 text-sm mt-2 inline-block">← Back to all posts</a>
        </div>
    <?php endif; ?>
    
    <?php if ($search_query): ?>
        <!-- Search Results -->
        <?php if (!empty($search_results)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                <?php foreach ($search_results as $post): ?>
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
                                <?php if ($post['full_name']): ?>
                                    <span>by <?php echo htmlspecialchars($post['full_name']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-12 text-center mb-16">
                <p class="text-gray-500 text-lg mb-4">No results found for "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
                <p class="text-gray-400">Try different keywords or <a href="index.php" class="text-blue-600 hover:text-blue-700">browse all posts</a></p>
            </div>
        <?php endif; ?>
    <?php else: ?>
    
    <!-- Main Hero Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-16">
        <!-- Left Column: Small Posts -->
        <div class="lg:col-span-3 space-y-6">
            <?php foreach ($left_posts as $post): ?>
                <article class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <a href="post.php?slug=<?php echo $post['slug']; ?>">
                        <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($post['featured_image'] ?? 'default.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                             class="w-full h-48 object-cover"
                             onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                    </a>
                    <div class="p-4">
                        <?php if ($post['category_name']): ?>
                            <span class="text-xs font-semibold text-gray-600 uppercase"><?php echo htmlspecialchars($post['category_name']); ?></span>
                        <?php endif; ?>
                        <h3 class="text-lg font-bold text-gray-900 mt-2 mb-2">
                            <a href="post.php?slug=<?php echo $post['slug']; ?>" class="hover:text-blue-600">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <p class="text-sm text-gray-500"><?php echo formatDate($post['published_at'] ?? $post['created_at']); ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Center Column: Hero Post -->
        <div class="lg:col-span-6">
            <?php if ($featured_post): ?>
                <article class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <a href="post.php?slug=<?php echo $featured_post['slug']; ?>">
                        <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($featured_post['featured_image'] ?? 'default.jpg'); ?>" 
                             alt="<?php echo htmlspecialchars($featured_post['title']); ?>"
                             class="w-full h-96 object-cover"
                             onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                    </a>
                    <div class="p-6">
                        <?php if ($featured_post['category_name']): ?>
                            <span class="text-xs font-semibold text-gray-600 uppercase"><?php echo htmlspecialchars($featured_post['category_name']); ?></span>
                        <?php endif; ?>
                        <h2 class="text-3xl font-bold text-gray-900 mt-3 mb-4">
                            <a href="post.php?slug=<?php echo $featured_post['slug']; ?>" class="hover:text-blue-600">
                                <?php echo htmlspecialchars($featured_post['title']); ?>
                            </a>
                        </h2>
                        <?php if ($featured_post['excerpt']): ?>
                            <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($featured_post['excerpt']); ?></p>
                        <?php endif; ?>
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500"><?php echo formatDate($featured_post['published_at'] ?? $featured_post['created_at']); ?></p>
                            <?php if ($featured_post['full_name']): ?>
                                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($featured_post['full_name']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <p class="text-gray-500">No posts available yet.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Right Column: Latest Posts Sidebar -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 uppercase">Latest</h3>
                <div class="space-y-4">
                    <?php foreach ($latest_posts as $post): ?>
                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="flex items-start gap-3 hover:opacity-80 transition-opacity">
                            <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($post['featured_image'] ?? 'default.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                 class="w-20 h-20 object-cover rounded flex-shrink-0"
                                 onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2"><?php echo htmlspecialchars($post['title']); ?></h4>
                                <p class="text-xs text-gray-500 mt-1"><?php echo formatDate($post['published_at'] ?? $post['created_at']); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Most Popular Posts Section -->
    <?php if (!empty($popular_posts)): ?>
        <section class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Most Popular Posts</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($popular_posts as $post): ?>
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
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2"><?php echo htmlspecialchars(truncate($post['excerpt'], 100)); ?></p>
                            <?php endif; ?>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span><?php echo formatDate($post['published_at'] ?? $post['created_at']); ?></span>
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-eye"></i>
                                        <?php echo number_format($post['views'] ?? 0); ?>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-heart"></i>
                                        <?php echo number_format($post['likes_count'] ?? 0); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    
    <!-- Category Sections -->
    <?php foreach ($category_sections as $cat_name => $cat_data): ?>
        <section class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($cat_name); ?></h2>
                <a href="index.php?category=<?php echo $cat_data['slug']; ?>" class="text-blue-600 hover:text-blue-700 font-medium">View all »</a>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Large Card on Left -->
                <?php if (!empty($cat_data['posts'])): 
                    $main_post = $cat_data['posts'][0];
                    $main_post_likes = getPostLikesCount($main_post['id']);
                    $main_post_views = $main_post['views'] ?? 0;
                    // Estimate read time (average reading speed: 200 words per minute)
                    $word_count = str_word_count(strip_tags($main_post['content'] ?? ''));
                    $read_time = max(1, round($word_count / 200));
                ?>
                    <div class="lg:col-span-2">
                        <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow h-full flex flex-col">
                            <a href="post.php?slug=<?php echo $main_post['slug']; ?>">
                                <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($main_post['featured_image'] ?? 'default.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($main_post['title']); ?>"
                                     class="w-full h-64 object-cover"
                                     onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                            </a>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xs font-semibold text-gray-600 uppercase"><?php echo htmlspecialchars($main_post['category_name'] ?? $cat_name); ?></span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500"><?php echo $read_time; ?> min read</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">
                                    <a href="post.php?slug=<?php echo $main_post['slug']; ?>" class="hover:text-blue-600">
                                        <?php echo htmlspecialchars($main_post['title']); ?>
                                    </a>
                                </h3>
                                <?php if ($main_post['excerpt']): ?>
                                    <p class="text-gray-600 text-sm mb-3 leading-relaxed font-medium"><?php echo htmlspecialchars(truncate($main_post['excerpt'], 250)); ?></p>
                                <?php endif; ?>
                                
                                <!-- Post Content Preview -->
                                <?php if ($main_post['content']): 
                                    // Strip HTML tags and get clean text
                                    $content_text = strip_tags($main_post['content']);
                                    // Remove extra whitespace
                                    $content_text = preg_replace('/\s+/', ' ', $content_text);
                                    // Get first 400 characters of content (after excerpt)
                                    $content_preview = mb_substr($content_text, 0, 400);
                                    if (strlen($content_text) > 400) {
                                        $content_preview .= '...';
                                    }
                                ?>
                                    <div class="text-gray-600 text-sm mb-4 leading-relaxed line-clamp-6">
                                        <?php echo nl2br(htmlspecialchars($content_preview)); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Author Info -->
                                <?php if ($main_post['full_name'] || $main_post['username']): ?>
                                    <div class="flex items-center gap-3 mb-4">
                                        <?php if ($main_post['profile_photo']): ?>
                                            <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($main_post['profile_photo']); ?>" 
                                                 alt="<?php echo htmlspecialchars($main_post['full_name'] ?? $main_post['username']); ?>"
                                                 class="w-10 h-10 rounded-full object-cover"
                                                 onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                                        <?php endif; ?>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($main_post['full_name'] ?? $main_post['username']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo formatDate($main_post['published_at'] ?? $main_post['created_at']); ?></p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500 mb-4"><?php echo formatDate($main_post['published_at'] ?? $main_post['created_at']); ?></p>
                                <?php endif; ?>
                                
                                <!-- Stats and Read More -->
                                <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                    <div class="flex items-center gap-4 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-eye"></i>
                                            <?php echo number_format($main_post_views); ?> views
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-heart"></i>
                                            <?php echo number_format($main_post_likes); ?> likes
                                        </span>
                                    </div>
                                    <a href="post.php?slug=<?php echo $main_post['slug']; ?>" class="text-blue-600 hover:text-blue-700 font-medium text-sm flex items-center gap-1">
                                        Read More <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                    
                    <!-- Small Cards on Right -->
                    <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach (array_slice($cat_data['posts'], 1, 4) as $post): ?>
                            <article class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                <a href="post.php?slug=<?php echo $post['slug']; ?>">
                                    <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($post['featured_image'] ?? 'default.jpg'); ?>" 
                                         alt="<?php echo htmlspecialchars($post['title']); ?>"
                                         class="w-full h-40 object-cover"
                                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                                </a>
                                <div class="p-4">
                                    <span class="text-xs font-semibold text-gray-600 uppercase"><?php echo htmlspecialchars($post['category_name'] ?? $cat_name); ?></span>
                                    <h4 class="text-base font-bold text-gray-900 mt-2 mb-2">
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="hover:text-blue-600">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h4>
                                    <?php if ($post['excerpt']): ?>
                                        <p class="text-gray-600 text-xs mb-2 line-clamp-2"><?php echo htmlspecialchars(truncate($post['excerpt'], 80)); ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-500"><?php echo formatDate($post['published_at'] ?? $post['created_at']); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>
    
    <!-- Newsletter Section -->
    <section id="newsletter" class="bg-gray-50 rounded-lg p-8 mb-16">
        <div class="max-w-2xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Newsletter</h2>
            <p class="text-gray-600 mb-6">Subscribe to get the latest updates and articles.</p>
            <form id="newsletter-form" class="flex gap-2 max-w-md mx-auto">
                <input type="email" 
                       placeholder="Enter your email" 
                       required
                       class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Subscribe
                </button>
            </form>
            <p class="text-xs text-gray-500 mt-4">Leave this field empty if you're human:</p>
        </div>
    </section>
    
    <!-- Authors Section -->
    <section id="authors" class="mb-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Authors</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <?php foreach ($authors as $author): ?>
                <div class="text-center">
                    <a href="author.php?id=<?php echo $author['id']; ?>">
                        <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($author['profile_photo']); ?>" 
                             alt="<?php echo htmlspecialchars($author['full_name'] ?? $author['username']); ?>"
                             class="w-20 h-20 rounded-full object-cover mx-auto mb-3"
                             onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                        <h3 class="font-semibold text-gray-900"><?php echo htmlspecialchars($author['full_name'] ?? $author['username']); ?></h3>
                        <p class="text-sm text-gray-500"><?php echo $author['post_count']; ?> posts</p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; // End of search results conditional ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
