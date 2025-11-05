<?php
require_once __DIR__ . '/includes/header.php';

// Get all authors
$all_authors = getAuthors(1000); // Get a large number to get all authors

$page_title = 'All Authors';
?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">All Authors</h1>
        <p class="text-gray-600">Discover our community of writers and contributors</p>
    </div>
    
    <?php if (!empty($all_authors)): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6 mb-8">
            <?php foreach ($all_authors as $author): ?>
                <div class="text-center bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                    <a href="author.php?id=<?php echo $author['id']; ?>" class="block hover:opacity-80 transition-opacity">
                        <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($author['profile_photo']); ?>" 
                             alt="<?php echo htmlspecialchars($author['full_name'] ?? $author['username']); ?>"
                             class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-gray-100"
                             onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                        <h3 class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($author['full_name'] ?? $author['username']); ?></h3>
                        <?php if ($author['username'] && $author['full_name']): ?>
                            <p class="text-xs text-gray-500 mb-2">@<?php echo htmlspecialchars($author['username']); ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-gray-600 font-medium"><?php echo $author['post_count']; ?> posts</p>
                        <?php if ($author['country']): ?>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <?php echo htmlspecialchars($author['country']); ?>
                            </p>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <p class="text-gray-500 text-lg">No authors found.</p>
        </div>
    <?php endif; ?>
    
    <!-- Back to Home Link -->
    <div class="text-center mt-8">
        <a href="index.php#authors" class="text-blue-600 hover:text-blue-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Home
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

