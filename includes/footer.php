    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About Us -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">About us</h3>
                    <p class="text-gray-600 text-sm">
                        Welcome to <?php echo SITE_NAME; ?>, your destination for thoughtful articles, 
                        engaging stories, and insightful perspectives on various topics.
                    </p>
                </div>
                
                <!-- Categories -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <?php 
                        $footer_categories = getCategories();
                        foreach ($footer_categories as $cat): 
                        ?>
                            <li>
                                <a href="index.php?category=<?php echo $cat['slug']; ?>" class="text-gray-600 hover:text-gray-900 text-sm">
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-600 hover:text-gray-900 text-sm">Home</a></li>
                        <li><a href="index.php#authors" class="text-gray-600 hover:text-gray-900 text-sm">Authors</a></li>
                        <li><a href="index.php#newsletter" class="text-gray-600 hover:text-gray-900 text-sm">Newsletter</a></li>
                    </ul>
                </div>
                
                <!-- Legal -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="terms.php" class="text-gray-600 hover:text-gray-900 text-sm">Terms & Conditions</a></li>
                        <li><a href="privacy.php" class="text-gray-600 hover:text-gray-900 text-sm">Privacy Policy</a></li>
                        <li><a href="contact.php" class="text-gray-600 hover:text-gray-900 text-sm">Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-200 mt-8 pt-8 text-center">
                <p class="text-gray-600 text-sm mb-2">
                    &copy; <?php echo date('Y'); ?> KSA News - All rights reserved. Published with Ryzera PVT LTD by <a href="https://www.linkedin.com/in/kalanasandakelum/" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700">Kalana Sandakelum</a>
                </p>
                <p class="text-gray-600 text-sm">
                    <a href="https://ryzera.io" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700">ryzera.io</a>
                </p>
            </div>
        </div>
    </footer>
    
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    <?php if (isset($page_title) && strpos($_SERVER['REQUEST_URI'], 'post.php') !== false): ?>
        <script src="<?php echo SITE_URL; ?>/assets/js/like-share.js"></script>
    <?php endif; ?>
    <?php if (isset($page_title) && strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>
        <script src="<?php echo SITE_URL; ?>/assets/js/dashboard.js"></script>
    <?php endif; ?>
</body>
</html>
