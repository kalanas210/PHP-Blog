<?php
require_once __DIR__ . '/includes/header.php';
$page_title = 'About Us';
?>

<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-16 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">About <?php echo SITE_NAME; ?></h1>
            
            <div class="prose max-w-none text-gray-700">
                <section class="mb-8">
                    <p class="text-lg text-gray-700 mb-6">
                        Welcome to <?php echo SITE_NAME; ?>, your trusted source for news, insights, and engaging stories. We are committed to delivering high-quality journalism and thought-provoking content to our readers.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Our Mission</h2>
                    <p class="mb-4">
                        At <?php echo SITE_NAME; ?>, our mission is to inform, educate, and inspire our readers through well-researched articles, diverse perspectives, and compelling storytelling. We believe in the power of journalism to shape public discourse and bring about positive change.
                    </p>
                    <p class="mb-4">
                        We are dedicated to maintaining the highest standards of journalistic integrity, accuracy, and fairness in all our reporting.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">What We Offer</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Comprehensive Coverage</h3>
                            <p class="mb-4">
                                From breaking news to in-depth analysis, we cover a wide range of topics including politics, business, technology, culture, and more. Our team of experienced writers and contributors bring you diverse viewpoints and expert insights.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Quality Content</h3>
                            <p class="mb-4">
                                Every article published on our platform undergoes a rigorous editorial process to ensure accuracy, clarity, and relevance. We value quality over quantity and strive to provide content that adds value to our readers' lives.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Community Engagement</h3>
                            <p class="mb-4">
                                We believe in fostering a vibrant community of readers, writers, and thinkers. Through comments, discussions, and social sharing, we encourage meaningful dialogue and exchange of ideas.
                            </p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Our Values</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-semibold text-gray-900 mb-2">Integrity</h3>
                            <p class="text-sm text-gray-700">We maintain the highest ethical standards in journalism and content creation.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-semibold text-gray-900 mb-2">Accuracy</h3>
                            <p class="text-sm text-gray-700">We fact-check all information and strive for precision in our reporting.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-semibold text-gray-900 mb-2">Diversity</h3>
                            <p class="text-sm text-gray-700">We celebrate diverse voices and perspectives in our content and community.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded">
                            <h3 class="font-semibold text-gray-900 mb-2">Transparency</h3>
                            <p class="text-sm text-gray-700">We are open about our editorial process and maintain transparency with our readers.</p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Our Team</h2>
                    <p class="mb-4">
                        <?php echo SITE_NAME; ?> is powered by a dedicated team of journalists, editors, and content creators who are passionate about delivering quality news and stories. Our contributors come from diverse backgrounds and bring unique perspectives to our platform.
                    </p>
                    <p class="mb-4">
                        We are always looking for talented writers and journalists to join our team. If you're interested in contributing, please visit our <a href="contact.php" class="text-blue-600 hover:text-blue-700">contact page</a>.
                    </p>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Get Involved</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Become an Author</h3>
                            <p class="mb-4">
                                Do you have a story to tell? We welcome experienced writers and journalists to join our platform. Contact us to learn more about becoming a contributor.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Subscribe to Our Newsletter</h3>
                            <p class="mb-4">
                                Stay updated with our latest articles and news by subscribing to our newsletter. You'll receive curated content directly in your inbox.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Follow Us</h3>
                            <p class="mb-4">
                                Connect with us on social media to stay engaged with our community and get real-time updates on breaking news and featured stories.
                            </p>
                        </div>
                    </div>
                </section>
                
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Contact Us</h2>
                    <p class="mb-4">
                        We'd love to hear from you! Whether you have a story tip, feedback, or just want to say hello, don't hesitate to reach out:
                    </p>
                    <div class="bg-gray-100 p-6 rounded">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">General Inquiries</h3>
                                <a href="contact.php" class="text-blue-600 hover:text-blue-700">Contact Form →</a>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Editorial</h3>
                                <a href="mailto:editorial@ksanews.com" class="text-blue-600 hover:text-blue-700">editorial@ksanews.com</a>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Partnerships</h3>
                                <a href="mailto:partnerships@ksanews.com" class="text-blue-600 hover:text-blue-700">partnerships@ksanews.com</a>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-2">Support</h3>
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

