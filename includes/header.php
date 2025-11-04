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
    <!-- Header Row 1 -->
    <header class="border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Left: Menu Icon -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 hover:text-gray-900">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <div class="hidden lg:block"></div>
                
                <!-- Center: Logo/Title -->
                <div class="flex-1 text-center">
                    <a href="index.php" class="text-3xl font-bold text-gray-900"><?php echo SITE_NAME; ?></a>
                </div>
                
                <!-- Right: Sign Up/Login or Profile -->
                <div class="flex items-center gap-4">
                    <?php if ($current_user): ?>
                        <div class="relative">
                            <button id="profile-menu-btn" class="flex items-center gap-2 text-gray-700 hover:text-gray-900">
                                <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($current_user['profile_photo']); ?>" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full object-cover"
                                     onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
                                <span class="hidden md:inline"><?php echo htmlspecialchars($current_user['username']); ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-50">
                                <?php if (isAuthor()): ?>
                                    <a href="dashboard/index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                <?php endif; ?>
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
                        <a href="login.php" class="px-4 py-2 text-gray-700 hover:text-gray-900">Login</a>
                        <a href="register.php" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Navigation Row 2 -->
        <nav class="border-t border-gray-200">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    <ul class="hidden lg:flex items-center gap-6 py-4">
                        <li><a href="index.php" class="text-gray-700 hover:text-gray-900 font-medium">Home</a></li>
                        <li class="relative group">
                            <a href="#" class="text-gray-700 hover:text-gray-900 font-medium flex items-center gap-1">
                                Posts <i class="fas fa-chevron-down text-xs"></i>
                            </a>
                            <ul class="hidden group-hover:block absolute top-full left-0 mt-1 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-2 z-50">
                                <?php foreach ($categories as $cat): ?>
                                    <li><a href="index.php?category=<?php echo $cat['slug']; ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                            <li class="relative group">
                                <a href="index.php?category=<?php echo $cat['slug']; ?>" class="text-gray-700 hover:text-gray-900 font-medium flex items-center gap-1">
                                    <?php echo htmlspecialchars($cat['name']); ?> <i class="fas fa-chevron-down text-xs"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li><a href="index.php#authors" class="text-gray-700 hover:text-gray-900 font-medium">Author list</a></li>
                        <li><a href="index.php#newsletter" class="text-gray-700 hover:text-gray-900 font-medium">Newsletter</a></li>
                    </ul>
                    
                    <!-- Mobile Menu -->
                    <div id="mobile-menu" class="lg:hidden hidden absolute top-full left-0 w-full bg-white border-b border-gray-200 shadow-lg z-50">
                        <ul class="py-4">
                            <li><a href="index.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Home</a></li>
                            <?php foreach ($categories as $cat): ?>
                                <li><a href="index.php?category=<?php echo $cat['slug']; ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"><?php echo htmlspecialchars($cat['name']); ?></a></li>
                            <?php endforeach; ?>
                            <li><a href="index.php#authors" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Author list</a></li>
                            <li><a href="index.php#newsletter" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Newsletter</a></li>
                        </ul>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="index.php#newsletter" class="text-gray-700 hover:text-gray-900">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="min-h-screen">
