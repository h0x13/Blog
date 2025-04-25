DROP DATABASE IF EXISTS blogdb;
CREATE DATABASE blogdb;
USE blogdb;

CREATE TABLE users (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'author', 'viewer') NOT NULL DEFAULT 'viewer',
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    image VARCHAR(255) DEFAULT NULL,
    birthdate DATE,
    gender VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE blogs (
    blog_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    visibility ENUM('private', 'public') NOT NULL DEFAULT 'private',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COLLATE utf8_general_ci UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE blog_categories (
    blog_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (blog_id, category_id),
    FOREIGN KEY (blog_id) REFERENCES blogs(blog_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
);

INSERT INTO `users` (first_name, last_name, middle_name, email, password, role)
VALUES ('Jay R', 'Conde', NULL, 'jayr@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'admin'),
       ('Casey', 'Muratori', NULL, 'casey@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'author'),
       ('Theo', 'Browne', 'Smith', 'theo@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'viewer'),
       ('Martin', 'Davis', NULL, 'martin@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'viewer'),
       ('Michael', 'Paulson', NULL, 'michael@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'author'),
       ('Linus Gabriel', 'Sebastian', NULL, 'linus@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'viewer');


INSERT INTO blogs (user_id, title, content, visibility)
VALUES 
-- Blog by Casey Muratori (Author)
(2, 'Understanding Game Loops', 
 'In this post, we’ll explore the fundamentals of game loops and how they manage the flow of gameplay, rendering, and logic updates.', 'public'),
 
-- Blog by Michael Paulson (Author)
(5, 'Top 10 JavaScript Tips for Beginners', 
 'JavaScript can be tricky at first. Here are 10 tips that helped me write cleaner, more efficient code.', 'public'),
 
-- Blog by Jay R Conde (Admin)
(1, 'Welcome to the Blog Platform!', 
 'As the admin, I’m excited to launch this new blogging platform. Looking forward to seeing your amazing posts!', 'public'),

-- Blog by Casey Muratori
(2, 'Why Handmade Hero Matters', 
 'This is a deep dive into why I started Handmade Hero and what I believe about learning game programming the hard way.', 'public'),

-- Blog by Michael Paulson
(5, 'React vs Vue: A Developer’s Perspective', 
 'I’ve used both frameworks for years. Here’s how they compare in real-world use cases.', 'public');


INSERT INTO categories (name)
VALUES
('science'),
('programming'),
('games'),
('relationship'),
('math');
