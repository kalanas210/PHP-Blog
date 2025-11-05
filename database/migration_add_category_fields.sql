-- Migration: Add featured_homepage and show_in_header fields to categories table
-- Run this SQL to add the new fields

ALTER TABLE categories 
ADD COLUMN featured_homepage_order INT DEFAULT NULL,
ADD COLUMN show_in_header TINYINT(1) DEFAULT 0,
ADD COLUMN header_order INT DEFAULT NULL;

-- Create indexes for better performance
CREATE INDEX idx_featured_homepage ON categories(featured_homepage_order);
CREATE INDEX idx_show_in_header ON categories(show_in_header, header_order);

