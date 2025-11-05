<?php
// Include auth first (before any output)
require_once __DIR__ . '/../includes/auth.php';

requireAdmin();

$conn = getDB();
$message = '';
$error = '';

// Handle form submissions (before including header to avoid output before redirect)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        if (empty($name) || empty($slug)) {
            $error = 'Name and slug are required.';
        } else {
            // Generate slug from name if not provided
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
                $slug = preg_replace('/-+/', '-', $slug);
                $slug = trim($slug, '-');
            }
            
            // Make slug unique
            $original_slug = $slug;
            $counter = 1;
            while (true) {
                $stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
                $stmt->bind_param("s", $slug);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                
                if ($result->num_rows == 0) {
                    break;
                }
                $slug = $original_slug . '-' . $counter;
                $counter++;
            }
            
            $stmt = $conn->prepare("INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $slug, $description);
            if ($stmt->execute()) {
                $message = 'Category added successfully.';
            } else {
                $error = 'Failed to add category.';
            }
            $stmt->close();
        }
    }
    
    if (isset($_POST['update_homepage_categories'])) {
        // Check if columns exist
        $columns_check = $conn->query("SHOW COLUMNS FROM categories LIKE 'featured_homepage_order'");
        $has_new_columns = $columns_check && $columns_check->num_rows > 0;
        
        if (!$has_new_columns) {
            $error = 'Please run the database migration first. See database/migration_add_category_fields.sql';
        } else {
            // Update featured homepage categories (5 categories)
            $homepage_categories = $_POST['homepage_categories'] ?? [];
            $homepage_orders = $_POST['homepage_orders'] ?? [];
            
            // First, clear all featured homepage orders
            $conn->query("UPDATE categories SET featured_homepage_order = NULL");
            
            // Set new featured categories
            foreach ($homepage_categories as $index => $cat_id) {
                if (!empty($cat_id)) {
                    $order = isset($homepage_orders[$index]) ? (int)$homepage_orders[$index] : $index + 1;
                    $stmt = $conn->prepare("UPDATE categories SET featured_homepage_order = ? WHERE id = ?");
                    $stmt->bind_param("ii", $order, $cat_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            $message = 'Homepage categories updated successfully.';
        }
    }
    
    if (isset($_POST['update_header_categories'])) {
        // Check if columns exist
        $columns_check = $conn->query("SHOW COLUMNS FROM categories LIKE 'show_in_header'");
        $has_new_columns = $columns_check && $columns_check->num_rows > 0;
        
        if (!$has_new_columns) {
            $error = 'Please run the database migration first. See database/migration_add_category_fields.sql';
        } else {
            // Update header menu categories (6 categories)
            $header_categories = $_POST['header_categories'] ?? [];
            $header_orders = $_POST['header_orders'] ?? [];
            
            // First, clear all header settings
            $conn->query("UPDATE categories SET show_in_header = 0, header_order = NULL");
            
            // Set new header categories
            foreach ($header_categories as $index => $cat_id) {
                if (!empty($cat_id)) {
                    $order = isset($header_orders[$index]) ? (int)$header_orders[$index] : $index + 1;
                    $show = 1;
                    $stmt = $conn->prepare("UPDATE categories SET show_in_header = ?, header_order = ? WHERE id = ?");
                    $stmt->bind_param("iii", $show, $order, $cat_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            $message = 'Header menu categories updated successfully.';
        }
    }
    
    if (isset($_POST['delete_category'])) {
        $cat_id = (int)$_POST['category_id'];
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        $stmt->close();
        $message = 'Category deleted successfully.';
    }
}

// Check if new columns exist
$columns_check = $conn->query("SHOW COLUMNS FROM categories LIKE 'featured_homepage_order'");
$has_new_columns = $columns_check && $columns_check->num_rows > 0;

// Get all categories
$all_categories_result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$all_categories = $all_categories_result ? $all_categories_result->fetch_all(MYSQLI_ASSOC) : [];

// Get featured homepage categories (5)
$featured_homepage = [];
if ($has_new_columns) {
    $result = $conn->query("
        SELECT * FROM categories 
        WHERE featured_homepage_order IS NOT NULL 
        ORDER BY featured_homepage_order ASC 
        LIMIT 5
    ");
    if ($result) {
        $featured_homepage = $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Get header categories (6)
$header_categories = [];
if ($has_new_columns) {
    $result = $conn->query("
        SELECT * FROM categories 
        WHERE show_in_header = 1 
        ORDER BY header_order ASC 
        LIMIT 6
    ");
    if ($result) {
        $header_categories = $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Include header after all redirects are handled
$page_title = 'Manage Categories';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Manage Categories</h1>
    
    <?php if (!$has_new_columns): ?>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-4">
            <strong>Migration Required:</strong> Please run the database migration to enable category management features. 
            See <code>database/migration_add_category_fields.sql</code> for the SQL commands.
        </div>
    <?php endif; ?>
    
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
    
    <!-- Add New Category -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Add New Category</h2>
        <form method="POST" action="">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Category Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Slug *</label>
                    <input type="text" name="slug" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <input type="text" name="description" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" name="add_category" class="mt-4 px-6 py-2 bg-black text-white rounded hover:bg-gray-900">
                Add Category
            </button>
        </form>
    </div>
    
    <!-- Featured Homepage Categories (5) -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Featured Homepage Categories (Select 5)</h2>
        <?php if (!$has_new_columns): ?>
            <p class="text-gray-600 mb-4">Run the database migration to enable this feature.</p>
        <?php endif; ?>
        <form method="POST" action="" <?php echo !$has_new_columns ? 'onsubmit="return false;"' : ''; ?>>
            <div class="space-y-4">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="flex items-center gap-4">
                        <label class="w-32 text-gray-700 font-medium">Position <?php echo $i + 1; ?>:</label>
                        <select name="homepage_categories[]" class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <?php 
                                $selected = false;
                                if (isset($featured_homepage[$i]) && $featured_homepage[$i]['id'] == $cat['id']) {
                                    $selected = true;
                                }
                                ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="homepage_orders[]" value="<?php echo $i + 1; ?>" min="1" max="5" class="w-20 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Order">
                    </div>
                <?php endfor; ?>
            </div>
            <button type="submit" name="update_homepage_categories" class="mt-4 px-6 py-2 bg-black text-white rounded hover:bg-gray-900">
                Update Homepage Categories
            </button>
        </form>
    </div>
    
    <!-- Header Menu Categories (6) -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Header Menu Categories (Select 6)</h2>
        <?php if (!$has_new_columns): ?>
            <p class="text-gray-600 mb-4">Run the database migration to enable this feature.</p>
        <?php endif; ?>
        <form method="POST" action="" <?php echo !$has_new_columns ? 'onsubmit="return false;"' : ''; ?>>
            <div class="space-y-4">
                <?php for ($i = 0; $i < 6; $i++): ?>
                    <div class="flex items-center gap-4">
                        <label class="w-32 text-gray-700 font-medium">Position <?php echo $i + 1; ?>:</label>
                        <select name="header_categories[]" class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Category --</option>
                            <?php foreach ($all_categories as $cat): ?>
                                <?php 
                                $selected = false;
                                if (isset($header_categories[$i]) && $header_categories[$i]['id'] == $cat['id']) {
                                    $selected = true;
                                }
                                ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="header_orders[]" value="<?php echo $i + 1; ?>" min="1" max="6" class="w-20 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Order">
                    </div>
                <?php endfor; ?>
            </div>
            <button type="submit" name="update_header_categories" class="mt-4 px-6 py-2 bg-black text-white rounded hover:bg-gray-900">
                Update Header Categories
            </button>
        </form>
    </div>
    
    <!-- All Categories List -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">All Categories</h2>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Slug</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Description</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Homepage</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Header</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_categories as $cat): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $cat['id']; ?></td>
                            <td class="border border-gray-300 px-4 py-2 font-medium"><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-gray-600"><?php echo htmlspecialchars($cat['slug']); ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-gray-600"><?php echo htmlspecialchars($cat['description'] ?? '-'); ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <?php if ($has_new_columns && isset($cat['featured_homepage_order']) && $cat['featured_homepage_order']): ?>
                                    <span class="text-green-600 font-medium">Yes (<?php echo $cat['featured_homepage_order']; ?>)</span>
                                <?php else: ?>
                                    <span class="text-gray-400">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <?php if ($has_new_columns && isset($cat['show_in_header']) && $cat['show_in_header']): ?>
                                    <span class="text-blue-600 font-medium">Yes (<?php echo $cat['header_order']; ?>)</span>
                                <?php else: ?>
                                    <span class="text-gray-400">No</span>
                                <?php endif; ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
                                    <input type="hidden" name="category_id" value="<?php echo $cat['id']; ?>">
                                    <button type="submit" name="delete_category" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

