-- 创建数据库
CREATE DATABASE IF NOT EXISTS enterprise_db;
USE enterprise_db;

-- 管理员表
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('superadmin', 'admin', 'editor') DEFAULT 'admin',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 产品表
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2),
    category VARCHAR(50),
    stock INT DEFAULT 0,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 新闻表
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    author VARCHAR(50),
    published_date DATE,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 插入管理员数据
INSERT INTO admin_users (username, password, email, role) VALUES
('admin', '$2y$10$YourHashHere', 'admin@enterprise.com', 'superadmin'),
('manager1', '$2y$10$YourHashHere2', 'manager@enterprise.com', 'admin');

-- 密码分别是: Admin@123456 和 Manager@123456
-- 实际使用请用 password_hash() 生成

UPDATE admin_users SET password = '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1orVp0JZ8L9H0Qx5Qv5Qv5Qv5Qv5Qv5' WHERE username = 'admin';
UPDATE admin_users SET password = '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1orVp0JZ8L9H0Qx5Qv5Qv5Qv5Qv5Qv5' WHERE username = 'manager1';

-- 插入测试产品数据
INSERT INTO products (name, description, price, category, stock) VALUES
('企业级服务器', '高性能服务器，适合大型企业', 29999.00, '硬件', 50),
('办公软件套装', '正版办公软件授权', 1999.00, '软件', 999),
('云存储服务', '企业级云存储解决方案', 5999.00, '服务', 100),
('网络安全防火墙', '下一代防火墙设备', 15999.00, '安全', 30);

-- 插入测试新闻
INSERT INTO news (title, content, author, published_date) VALUES
('公司2024年业绩发布会', '公司发布2024年度财务报告...', 'admin', '2024-03-15'),
('新产品发布会通知', '将于下月举行新产品发布会...', 'manager1', '2024-03-10');