<?php
// Include auth and functions first (before any output)
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAuthor();

$current_user = getCurrentUser();
$error = '';
$success = '';

// Process POST request before including header (to avoid output before redirect)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $status = $_POST['status'] ?? 'draft';
    
    if (empty($title) || empty($content)) {
        $error = 'Title and content are required.';
    } else {
        $slug = generateSlug($title);
        $conn = getDB();
        
        // Handle file upload
        $featured_image = null;
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['featured_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($ext, $allowed) && $file['size'] <= UPLOAD_MAX_SIZE) {
                $filename = uniqid() . '.' . $ext;
                $upload_path = __DIR__ . '/../assets/images/' . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $featured_image = $filename;
                }
            }
        }
        
        // Determine if post should be auto-approved
        $final_status = $status;
        if ($status === 'published' && $current_user['auto_approve'] == 1) {
            $final_status = 'published';
            $published_at = date('Y-m-d H:i:s');
        } else {
            $final_status = $status === 'published' ? 'pending' : $status;
            $published_at = $status === 'published' ? date('Y-m-d H:i:s') : null;
        }
        
        $stmt = $conn->prepare("
            INSERT INTO posts (author_id, title, slug, excerpt, content, featured_image, category_id, status, published_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("isssssiss", 
            $current_user['id'], 
            $title, 
            $slug, 
            $excerpt, 
            $content, 
            $featured_image, 
            $category_id, 
            $final_status,
            $published_at
        );
        
        if ($stmt->execute()) {
            $success = 'Post created successfully!';
            if ($final_status === 'pending') {
                $success .= ' Your post is pending approval.';
            }
            header('Location: index.php');
            exit;
        } else {
            $error = 'Failed to create post. Please try again.';
        }
        $stmt->close();
    }
}

$categories = getCategories();

// Include header after processing (when we need to display the form)
$page_title = 'New Post';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Post</h1>
    
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg p-8">
        <div class="mb-6">
            <label for="title" class="block text-gray-700 font-medium mb-2">Title *</label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   required
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="mb-6">
            <label for="excerpt" class="block text-gray-700 font-medium mb-2">Excerpt</label>
            <textarea id="excerpt" 
                      name="excerpt" 
                      rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        
        <div class="mb-6">
            <label for="content" class="block text-gray-700 font-medium mb-2">Content *</label>
            <textarea id="content" 
                      name="content" 
                      rows="15"
                      required
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="category_id" class="block text-gray-700 font-medium mb-2">Category</label>
                <select id="category_id" 
                        name="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                <select id="status" 
                        name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="draft">Draft</option>
                    <option value="published">Publish</option>
                </select>
            </div>
        </div>
        
        <div class="mb-6">
            <label for="featured_image" class="block text-gray-700 font-medium mb-2">Featured Image</label>
            <input type="file" 
                   id="featured_image" 
                   name="featured_image"
                   accept="image/*"
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-sm text-gray-500 mt-1">Max size: 5MB. Supported formats: JPG, PNG, GIF, WebP</p>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Save Post
            </button>
            <a href="index.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
