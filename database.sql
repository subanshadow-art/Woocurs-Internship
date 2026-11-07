
CREATE DATABASE IF NOT EXISTS your_project_db;
USE your_project_db;

CREATE TABLE IF NOT EXISTS entries (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert 15 Tamil Student Records
INSERT INTO entries (name, email, phone) VALUES
('Arun Kumar', 'arun.kumar@example.com', '0770000001'),
('Karthik Raj', 'karthik.raj@example.com', '0770000002'),
('Sivakumar Rajan', 'sivakumar@example.com', '0770000003'),
('Tharshini Selvam', 'tharshini@example.com', '0770000004'),
('Kavitha Devi', 'kavitha.devi@example.com', '0770000005'),
('Praveen Kumar', 'praveen.kumar@example.com', '0770000006'),
('Nithya Shree', 'nithya.shree@example.com', '0770000007'),
('Vigneshwaran', 'vignesh@example.com', '0770000008'),
('Abinaya Raj', 'abinaya.raj@example.com', '0770000009'),
('Dinesh Kumar', 'dinesh.kumar@example.com', '0770000010'),
('Harini Siva', 'harini.siva@example.com', '0770000011'),
('Sanjay Raj', 'sanjay.raj@example.com', '0770000012'),
('Yogeshwaran', 'yogesh@example.com', '0770000013'),
('Janani Priya', 'janani.priya@example.com', '0770000014'),
('Mathumitha', 'mathumitha@example.com', '0770000015');

-- Display all student records
SELECT * FROM entries;
