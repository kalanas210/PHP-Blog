<?php
require_once __DIR__ . '/includes/header.php';
$page_title = 'Privacy Policy';
?>

<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
            <p class="text-gray-600 mb-8">Last updated: <?php echo date('F j, Y'); ?></p>
            
            <div class="prose max-w-none text-gray-700">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Introduction</h2>
                    <p class="mb-4">
                        Welcome to <?php echo SITE_NAME; ?>. We respect your privacy and are committed to protecting your personal data. This privacy policy explains how we collect, use, and safeguard your information when you visit our website.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Information We Collect</h2>
                    <p class="mb-4">We collect the following types of information:</p>
                    <ul class="list-disc pl-6 mb-4 space-y-2">
                        <li><strong>Personal Information:</strong> Name, email address, username, and profile information when you register for an account.</li>
                        <li><strong>Content:</strong> Posts, comments, and other content you submit to our platform.</li>
                        <li><strong>Usage Data:</strong> Information about how you interact with our website, including IP address, browser type, and pages visited.</li>
                        <li><strong>Cookies:</strong> Data stored on your device to enhance your browsing experience.</li>
                    </ul>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. How We Use Your Information</h2>
                    <p class="mb-4">We use the information we collect to:</p>
                    <ul class="list-disc pl-6 mb-4 space-y-2">
                        <li>Provide, maintain, and improve our services</li>
                        <li>Process your account registration and manage your profile</li>
                        <li>Send you administrative information and updates</li>
                        <li>Respond to your inquiries and provide customer support</li>
                        <li>Monitor and analyze usage patterns and trends</li>
                        <li>Detect and prevent fraud, abuse, or security issues</li>
                    </ul>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Information Sharing and Disclosure</h2>
                    <p class="mb-4">We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
                    <ul class="list-disc pl-6 mb-4 space-y-2">
                        <li><strong>Service Providers:</strong> With trusted third-party service providers who assist us in operating our website</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                        <li><strong>Business Transfers:</strong> In connection with any merger, sale, or acquisition of our assets</li>
                        <li><strong>With Your Consent:</strong> When you explicitly authorize us to share your information</li>
                    </ul>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">5. Data Security</h2>
                    <p class="mb-4">
                        We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">6. Cookies and Tracking Technologies</h2>
                    <p class="mb-4">
                        We use cookies and similar tracking technologies to track activity on our website and store certain information. Cookies are files with a small amount of data that may include an anonymous unique identifier. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">7. Your Rights</h2>
                    <p class="mb-4">You have the right to:</p>
                    <ul class="list-disc pl-6 mb-4 space-y-2">
                        <li>Access and receive a copy of your personal data</li>
                        <li>Rectify inaccurate or incomplete personal data</li>
                        <li>Request deletion of your personal data</li>
                        <li>Object to processing of your personal data</li>
                        <li>Request restriction of processing your personal data</li>
                        <li>Data portability (receive your data in a structured format)</li>
                        <li>Withdraw consent at any time</li>
                    </ul>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">8. Children's Privacy</h2>
                    <p class="mb-4">
                        Our services are not intended for individuals under the age of 18. We do not knowingly collect personal information from children. If you are a parent or guardian and believe your child has provided us with personal information, please contact us immediately.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">9. Changes to This Privacy Policy</h2>
                    <p class="mb-4">
                        We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last updated" date. You are advised to review this Privacy Policy periodically for any changes.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">10. Contact Us</h2>
                    <p class="mb-4">
                        If you have any questions about this Privacy Policy, please contact us at:
                    </p>
                    <div class="bg-gray-100 p-4 rounded">
                        <p class="mb-2"><strong>Email:</strong> <a href="mailto:privacy@ksanews.com" class="text-blue-600 hover:text-blue-700">privacy@ksanews.com</a></p>
                        <p class="mb-2"><strong>Website:</strong> <a href="contact.php" class="text-blue-600 hover:text-blue-700">Contact Us</a></p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
