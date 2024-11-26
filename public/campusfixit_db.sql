-- Drop the database if it already exists
DROP DATABASE IF EXISTS campusfixit;

-- Create the database
CREATE DATABASE campusfixit;
USE campusfixit;

-- Create the User table
CREATE TABLE `User` (
    `userID` INT AUTO_INCREMENT PRIMARY KEY,
    `userName` VARCHAR(255) NOT NULL,
    `userContact` VARCHAR(20),
    `userEmail` VARCHAR(255) NOT NULL UNIQUE,
    `userPassword` VARCHAR(255) NOT NULL,
    `userRole` ENUM('Regular', 'Admin') DEFAULT 'Regular'
);

-- Create the MaintenanceType table
CREATE TABLE `MaintenanceType` (
    `maintenanceTypeID` INT AUTO_INCREMENT PRIMARY KEY,
    `typeName` VARCHAR(255) NOT NULL UNIQUE
);

-- Create the Status table
CREATE TABLE `Status` (
    `statusID` INT AUTO_INCREMENT PRIMARY KEY,
    `statusName` VARCHAR(50) NOT NULL UNIQUE
);

-- Create the Report table
CREATE TABLE `Report` (
    `reportID` VARCHAR(255) PRIMARY KEY,
    `userID` INT NOT NULL,
    `maintenanceTypeID` INT NOT NULL,
    `statusID` INT NOT NULL,
    `description` TEXT NOT NULL,
    `location` VARCHAR(255),
    `submissionDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `completionDate` DATE,
    FOREIGN KEY (`userID`) REFERENCES `User`(`userID`) ON DELETE CASCADE,
    FOREIGN KEY (`maintenanceTypeID`) REFERENCES `MaintenanceType`(`maintenanceTypeID`) ON DELETE RESTRICT,
    FOREIGN KEY (`statusID`) REFERENCES `Status`(`statusID`) ON DELETE RESTRICT
);

-- Create the Uploads table
CREATE TABLE `Uploads` (
    `uploadID` INT AUTO_INCREMENT PRIMARY KEY,
    `reportID` VARCHAR(255) NOT NULL,
    `fileName` VARCHAR(255) NOT NULL,
    `filePath` VARCHAR(255) NOT NULL,
    `mimeType` VARCHAR(100) NOT NULL,
    `fileSize` INT NOT NULL,
    `uploadedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`reportID`) REFERENCES `Report`(`reportID`) ON DELETE CASCADE
);

-- Insert initial data into MaintenanceType table
INSERT INTO `MaintenanceType` (`typeName`) VALUES
    ('Electrical'),
    ('Plumbing'),
    ('HVAC'),
    ('General Repairs'),
    ('Cleaning');

-- Insert initial data into Status table
INSERT INTO `Status` (`statusName`) VALUES
    ('Pending'),
    ('In Progress'),
    ('Completed'),
    ('Cancelled');

-- Insert sample data into User table
INSERT INTO `User` (`userName`, `userContact`, `userEmail`, `userPassword`, `userRole`) VALUES
    ('John Doe', '123456789', 'admin@example.com', 'hashed_admin_password', 'Admin'),
    ('Jane Smith', '987654321', 'jane@example.com', 'hashed_user_password', 'Regular');

-- Insert sample data into Report table
INSERT INTO `Report` (`reportID`, `userID`, `maintenanceTypeID`, `statusID`, `description`, `location`) VALUES
    ('report_1', 1, 1, 1, 'Flickering lights in the main hallway', 'Main Hallway'),
    ('report_2', 2, 2, 2, 'Leaking pipe in the restroom', 'Restroom');

-- (Optional) Insert sample data into Uploads table
INSERT INTO `Uploads` (`reportID`, `fileName`, `filePath`, `mimeType`, `fileSize`) VALUES
    ('report_1', 'light_issue.jpg', '/uploads/light_issue.jpg', 'image/jpeg', 2048),
    ('report_2', 'pipe_leak.png', '/uploads/pipe_leak.png', 'image/png', 4096);
