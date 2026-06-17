# KSA News Blog Platform

<p align="left">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" alt="JavaScript" />
  <img src="https://img.shields.io/badge/Security-Prepared_Statements-4CAF50?style=for-the-badge" alt="Security" />
</p>

> A full-featured, modern blog platform built with PHP and MySQL. Designed for speed, security, and an intuitive user experience. It provides a complete content management system with user authentication, post management, a commenting system, and a powerful admin panel.

KSA News Blog Platform simulates a professional digital publishing environment. The entire system is built around core principles — **security, performance, and responsive design** — ensuring a seamless experience for both readers and authors.

## 🚀 Proven Architecture

The platform is structured to handle content efficiently while maintaining high security standards.

| Feature | Implementation | Benefit |
| :--- | :--- | :--- |
| **Authentication** | Bcrypt Hashing & HttpOnly Cookies | Prevents session hijacking and secures user data |
| **Database** | PDO Prepared Statements | 100% protection against SQL Injection attacks |
| **XSS Protection** | Strict `htmlspecialchars` sanitization | Prevents malicious scripts from executing in browsers |
| **File Uploads** | MIME-type & Extension Validation | Blocks malicious file executions (e.g., PHP shells) |
| **UI/UX** | Tailwind CSS (Mobile-First) | Lightning-fast rendering and perfect mobile responsiveness |

## 🎯 Core Features

### 👤 For Readers
- **Responsive Design**: Fully optimized for desktop, tablet, and mobile devices.
- **Dynamic Discovery**: Search functionality, category filtering, and featured hero sections.
- **Engagement**: Like, share, and comment on posts.
- **Author Profiles**: Discover content by your favorite writers.

### ✍️ For Authors (Dashboard)
- **Rich Content Creation**: Create posts with a rich text editor, excerpts, and featured images.
- **Draft Management**: Save posts as drafts or publish them immediately.
- **Profile Customization**: Update bio, country, and profile photo.
- **Analytics**: Track views, likes, and comments on your articles.

### 🛡️ For Administrators (Admin Panel)
- **Content Moderation**: Approve pending posts, unpublish, or delete content.
- **User Management**: Ban/unban users, promote to authors, and grant auto-approve rights.
- **Category Control**: Manage categories, set homepage features, and configure navigation.
- **Site Overview**: Comprehensive dashboard with statistics and activity logs.

## 📁 Clean Folder Structure

```text
blog/
├── admin/               # Secure admin panel for moderation
├── api/                 # RESTful endpoints (likes, comments, uploads)
├── assets/              # CSS, JS, and uploaded media
├── config/              # Environment-based configuration (.env)
├── dashboard/           # Author dashboard for content creation
├── database/            # SQL schemas and migrations
├── includes/            # Reusable components and core functions
└── public pages         # index, post, login, register, etc.
```

## 🛠️ Quick Installation

### 1. Database Setup
```sql
CREATE DATABASE blog_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Import the schema:
```bash
mysql -u root -p blog_app < database/schema.sql
```

### 2. Configuration
Copy the environment template and configure your settings:
```bash
cp .env.example .env
```
Edit `.env` with your database credentials and site URL.

### 3. Permissions (Linux/Mac)
```bash
chmod 755 assets/images/
chmod 644 assets/images/*
chmod 600 .env
```

### 4. Default Admin Credentials
- **Username**: `admin`
- **Password**: `admin123`
*(Change immediately after first login)*

## 🔒 Security Best Practices

- **Never commit `.env`**: It is ignored by Git by default.
- **Production Error Reporting**: Set `ERROR_REPORTING=0` and `DISPLAY_ERRORS=0` in `.env`.
- **HTTPS**: Always use an SSL certificate in production.

---

**Developed by**: Kalana Sandakelum (KSA Labs)  
**Version**: 1.0.0
