# Category Management Migration

## Setup Instructions

To enable the new category management features, you need to run the migration SQL file.

### Steps:

1. **Run the migration SQL:**
   ```sql
   -- Run the SQL from database/migration_add_category_fields.sql
   ALTER TABLE categories 
   ADD COLUMN featured_homepage_order INT DEFAULT NULL,
   ADD COLUMN show_in_header TINYINT(1) DEFAULT 0,
   ADD COLUMN header_order INT DEFAULT NULL;

   CREATE INDEX idx_featured_homepage ON categories(featured_homepage_order);
   CREATE INDEX idx_show_in_header ON categories(show_in_header, header_order);
   ```

2. **Access the Admin Panel:**
   - Go to `admin/categories.php`
   - Or use the "Manage Categories" link from the admin dashboard

3. **Configure Categories:**
   - **Add New Categories**: Use the "Add New Category" form at the top
   - **Select 5 Homepage Categories**: Choose which 5 categories to display on the homepage (with ordering)
   - **Select 6 Header Categories**: Choose which 6 categories to show in the navigation menu (with ordering)

### Features:

- **Homepage Categories**: Only 5 selected categories will be displayed as sections on the homepage
- **Header Menu Categories**: Only 6 selected categories will appear in the navigation bar
- **Category Management**: Add, view, and delete categories from the admin panel
- **Ordering**: Set custom order for both homepage and header categories

### Notes:

- If no categories are selected for homepage, no category sections will appear
- If no categories are selected for header, only "Home", "Author list", and "Newsletter" will appear in the menu
- Categories can be used in both homepage and header simultaneously

