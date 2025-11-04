<?php
require_once __DIR__ . '/includes/header.php';
$page_title = 'Help & Support';
?>

<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Help & Support</h1>
            <p class="text-gray-600 mb-8">Find answers to common questions and get help with using <?php echo SITE_NAME; ?>.</p>
            
            <div class="prose max-w-none text-gray-700">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Getting Started</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I create an account?</h3>
                            <p class="mb-4">
                                Click on the "Sign Up" button in the header, fill in your username, email, and password, then click "Register". You'll be automatically logged in after registration.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I become an author?</h3>
                            <p class="mb-4">
                                Author status is granted by administrators. Contact us through the contact form or reach out to an administrator to request author privileges.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I search for posts?</h3>
                            <p class="mb-4">
                                Click the search icon in the header to reveal the search box. Type your keywords and press Enter or click the search button to find relevant posts.
                            </p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Reading & Navigation</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I browse by category?</h3>
                            <p class="mb-4">
                                Hover over any category name in the navigation menu to see recent posts from that category. Click on the category name to view all posts in that category.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I like a post?</h3>
                            <p class="mb-4">
                                You must be logged in to like posts. Click the heart icon on any post to like it. You can unlike a post by clicking the heart icon again.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I comment on a post?</h3>
                            <p class="mb-4">
                                Scroll to the comments section at the bottom of any post. Logged-in users can comment directly. Guests need to provide their name and email to comment.
                            </p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">For Authors</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I create a new post?</h3>
                            <p class="mb-4">
                                Go to Dashboard → New Post. Fill in the title, content, excerpt, select a category, upload a featured image, and choose whether to publish or save as draft.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Why is my post pending?</h3>
                            <p class="mb-4">
                                Posts are reviewed by administrators before publication unless you have auto-approve privileges. This ensures quality content on our platform.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I edit my profile?</h3>
                            <p class="mb-4">
                                Go to Dashboard → Profile. You can update your full name, country, bio, and upload a profile photo. Note that username and email cannot be changed.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">What image formats are supported?</h3>
                            <p class="mb-4">
                                We support JPG, JPEG, PNG, GIF, and WebP formats. Maximum file size is 5MB. Images are automatically optimized for web display.
                            </p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Account Management</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I change my password?</h3>
                            <p class="mb-4">
                                Currently, password changes are not available through the user interface. Please contact support if you need to change your password.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">I forgot my password. What should I do?</h3>
                            <p class="mb-4">
                                Contact our support team through the contact form with your username or email, and we'll help you regain access to your account.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">How do I delete my account?</h3>
                            <p class="mb-4">
                                To delete your account, please contact us through the contact form. We'll process your request within 48 hours.
                            </p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Technical Issues</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">The website is not loading properly</h3>
                            <p class="mb-4">
                                Try clearing your browser cache and cookies, or try using a different browser. If the problem persists, contact our technical support.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Images are not displaying</h3>
                            <p class="mb-4">
                                Check your internet connection and ensure images are enabled in your browser settings. Some images may take a moment to load.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">I'm seeing error messages</h3>
                            <p class="mb-4">
                                Note the specific error message and contact our support team with details. Include your browser type and version for faster resolution.
                            </p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Still Need Help?</h2>
                    <p class="mb-4">
                        If you can't find the answer you're looking for, please don't hesitate to contact us:
                    </p>
                    <div class="bg-gray-100 p-6 rounded">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Contact Form</h3>
                                <a href="contact.php" class="text-blue-600 hover:text-blue-700">Send us a message →</a>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Email Support</h3>
                                <a href="mailto:support@ksanews.com" class="text-blue-600 hover:text-blue-700">support@ksanews.com</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

