USE `logovac`;

-- Vytvorenie tabuľky študentov
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- Vytvorenie tabuľky príchodov
CREATE TABLE arrivals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    late BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
