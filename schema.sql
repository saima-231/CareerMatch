CREATE DATABASE IF NOT EXISTS careermatch CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE careermatch;

-- Students
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    course VARCHAR(150) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Companies
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Internships
CREATE TABLE IF NOT EXISTS internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    duration VARCHAR(100),
    company_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
) ENGINE=InnoDB;
ALTER TABLE internships ADD COLUMN is_active TINYINT(1) DEFAULT 1;

-- Applications
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    internship_id INT NOT NULL,
    status ENUM('Pending','Accepted','Rejected') DEFAULT 'Pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (internship_id) REFERENCES internships(id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (student_id, internship_id)
) ENGINE=InnoDB;

-- Admin
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Seed default admin (password: admin123)
INSERT INTO admins (username, password)
VALUES ('admin', 'admin123')
ON DUPLICATE KEY UPDATE username=username;

-- Update Students Table
ALTER TABLE students
ADD COLUMN phone VARCHAR(20) AFTER course,
ADD COLUMN university VARCHAR(150) AFTER phone,
ADD COLUMN year_of_study INT AFTER university,
ADD COLUMN skills TEXT AFTER year_of_study,
ADD COLUMN resume_link VARCHAR(255) AFTER skills,
ADD COLUMN github_link VARCHAR(255) AFTER resume_link,
ADD COLUMN linkedin_link VARCHAR(255) AFTER github_link,
ADD COLUMN bio TEXT AFTER linkedin_link;

-- Update Companies Table
ALTER TABLE companies
ADD COLUMN website VARCHAR(255) AFTER description,
ADD COLUMN phone VARCHAR(20) AFTER website,
ADD COLUMN industry VARCHAR(100) AFTER phone,
ADD COLUMN location VARCHAR(150) AFTER industry,
ADD COLUMN logo VARCHAR(255) AFTER location;
