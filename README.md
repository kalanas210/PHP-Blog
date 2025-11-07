# KSA News Blog Platform

A full-featured, modern blog platform built with PHP, MySQL, Tailwind CSS, and JavaScript. This application provides a complete content management system with user authentication, post management, commenting system, and an intuitive admin panel.

## 🚀 Features

### Public Features
- **Responsive Design**: Fully responsive layout optimized for desktop, tablet, and mobile devices
- **Hero Section**: Featured post display with large thumbnail
- **Category Navigation**: Dynamic category-based post organization with dropdown menus
- **Search Functionality**: Full-text search across all published posts
- **Post Viewing**: Beautiful post cards with featured images, excerpts, and metadata
- **Author Profiles**: Author listing page with profile information and post counts
- **Newsletter Section**: Email subscription form (frontend implementation)
- **Social Media Links**: Integrated social media icons in footer

### User Features
- **User Registration**: Simple registration with email and username
- **User Authentication**: Secure login/logout system
- **Post Interactions**:
  - Like posts (requires login)
  - Share posts (copy URL to clipboard)
  - Comment on posts (guest comments allowed)
- **User Profile**: View and edit profile information, upload profile photos

### Author Dashboard
- **Post Management**:
  - Create new posts with rich text editor
  - Edit existing posts
  - Save as draft or publish immediately
  - Delete posts
  - Upload featured images
  - Add excerpts and categories
- **Profile Management**:
  - Edit full name, country, and bio
  - Upload and change profile photo
  - View profile statistics
- **Statistics**: View post views, likes, and comments

### Admin Panel
- **Post Management**:
  - Approve pending posts
  - Unpublish posts
  - Delete posts
  - View all posts with filters
- **User Management**:
  - View all users
  - Ban/unban users
  - Promote users to authors
  - Grant auto-approve permissions
  - View user statistics
- **Category Management**:
  - Create, edit, and delete categories
  - Set homepage featured categories
  - Configure header navigation categories
  - Set display order for categories
- **Site Statistics**: Dashboard with overview of posts, users, and activity

### Technical Features
- **Security**:
  - Environment-based configuration (`.env` files)
  - Password hashing with bcrypt
  - SQL injection protection (prepared statements)
  - XSS protection (htmlspecialchars)
  - Session security (HttpOnly cookies)
  - Input validation and sanitization
- **Performance**:
  - Optimized database queries with indexes
  - Efficient image handling
  - Responsive image loading
- **Mobile Optimization**:
  - Mobile-first responsive design
  - Touch-friendly navigation menu
  - Optimized post cards for mobile viewing
  - Simplified mobile layouts

## 📋 Requirements

- **PHP**: 7.4 or higher (8.0+ recommended)
- **MySQL**: 5.7 or higher (8.0+ recommended)
- **Web Server**: Apache or Nginx
- **PHP Extensions**:
  - PDO
  - PDO_MySQL
  - GD (for image processing)
  - mbstring
  - session

## 🛠️ Installation

### Step 1: Download/Clone the Project

```bash
# If using Git
git clone <repository-url> blog
cd blog

# Or extract the ZIP file to your web server directory
```

### Step 2: Database Setup

