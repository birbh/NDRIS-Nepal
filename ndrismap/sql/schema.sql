-- NDRIS-Nepal Database Schema
-- National Disaster, Responsibility & Impact System
-- Created: January 2026

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS ndris_nepal;
USE ndris_nepal;

-- Table: disasters
-- Stores historical disaster records
CREATE TABLE IF NOT EXISTS disasters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(100) NOT NULL COMMENT 'e.g., earthquake, flood, landslide',
    district VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    impact_level INT NOT NULL COMMENT 'Scale of 1-10',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_district (district),
    INDEX idx_year (year),
    INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: grievances
-- Stores citizen-submitted issues
CREATE TABLE IF NOT EXISTS grievances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NOT NULL COMMENT 'e.g., infrastructure, health, education',
    district VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending' COMMENT 'pending, reviewed, resolved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_district (district),
    INDEX idx_status (status),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: policies
-- Tracks policies and their effectiveness
CREATE TABLE IF NOT EXISTS policies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    policy_name VARCHAR(255) NOT NULL,
    sector VARCHAR(100) NOT NULL COMMENT 'e.g., health, education, infrastructure',
    district VARCHAR(100) NOT NULL,
    effectiveness_score INT NOT NULL COMMENT 'Scale of 1-10',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_district (district),
    INDEX idx_sector (sector)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: neglect_index
-- Stores computed neglect scores per district
CREATE TABLE IF NOT EXISTS neglect_index (
    district VARCHAR(100) PRIMARY KEY,
    grievance_count INT DEFAULT 0,
    disaster_count INT DEFAULT 0,
    policy_score INT DEFAULT 0,
    neglect_score FLOAT DEFAULT 0.0 COMMENT 'Computed heuristic score',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: admin_users
-- Simple admin authentication
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
-- NOTE: Change this password in production
INSERT INTO admin_users (username, password_hash) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample data for testing (optional)
INSERT INTO disasters (title, type, district, year, impact_level, description) VALUES
('2015 Gorkha Earthquake', 'earthquake', 'Gorkha', 2015, 10, 'Major earthquake affecting central Nepal'),
('Koshi River Flood', 'flood', 'Sunsari', 2008, 8, 'Devastating flood in eastern Nepal'),
('Jajarkot Earthquake', 'earthquake', 'Jajarkot', 2023, 7, 'Earthquake in western Nepal');

INSERT INTO grievances (category, district, description, status) VALUES
('infrastructure', 'Kathmandu', 'Road quality deteriorating in Thamel area', 'pending'),
('health', 'Pokhara', 'Hospital equipment shortage', 'reviewed'),
('education', 'Lalitpur', 'School building needs repair', 'pending');

INSERT INTO policies (policy_name, sector, district, effectiveness_score, notes) VALUES
('School Reconstruction Program', 'education', 'Kathmandu', 7, 'Ongoing reconstruction after 2015 earthquake'),
('Rural Road Development', 'infrastructure', 'Pokhara', 5, 'Progress slower than expected'),
('Health Post Modernization', 'health', 'Lalitpur', 8, 'Good progress on equipment upgrade');
