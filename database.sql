-- Database: cameroonian_restaurant
CREATE DATABASE IF NOT EXISTS cameroonian_restaurant;
USE cameroonian_restaurant;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: meals
CREATE TABLE IF NOT EXISTS meals (
    meal_id INT PRIMARY KEY AUTO_INCREMENT,
    meal_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50),
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: orders
CREATE TABLE IF NOT EXISTS orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('online', 'delivery') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    delivery_address TEXT NOT NULL,
    order_status ENUM('pending', 'processing', 'delivered', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Table: order_items
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    meal_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (meal_id) REFERENCES meals(meal_id)
);

-- Pre-populate meals
INSERT INTO meals (meal_name, description, price, category, image_url) VALUES
('Ndole', 'Bitterleaf stew with peanuts', 2500.00, 'Main Course', NULL),
('Eru', 'Vegetable soup with waterleaf', 3000.00, 'Main Course', NULL),
('Koki', 'Steamed bean pudding', 1500.00, 'Appetizer', NULL),
('Achu Soup', 'Yellow soup with cocoyam', 3500.00, 'Main Course', NULL),
('Puff Puff', 'Fried dough balls', 500.00, 'Dessert', NULL),
('Plantains', 'Fried or boiled', 1000.00, 'Side Dish', NULL),
('Suya', 'Spiced grilled meat', 2000.00, 'Appetizer', NULL),
('Chapman', 'Local cocktail drink', 1200.00, 'Drinks', NULL);
