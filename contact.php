<?php
require_once __DIR__ . '/includes/header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($content)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real application, you would send an email here
        // For now, we'll just show a success message
        $message = 'Thank you for contacting us! We have received your message and will get back to you within 24-48 hours.';
    }
}

$page_title = 'Contact Us';
?>

<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contact Information -->
            <div class="lg:col-span-1">
                <div class="bg-gray-900 text-white rounded-lg shadow-lg p-8 h-full">
                    <h2 class="text-2xl font-bold mb-6">Get in Touch</h2>
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <h3 class="font-semibold">Email</h3>
                            </div>
                            <a href="mailto:contact@ksanews.com" class="text-gray-300 hover:text-white text-sm">contact@ksanews.com</a>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-phone text-gray-400"></i>
                                <h3 class="font-semibold">Phone</h3>
                            </div>
                            <p class="text-gray-300 text-sm">+94 77 123 4567</p>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                <h3 class="font-semibold">Address</h3>
                            </div>
                            <p class="text-gray-300 text-sm">115/2<br>John Rodrigo Mawatha<br>Moratuwa, Sri Lanka</p>
                        </div>
                        <div class="pt-4 border-t border-gray-700">
                            <h3 class="font-semibold mb-3">Follow Us</h3>
                            <div class="flex gap-3">
                                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Contact Us</h1>
                    <p class="text-gray-600 mb-8">Have a question or want to get in touch? Fill out the form below and we'll respond as soon as possible.</p>
                    
                    <?php if ($message): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="space-y-6">
                        <div>
                            <label for="name" class="block text-gray-900 font-medium mb-2">Full Name *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 bg-white text-gray-900">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-900 font-medium mb-2">Email Address *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 bg-white text-gray-900">
                        </div>
                        
                        <div>
                            <label for="subject" class="block text-gray-900 font-medium mb-2">Subject *</label>
                            <input type="text" 
                                   id="subject" 
                                   name="subject" 
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 bg-white text-gray-900">
                        </div>
                        
                        <div>
                            <label for="content" class="block text-gray-900 font-medium mb-2">Message *</label>
                            <textarea id="content" 
                                      name="content" 
                                      rows="6"
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 bg-white text-gray-900"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors font-medium">
                            Send Message
                        </button>
                    </form>
                    
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3">Other Ways to Reach Us</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <strong class="text-gray-900">Editorial:</strong> <a href="mailto:editorial@ksanews.com" class="text-blue-600 hover:text-blue-700">editorial@ksanews.com</a>
                            </div>
                            <div>
                                <strong class="text-gray-900">Support:</strong> <a href="mailto:support@ksanews.com" class="text-blue-600 hover:text-blue-700">support@ksanews.com</a>
                            </div>
                            <div>
                                <strong class="text-gray-900">Partnerships:</strong> <a href="mailto:partnerships@ksanews.com" class="text-blue-600 hover:text-blue-700">partnerships@ksanews.com</a>
                            </div>
                            <div>
                                <strong class="text-gray-900">General:</strong> <a href="mailto:info@ksanews.com" class="text-blue-600 hover:text-blue-700">info@ksanews.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
