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
    } else {
        // In a real application, you would send an email here
        $message = 'Thank you for contacting us! We will get back to you soon.';
    }
}

$page_title = 'Contact';
?>

<div class="container mx-auto px-4 py-16 max-w-2xl">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Contact Us</h1>
    
    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" class="bg-white rounded-lg shadow-lg p-8">
        <div class="mb-6">
            <label for="name" class="block text-gray-700 font-medium mb-2">Name *</label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="mb-6">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email *</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="mb-6">
            <label for="subject" class="block text-gray-700 font-medium mb-2">Subject *</label>
            <input type="text" 
                   id="subject" 
                   name="subject" 
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="mb-6">
            <label for="content" class="block text-gray-700 font-medium mb-2">Message *</label>
            <textarea id="content" 
                      name="content" 
                      rows="6"
                      required
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        
        <button type="submit" class="w-full px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Send Message
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
