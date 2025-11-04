# Setup Guide

## Quick Start

1. **Database Setup**
   ```sql
   CREATE DATABASE blog_app;
   ```
   Then import `database/schema.sql`

2. **Configuration**
   - Edit `config/config.php`
   - Set database credentials
   - Set `SITE_URL` (e.g., `http://localhost/blog`)

3. **Web Server**
   - Point document root to project folder
   - Or use PHP built-in server: `php -S localhost:8000`

4. **Default Login**
   - Username: `admin`
   - Password: `admin123`
   - **Change immediately after first login!**

5. **Image Directory**
   - Ensure `assets/images/` is writable
   - Add placeholder images:
     - `default.jpg` - Default post image
     - `default-avatar.png` - Default user avatar

## File Permissions

```bash
chmod 755 assets/images/
chmod 644 assets/images/*
```

## Troubleshooting

- **Database connection error**: Check credentials in `config/config.php`
- **Image upload fails**: Check `assets/images/` permissions
- **403 Forbidden**: Check `.htaccess` configuration
- **JavaScript not working**: Verify `SITE_URL` is correct in config

## Next Steps

1. Create your first author account
2. Promote users to authors via admin panel
3. Create posts and publish content
4. Customize the design in `includes/header.php` and `includes/footer.php`
