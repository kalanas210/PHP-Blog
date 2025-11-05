<?php
// Include auth first (before any output)
require_once __DIR__ . '/../includes/auth.php';

requireLogin();

$current_user = getCurrentUser();
$error = '';
$success = '';

// Process POST request before including header (to avoid output before redirect)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    
    $conn = getDB();
    
    // Handle profile photo upload
    $profile_photo = $current_user['profile_photo'];
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile_photo'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($ext, $allowed) && $file['size'] <= UPLOAD_MAX_SIZE) {
            $filename = 'profile_' . $current_user['id'] . '_' . uniqid() . '.' . $ext;
            $upload_path = __DIR__ . '/../assets/images/' . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Delete old profile photo if exists and not default
                if ($profile_photo && $profile_photo !== 'default-avatar.png' && file_exists(__DIR__ . '/../assets/images/' . $profile_photo)) {
                    unlink(__DIR__ . '/../assets/images/' . $profile_photo);
                }
                $profile_photo = $filename;
            }
        }
    }
    
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, country = ?, bio = ?, profile_photo = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $full_name, $country, $bio, $profile_photo, $current_user['id']);
    
    if ($stmt->execute()) {
        $stmt->close();
        // Refresh user data in session
        $_SESSION['user_id'] = $current_user['id'];
        header('Location: profile.php');
        exit;
    } else {
        $error = 'Failed to update profile. Please try again.';
        $stmt->close();
    }
}

// Only include header after processing (when we need to display the form)
$page_title = 'Profile';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Profile</h1>
    
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
        <div class="mb-6 text-center">
            <img src="<?php echo SITE_URL; ?>/assets/images/<?php echo htmlspecialchars($current_user['profile_photo']); ?>" 
                 alt="Profile Photo" 
                 class="w-32 h-32 rounded-full object-cover mx-auto mb-4"
                 onerror="this.src='<?php echo SITE_URL; ?>/assets/images/default-avatar.png'">
            <label for="profile_photo" class="block text-sm text-gray-600 mb-2">Profile Photo</label>
            <input type="file" 
                   id="profile_photo" 
                   name="profile_photo"
                   accept="image/*"
                   class="mx-auto block">
            <p class="text-xs text-gray-500 mt-1">Max size: 5MB</p>
        </div>
        
        <div class="mb-6">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
            <input type="text" 
                   id="username" 
                   value="<?php echo htmlspecialchars($current_user['username']); ?>"
                   disabled
                   class="w-full px-4 py-2 border border-gray-300 rounded bg-gray-100">
            <p class="text-sm text-gray-500 mt-1">Username cannot be changed</p>
        </div>
        
        <div class="mb-6">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
            <input type="email" 
                   id="email" 
                   value="<?php echo htmlspecialchars($current_user['email']); ?>"
                   disabled
                   class="w-full px-4 py-2 border border-gray-300 rounded bg-gray-100">
            <p class="text-sm text-gray-500 mt-1">Email cannot be changed</p>
        </div>
        
        <div class="mb-6">
            <label for="full_name" class="block text-gray-700 font-medium mb-2">Full Name</label>
            <input type="text" 
                   id="full_name" 
                   name="full_name"
                   value="<?php echo htmlspecialchars($current_user['full_name'] ?? ''); ?>"
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="mb-6">
            <label for="country" class="block text-gray-700 font-medium mb-2">Country</label>
            <input type="text" 
                   id="country" 
                   name="country"
                   value="<?php echo htmlspecialchars($current_user['country'] ?? ''); ?>"
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="mb-6">
            <label for="bio" class="block text-gray-700 font-medium mb-2">Bio</label>
            <textarea id="bio" 
                      name="bio" 
                      rows="5"
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($current_user['bio'] ?? ''); ?></textarea>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-black text-white rounded hover:bg-gray-900">
                Update Profile
            </button>
            <a href="index.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
