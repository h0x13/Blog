DROP DATABASE IF EXISTS blogdb;
CREATE DATABASE blogdb;
USE blogdb;

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
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


INSERT INTO `users` (first_name, last_name, middle_name, email, password, role)
VALUES ('Jay R', 'Conde', NULL, 'jayr@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'admin'),
       ('Casey', 'Muratori', NULL, 'casey@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'author'),
       ('Theo', 'Browne', 'Smith', 'theo@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'viewer'),
       ('Martin', 'Davis', NULL, 'martin@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'viewer'),
       ('Michael', 'Paulson', NULL, 'michael@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'author'),
       ('Linus Gabriel', 'Sebastian', NULL, 'linus@gmail.com', '$2y$10$dqPj.i9fI8QCb0zceTSwDu3uC0TxPm530.MEfc.Nk7zdYaHRO372G', 'viewer');