1. **Create a MySQL database**:
   ```sql
   CREATE DATABASE blog_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Import the database schema**:
   ```bash
   mysql -u your_username -p blog_app < database/schema.sql
   ```
   
   Or use phpMyAdmin:
   - Open phpMyAdmin
   - Select the `blog_app` database
   - Go to Import tab
   - Select `database/schema.sql`
   - Click Go

### Step 3: Configure the Application

1. **Copy the `.env.example` file to `.env`**:
   ```bash
   cp .env.example .env
   ```
   
   On Windows:
   ```bash
   copy .env.example .env
   ```

2. **Edit the `.env` file** with your settings:
   ```env
   # Database Configuration
   DB_HOST=localhost
   DB_USER=your_database_username
   DB_PASS=your_database_password
   DB_NAME=blog_app
   
   # Site Configuration
   SITE_NAME=Your Blog Name
   SITE_URL=http://localhost/blog
   SITE_LOGO=logo.png
   
   # File Upload Configuration
   UPLOAD_DIR=../assets/images/
   UPLOAD_MAX_SIZE=5242880
   
   # Error Reporting
   # For development: E_ALL
   # For production: 0
   ERROR_REPORTING=E_ALL
   DISPLAY_ERRORS=1
   ```

   **Note**: The `.env` file is automatically ignored by Git for security. Never commit your `.env` file to version control.

3. **Set proper file permissions** (Linux/Mac):
   ```bash
   chmod 755 assets/images/
   chmod 644 assets/images/*
   chmod 600 .env  # Secure your .env file
   ```

### Step 4: Web Server Configuration

#### Apache Configuration

Ensure `.htaccess` is enabled and working. The project includes an `.htaccess` file for:
- URL rewriting
- Security (protecting config and includes directories)
- PHP settings

#### Nginx Configuration

Add this to your Nginx server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

### Step 5: Default Login Credentials

After installation, you can login with:
- **Username**: `admin`
- **Password**: `admin123`

**⚠️ IMPORTANT**: Change this password immediately after first login!

## 📁 Project Structure

```
blog/
├── admin/                    # Admin panel pages
│   ├── index.php            # Admin dashboard
│   ├── posts.php            # Post management
│   ├── users.php            # User management
│   └── categories.php       # Category management
│
├── api/                      # API endpoints
│   ├── comment.php          # Handle comments (AJAX)
│   ├── like.php             # Handle likes (AJAX)
│   └── share.php            # Generate share URLs
│
├── assets/
│   ├── css/
│   │   └── styles.css       # Custom styles
│   ├── js/
│   │   ├── main.js          # Main JavaScript
│   │   ├── dashboard.js     # Dashboard JavaScript
│   │   └── like-share.js    # Like/Share functionality
│   └── images/              # Uploaded images
│       ├── default-avatar.png
│       ├── logo.png
│       └── [uploaded files]
│
├── config/
│   ├── config.php           # Main configuration (loads from .env)
│   └── config.production.php.example  # Production config template
├── .env                      # Environment variables (create from .env.example)
├── .env.example              # Environment variables template
│
├── dashboard/               # Author dashboard
│   ├── index.php           # Dashboard home
│   ├── new-post.php        # Create new post
│   ├── edit-post.php       # Edit existing post
│   └── profile.php         # Edit user profile
│
├── database/
│   ├── schema.sql          # Complete database schema
│   └── migration_add_category_fields.sql  # Migration for existing DBs
│
├── includes/                # PHP includes
│   ├── auth.php            # Authentication functions
│   ├── auth-check.php      # Authentication checks
│   ├── db.php              # Database connection
│   ├── functions.php       # Helper functions
│   ├── header.php          # Header template
│   └── footer.php          # Footer template
│
├── index.php                # Homepage
├── post.php                 # Single post page
├── login.php                # Login page
├── register.php             # Registration page
├── logout.php               # Logout handler
├── author.php               # Single author page
├── authors.php              # Authors listing
├── about.php                # About page
├── contact.php              # Contact page
├── privacy.php              # Privacy policy
├── terms.php                # Terms & conditions
├── help.php                 # Help page
├── .htaccess                # Apache configuration
├── .gitignore               # Git ignore file
└── README.md                # This file
```

## 🎯 Usage Guide

### For Administrators

1. **Login** to the admin panel at `/admin/`
2. **Manage Posts**:
   - View pending posts awaiting approval
   - Approve or reject posts
   - Edit or delete any post
3. **Manage Users**:
   - Promote users to authors
   - Grant auto-approve permissions
   - Ban/unban users
4. **Manage Categories**:
   - Create new categories
   - Set categories for homepage display
   - Configure header navigation categories
   - Set display order

### For Authors

1. **Register/Login** to your account
2. **Access Dashboard** at `/dashboard/`
3. **Create Posts**:
   - Click "New Post"
   - Fill in title, content, excerpt
   - Select category
   - Upload featured image (optional)
   - Choose status (Draft or Publish)
   - Save post
4. **Edit Profile**:
   - Go to Dashboard → Profile
   - Update name, country, bio
   - Upload profile photo

### For Users

1. **Browse Posts**:
   - View posts by category
   - Search for posts
   - Read full posts
2. **Interact**:
   - Like posts (requires login)
   - Share posts
   - Comment on posts
3. **Create Account**:
   - Register for free
   - Request author status from admin

## 🔒 Security Features

- **Password Security**: All passwords are hashed using bcrypt
- **SQL Injection Protection**: All database queries use prepared statements
- **XSS Protection**: All user input is sanitized with `htmlspecialchars()`
- **Session Security**: HttpOnly cookies, secure session handling
- **File Upload Security**: File type and size validation
- **Access Control**: Role-based access control (admin, author, user)
- **CSRF Protection**: Session-based token validation (recommended addition)

## 🎨 Customization

### Changing Site Name and Logo

Edit the `.env` file in the project root:
```env
SITE_NAME=Your Blog Name
SITE_LOGO=logo.png
```

To use text only (no logo), set:
```env
SITE_LOGO=false
```

Upload your logo to `assets/images/logo.png`

### Styling

The project uses **Tailwind CSS** via CDN. To customize:

1. **Quick Customization**: Edit `assets/css/styles.css` for custom styles
2. **Full Customization**: 
   - Install Tailwind CLI
   - Create a custom build
   - Replace CDN link with local CSS file

### Categories Configuration

1. Login as admin
2. Go to Admin Panel → Categories
3. Set which categories appear on homepage
4. Set which categories appear in header navigation
5. Configure display order

## 🐛 Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check database credentials in `.env` file
   - Verify the `.env` file exists in the project root
   - Ensure `.env` file has correct format (KEY=VALUE, no spaces around =)
   - Verify database exists and user has proper permissions
   - Check MySQL service is running

2. **Images Not Uploading**:
   - Check `assets/images/` directory permissions (should be 755)
   - Verify PHP upload settings in `php.ini`
   - Check file size limits

3. **Session Issues**:
   - Ensure PHP sessions are enabled
   - Check session directory permissions
   - Verify cookie settings

4. **404 Errors**:
   - Ensure `.htaccess` is enabled (Apache)
   - Check web server rewrite rules
   - Verify file paths are correct

### Getting Help

- Check error logs in your web server
- Enable error reporting in `.env` file (set `DISPLAY_ERRORS=1` for development only)
- Review PHP error logs
- Verify `.env` file exists and has correct format

## 📝 Database Migration

If you have an existing database without the category fields, run:

```bash
mysql -u your_username -p blog_app < database/migration_add_category_fields.sql
```

Or manually execute the SQL in `database/migration_add_category_fields.sql`

## 🚀 Production Deployment

### Before Going Live

1. **Update Configuration in `.env`**:
   ```env
   # Update site URL
   SITE_URL=https://yourdomain.com
   
   # Disable error reporting for production
   ERROR_REPORTING=0
   DISPLAY_ERRORS=0
   
   # Update database credentials for production
   DB_HOST=your_production_host
   DB_USER=your_production_user
   DB_PASS=your_production_password
   DB_NAME=your_production_database
   ```

2. **Secure your `.env` file**:
   ```bash
   chmod 600 .env  # Linux/Mac - restrict access to owner only
   ```

3. **Change Default Passwords**: Change admin password immediately

4. **Enable HTTPS**: Configure SSL certificate

5. **Set Proper Permissions**:
   ```bash
   chmod 600 .env  # Secure environment file
   chmod 755 assets/images/
   ```

6. **Backup Database**: Set up regular database backups

7. **Optimize Images**: Compress uploaded images for better performance

### Recommended Production Settings

- Use PHP 8.0 or higher
- Enable OPcache for PHP
- Use MySQL 8.0 or higher
- Enable HTTPS/SSL
- Set up regular backups
- Monitor error logs
- Use a CDN for static assets (optional)

## 📄 License

This project is created for educational and commercial purposes. Feel free to use, modify, and distribute as needed.

## 👨‍💻 Development

### Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, Tailwind CSS (CDN), JavaScript (Vanilla)
- **Icons**: Font Awesome 6.4.0

### Code Structure

- **MVC-like Structure**: Separation of concerns
- **Prepared Statements**: All database queries use prepared statements
- **Template System**: Reusable header/footer includes
- **API Endpoints**: RESTful API for AJAX operations

### Future Enhancements

Potential improvements:
- Email notifications
- RSS feed generation
- Advanced search with filters
- Post scheduling
- Image optimization
- Caching system
- SEO enhancements
- Multi-language support

## 📞 Support

For issues, questions, or contributions:
- Review the documentation
- Check the code comments
- Review error logs
- Contact the development team

---

**Version**: 1.0.0  
**Last Updated**: 2024  
**Developed by**: KSA Labs
