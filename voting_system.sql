CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    position VARCHAR(50) NOT NULL
);

INSERT IGNORE INTO candidates (first_name, last_name, position) VALUES
('John', 'Doe', 'President'),
('Jane', 'Smith', 'Vice President'),
('Alice', 'Johnson', 'Secretary'),
('Bob', 'Brown', 'Treasurer');

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'voter') NOT NULL,
    has_voted BOOLEAN DEFAULT FALSE
);

INSERT IGNORE INTO users (email, password, role) VALUES
('admin@example.com', MD5('password123'), 'admin');

CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    candidate_id INT NOT NULL,
    position VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE
);