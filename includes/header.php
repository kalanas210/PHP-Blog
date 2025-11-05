<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
$current_user = getCurrentUser();
$categories = getCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?php echo SITE_URL; ?>/assets/css/styles.css" rel="stylesheet">
    <script>
        window.SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
</head>
<body class="bg-white">
    <!-- Header -->
    <header class="border-b border-gray-200 relative">
        <!-- Header Row 1 -->
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-3 md:py-4">
                <!-- Left: Menu Icon (Mobile) -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 hover:text-gray-900 p-2">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div class="hidden lg:block"></div>
                
                <!-- Center: Logo/Title -->
                <div class="flex-1 text-center lg:flex-1">
                    <a href="index.php" class="flex items-center justify-center gap-2">
                        <?php if (defined('SITE_LOGO') && SITE_LOGO): ?>
                            <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars(SITE_LOGO); ?>" 
                                 alt="<?php echo SITE_NAME; ?>" 
                                 class="h-8 md:h-10 object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <span class="text-2xl md:text-3xl font-bold text-gray-900" style="display: none;"><?php echo SITE_NAME; ?></span>
                        <?php else: ?>
                            <span class="text-2xl md:text-3xl font-bold text-gray-900"><?php echo SITE_NAME; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Right: Sign Up/Login or Profile -->
                <div class="flex items-center gap-2 md:gap-4">
                    <?php if ($current_user): ?>
                        <div class="relative">
                            <button id="profile-menu-btn" class="flex items-center gap-1 md:gap-2 text-gray-700 hover:text-gray-900 p-1">
                                <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($current_user['profile_photo']); ?>" 
                                     alt="Profile" 
                                     class="w-7 h-7 md:w-8 md:h-8 rounded-full object-cover"
                                     onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                                <span class="hidden md:inline text-sm"><?php echo htmlspecialchars($current_user['username']); ?></span>
                                <i class="fas fa-chevron-down text-xs hidden md:inline"></i>
                            </button>
                            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                <a href="dashboard/index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                                <a href="dashboard/profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <?php if (isAdmin()): ?>
                                    <a href="admin/index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-cog mr-2"></i>Admin Panel
                                    </a>
                                <?php endif; ?>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="px-3 py-1.5 md:px-4 md:py-2 text-sm md:text-base text-gray-700 hover:text-gray-900">Login</a>
                        <a href="register.php" class="px-3 py-1.5 md:px-4 md:py-2 bg-black text-white rounded hover:bg-gray-900 text-sm md:text-base">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Navigation Row 2 -->
        <nav class="border-t border-gray-200 relative">
            <div class="container mx-auto px-4">
                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center justify-center">
                    <ul class="flex items-center gap-6 py-4">
                        <li><a href="index.php" class="text-gray-700 hover:text-gray-900 font-medium">Home</a></li>
                        <?php 
                        // Get only header categories (6 categories)
                        $header_cats = getCategories('header');
                        foreach ($header_cats as $cat): 
                            // Get 3 posts from this category for the dropdown
                            $cat_posts = getPosts(3, 0, $cat['slug'], 'published');
                        ?>
                            <li class="relative group">
                                <a href="index.php?category=<?php echo $cat['slug']; ?>" class="text-gray-700 hover:text-gray-900 font-medium flex items-center gap-1">
                                    <?php echo htmlspecialchars($cat['name']); ?> <i class="fas fa-chevron-down text-xs"></i>
                                </a>
                                <?php if (!empty($cat_posts)): ?>
                                    <div class="hidden group-hover:block absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50 p-4">
                                        <div class="grid grid-cols-1 gap-3">
                                            <?php foreach ($cat_posts as $post): ?>
                                                <a href="post.php?slug=<?php echo $post['slug']; ?>" class="flex items-start gap-3 p-2 rounded hover:bg-gray-50 transition-colors">
                                                    <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($post['featured_image'] ?? 'default.jpg'); ?>" 
                                                         alt="<?php echo htmlspecialchars($post['title']); ?>"
                                                         class="w-20 h-20 object-cover rounded flex-shrink-0"
                                                         onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default.jpg'">
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1"><?php echo htmlspecialchars($post['title']); ?></h4>
                                                        <p class="text-xs text-gray-500"><?php echo formatDate($post['published_at'] ?? $post['created_at']); ?></p>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-gray-200 text-center">
                                            <a href="index.php?category=<?php echo $cat['slug']; ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                                View all <?php echo htmlspecialchars($cat['name']); ?> posts →
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        <li><a href="index.php#authors" class="text-gray-700 hover:text-gray-900 font-medium">Author list</a></li>
                    </ul>
                    
                    <div class="flex items-center gap-6 ml-6">
                        <a href="index.php#newsletter" class="text-gray-700 hover:text-gray-900 font-medium">Newsletter</a>
                        <div class="flex items-center">
                            <button type="button" id="search-toggle-btn" class="text-gray-700 hover:text-gray-900 focus:outline-none cursor-pointer">
                                <i class="fas fa-search text-lg"></i>
                            </button>
                            <form method="GET" action="index.php" id="search-form" class="hidden ml-3 flex items-center">
                                <input type="text" 
                                       name="search" 
                                       id="search-input"
                                       placeholder="Search..." 
                                       class="px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-48 text-sm"
                                       autocomplete="off">
                                <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-r-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Navigation -->
                <div class="lg:hidden flex items-center justify-between py-3">
                    <a href="index.php#newsletter" class="text-gray-700 hover:text-gray-900 font-medium text-sm">Newsletter</a>
                    <div class="flex items-center gap-3">
                        <button type="button" id="search-toggle-btn-mobile" class="text-gray-700 hover:text-gray-900 focus:outline-none cursor-pointer p-2">
                            <i class="fas fa-search text-lg"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Search Form -->
                <div id="search-form-mobile-container" class="lg:hidden hidden pb-3">
                    <form method="GET" action="index.php" id="search-form-mobile" class="flex items-center w-full">
                        <input type="text" 
                               name="search" 
                               id="search-input-mobile"
                               placeholder="Search..." 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                               autocomplete="off">
                        <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-r-lg hover:bg-gray-800 focus:outline-none">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="lg:hidden hidden fixed top-full left-0 w-full bg-white border-b border-gray-200 shadow-lg z-50 max-h-[80vh] overflow-y-auto">
                <div class="container mx-auto px-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-900">Menu</h3>
                        <button id="mobile-menu-close" class="text-gray-700 hover:text-gray-900 p-2">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <ul class="py-4">
                        <li><a href="index.php" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 font-medium">Home</a></li>
                        <?php 
                        // Get only header categories for mobile menu
                        $header_cats_mobile = getCategories('header');
                        foreach ($header_cats_mobile as $cat): ?>
                            <li><a href="index.php?category=<?php echo $cat['slug']; ?>" class="block px-4 py-3 text-gray-700 hover:bg-gray-100"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                        <?php endforeach; ?>
                        <li><a href="index.php#authors" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">Author list</a></li>
                        <li><a href="index.php#newsletter" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">Newsletter</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="min-h-screen">
