    </main>
    
    <!-- Scroll to Top Button (Bottom Left) -->
    <button id="scroll-to-top-bottom" class="hidden fixed bottom-8 left-8 bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-900 transition-all z-50">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    <!-- Scroll to Bottom Button (Bottom Right) -->
    <button id="scroll-to-bottom" class="hidden fixed bottom-8 right-8 bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-900 transition-all z-50">
        <i class="fas fa-chevron-down"></i>
    </button>
    
    <!-- Scroll to Top Button (Top Right) -->
    <!-- <button id="scroll-to-top-top" class="hidden fixed top-8 right-8 bg-gray-800 text-white p-3 rounded-full shadow-lg hover:bg-gray-900 transition-all z-50">
        <i class="fas fa-chevron-up"></i>
    </button> -->
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <!-- Top Footer Bar -->
        <div class="border-b border-gray-200">
            <div class="container mx-auto px-4 py-4">
                <div class="flex items-center justify-center">
                    <div class="flex items-center gap-6">
                        <a href="about.php" class="text-gray-700 hover:text-gray-900 text-sm">About</a>
                        <a href="privacy.php" class="text-gray-700 hover:text-gray-900 text-sm">Privacy</a>
                        <a href="help.php" class="text-gray-700 hover:text-gray-900 text-sm">Help</a>
                        <a href="terms.php" class="text-gray-700 hover:text-gray-900 text-sm">Terms</a>
                        <a href="contact.php" class="text-gray-700 hover:text-gray-900 text-sm">Contact</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Footer Content -->
        <div class="container mx-auto px-4 py-12">
            <div class="text-center">
                <!-- Site Name -->
                <h2 class="text-4xl font-bold text-gray-900 mb-4 uppercase"><?php echo SITE_NAME; ?></h2>
                
                <!-- Copyright -->
                <p class="text-gray-600 text-sm mb-2">
                    &copy;<?php echo date('Y'); ?> - All rights reserved. Published By <a href="https://www.linkedin.com/in/kalanasandakelum/" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700">Kalana Sandakelum</a> .
                </p>
                
                <!-- Powered by -->
                <p class="text-gray-600 text-sm mb-8">
                    Powered by <a href="https://ryzera.io" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700">KSA</a> Labs.
                </p>
                
                <!-- Social Media Icons -->
                <div class="flex items-center justify-center gap-4">
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-colors" title="Facebook">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-colors" title="Twitter">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-colors" title="X">
                        <i class="fab fa-x-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-700 hover:text-gray-900 transition-colors" title="Instagram">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="mailto:contact@ksanews.com" class="text-gray-700 hover:text-gray-900 transition-colors" title="Email">
                        <i class="fas fa-envelope text-xl"></i>
                    </a>
                    <a href="https://ryzera.io" target="_blank" rel="noopener noreferrer" class="text-gray-700 hover:text-gray-900 transition-colors" title="Website">
                        <i class="fas fa-globe text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    <?php if (isset($page_title) && strpos($_SERVER['REQUEST_URI'], 'post.php') !== false): ?>
        <script src="<?php echo SITE_URL; ?>/assets/js/like-share.js"></script>
    <?php endif; ?>
    <?php if (isset($page_title) && strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false): ?>
        <script src="<?php echo SITE_URL; ?>/assets/js/dashboard.js"></script>
        <?php if (strpos($_SERVER['REQUEST_URI'], 'new-post.php') !== false || strpos($_SERVER['REQUEST_URI'], 'edit-post.php') !== false): ?>
            <script src="<?php echo SITE_URL; ?>/assets/js/editor.js"></script>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
