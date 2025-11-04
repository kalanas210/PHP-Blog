<?php
require_once __DIR__ . '/includes/header.php';

$category_filter = isset($_GET['category']) ? $_GET['category'] : null;

// Get featured posts (hero section)
$featured_posts = getPosts(1, 0, null, 'published');
$featured_post = !empty($featured_posts) ? $featured_posts[0] : null;

// Get latest posts for sidebar
$latest_posts = getPosts(5, 0, null, 'published');

// Get posts for left column
$left_posts = getPosts(2, 1, null, 'published');

// Get posts by category
$category_sections = [];
if (!$category_filter) {
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
    
    <!-- Category Sections -->
    <?php foreach ($category_sections as $cat_name => $cat_data): ?>
        <section class="mb-16">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($cat_name); ?></h2>
                <a href="index.php?category=<?php echo $cat_data['slug']; ?>" class="text-blue-600 hover:text-blue-700 font-medium">View all »</a>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Large Card on Left -->
                <?php if (!empty($cat_data['posts'])): ?>
                    <div class="lg:col-span-2">
                        <article class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow h-full">
                            <a href="post.php?slug=<?php echo $cat_data['posts'][0]['slug']; ?>">
                                <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($cat_data['posts'][0]['featured_image'] ?? 'default.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($cat_data['posts'][0]['title']); ?>"
                                     class="w-full h-64 object-cover"
                                     onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                            </a>
                            <div class="p-6">
                                <span class="text-xs font-semibold text-gray-600 uppercase"><?php echo htmlspecialchars($cat_data['posts'][0]['category_name'] ?? $cat_name); ?></span>
                                <h3 class="text-xl font-bold text-gray-900 mt-2 mb-3">
                                    <a href="post.php?slug=<?php echo $cat_data['posts'][0]['slug']; ?>" class="hover:text-blue-600">
                                        <?php echo htmlspecialchars($cat_data['posts'][0]['title']); ?>
                                    </a>
                                </h3>
                                <?php if ($cat_data['posts'][0]['excerpt']): ?>
                                    <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars(truncate($cat_data['posts'][0]['excerpt'], 120)); ?></p>
                                <?php endif; ?>
                                <p class="text-sm text-gray-500"><?php echo formatDate($cat_data['posts'][0]['published_at'] ?? $cat_data['posts'][0]['created_at']); ?></p>
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
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
