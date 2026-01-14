-- Database Creation
CREATE DATABASE IF NOT EXISTS cams_db;
USE cams_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Classrooms Table
CREATE TABLE IF NOT EXISTS classrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    capacity INT NOT NULL,
    type VARCHAR(50) DEFAULT 'Lecture Hall',
    facilities TEXT, 
    status ENUM('Active', 'Maintenance') DEFAULT 'Active'
);

-- Courses Table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100)
);

-- Allocations Table
CREATE TABLE IF NOT EXISTS allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT NOT NULL,
    course_name VARCHAR(100), 
    instructor VARCHAR(100),
    day_of_week ENUM('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    purpose VARCHAR(100) DEFAULT 'Lecture',
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE
);

-- Indexes for Performance
CREATE INDEX idx_classroom_day ON allocations(classroom_id, day_of_week);
CREATE INDEX idx_time_range ON allocations(start_time, end_time);

-- Seed Data: Classrooms (Strict List)
DELETE FROM classrooms; -- Clear existing sample data
INSERT INTO classrooms (name, capacity, type, facilities, status) VALUES 
('Technological Computer Lab 1', 45, 'Laboratory', 'High-end PCs, Network Equipment', 'Active'),
('Technological Computer Lab 2', 45, 'Laboratory', 'High-end PCs, IoT Kits', 'Active'),
('Technological Lecture Hall 1', 120, 'Lecture Hall', 'Audio System, Dual Projectors', 'Active'),
('Technological Lecture Hall 2', 120, 'Lecture Hall', 'Smart Board, AC', 'Active'),
('Technological Electronic Lab', 35, 'Laboratory', 'Oscilloscopes, Soldering Stations', 'Active');

-- Insert Default Admin User (password: password)
INSERT INTO users (username, password, role, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'System Administrator') 
ON DUPLICATE KEY UPDATE id=id;
