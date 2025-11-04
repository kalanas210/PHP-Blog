# Blog Application

A full-featured blog web application built with HTML, Tailwind CSS, JavaScript, PHP, and MySQL.

## Features

### Homepage
- Hero section with featured post
- Latest posts sidebar
- Category-based sections
- Newsletter subscription
- Author profiles section

### User Features
- User registration and login
- View blog posts
- Like posts (requires login)
- Share posts (URL copy)
- Comment on posts (guest or logged-in)

### Author Dashboard
- Create new posts
- Edit existing posts
- Save as draft
- Delete posts
- Unpublish posts
- View post statistics (views, likes, comments)
- Edit profile (name, country, bio, profile photo)

### Admin Panel
- Approve pending posts
- Unpublish posts
- Delete posts
- Manage users (ban/unban)
- Grant auto-approve permissions
- Promote users to authors
- View site statistics

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional)

### Setup Steps

1. **Clone or extract the project**
   ```bash
   cd blog
   ```

2. **Configure Database**
   - Create a MySQL database named `blog_app`
   - Update `config/config.php` with your database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'blog_app');
     ```
   - Update `SITE_URL` in `config/config.php`:
     ```php
     define('SITE_URL', 'http://localhost/blog');
     ```

3. **Import Database Schema**
   ```bash
   mysql -u your_username -p blog_app < database/schema.sql
   ```
   Or use phpMyAdmin to import `database/schema.sql`

4. **Set Permissions**
   ```bash
   chmod 755 assets/images/
   chmod 644 assets/images/*
   ```

5. **Default Admin Account**
   - Username: `admin`
   - Password: `admin123`
   - **Important**: Change this password after first login!

6. **Configure Web Server**
   - Point your web server document root to the project folder
   - Or use PHP built-in server for development:
     ```bash
     php -S localhost:8000
     ```
   - Access at `http://localhost:8000`

## Project Structure

```
blog/
├── admin/              # Admin panel pages
│   ├── index.php      # Admin dashboard
│   ├── posts.php      # Manage posts
│   └── users.php      # Manage users
├── api/               # API endpoints
│   ├── like.php       # Handle likes
│   ├── comment.php    # Handle comments
│   └── share.php      # Generate share URLs
├── assets/
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── images/        # Uploaded images
├── config/            # Configuration files
│   └── config.php     # Main config
├── dashboard/         # Author dashboard
│   ├── index.php      # Dashboard home
│   ├── new-post.php   # Create post
│   ├── edit-post.php  # Edit post
│   └── profile.php    # Edit profile
├── database/          # Database files
│   └── schema.sql     # Database schema
├── includes/          # PHP includes
│   ├── auth.php       # Authentication functions
│   ├── db.php         # Database connection
│   ├── footer.php     # Footer template
│   ├── functions.php  # Helper functions
│   └── header.php     # Header template
├── index.php          # Homepage
├── post.php           # Single post page
├── login.php          # Login page
├── register.php       # Registration page
├── logout.php         # Logout script
├── terms.php          # Terms & Conditions
├── privacy.php        # Privacy Policy
├── contact.php        # Contact page
└── README.md          # This file
```

## Usage

### Creating Posts (Authors)
1. Register/Login as a user
2. Contact admin to be promoted to author
3. Go to Dashboard → New Post
4. Fill in title, content, category
5. Choose status (Draft or Publish)
6. Upload featured image (optional)
7. Save post

### Managing Posts (Admin)
1. Login as admin
2. Go to Admin Panel
3. View pending posts
4. Approve or reject posts
5. Manage users (ban, promote, permissions)

### User Features
- Browse posts by category
- Like posts (requires login)
- Share posts (copy URL)
- Comment on posts (guest or logged-in)

## Security Notes

- Change default admin password immediately
- Use strong passwords for production
- Enable HTTPS in production
- Regularly update PHP and MySQL
- Keep file permissions secure
- Validate and sanitize all user inputs

## Development

### Tailwind CSS
This project uses Tailwind CSS via CDN. For production, consider:
- Installing Tailwind CLI
- Building a custom CSS file
- Purging unused styles

### Database
- All queries use prepared statements
- SQL injection protection
- XSS protection via htmlspecialchars

## License

This project is created for educational purposes.

## Support

For issues or questions, please contact the development team.
