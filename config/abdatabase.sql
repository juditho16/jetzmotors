-- ===============================
-- Users Table
-- ===============================
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(50) NOT NULL,
  `middlename` VARCHAR(50) DEFAULT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `suffix` VARCHAR(10) DEFAULT NULL,
  `sex` ENUM('male','female') NOT NULL,
  `age` INT(11) DEFAULT NULL,
  `birthdate` DATE DEFAULT NULL,
  `phone_number` VARCHAR(15) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Admin Table
-- ===============================
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` INT(11) NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(50) NOT NULL,
  `lastname` VARCHAR(50) NOT NULL,
  `role` ENUM('super_admin','staff') DEFAULT 'staff',
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Brands Table
-- ===============================
CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Installment Plans Table
-- ===============================
CREATE TABLE IF NOT EXISTS `installment_plans` (
  `plan_id` INT(11) NOT NULL AUTO_INCREMENT,
  `price` DECIMAL(10,2) NOT NULL,
  `downpayment` DECIMAL(10,2) NOT NULL,
  `monthly_4` DECIMAL(10,2) NOT NULL,
  `monthly_6` DECIMAL(10,2) NOT NULL,
  `monthly_9` DECIMAL(10,2) NOT NULL,
  `monthly_12` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Products Table
-- ===============================
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `brand_id` INT(11) NOT NULL,
  `plan_id` INT(11) DEFAULT NULL,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_quantity` INT(11) NOT NULL,
  `ram` ENUM('4GB','6GB','8GB+') DEFAULT NULL,
  `storage` ENUM('64GB','128GB','256GB+') DEFAULT NULL,
  `operating_system` ENUM('Android','iOS') DEFAULT NULL,
  `category` ENUM('Accessories','Tablets','Mobile Phones') NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `brand_id` (`brand_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`brand_id`) ON DELETE CASCADE,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `installment_plans` (`plan_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Application Form Table
-- ===============================
CREATE TABLE IF NOT EXISTS `application_form` (
  `application_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `plan_id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `age` INT(11) NOT NULL,
  `brand_and_unit` VARCHAR(200) NOT NULL,
  `downpayment` DECIMAL(10,2) NOT NULL,
  `installment_plan` ENUM('4 months','6 months','9 months','12 months') NOT NULL,
  `address` TEXT NOT NULL,
  `facebook_account` VARCHAR(200) NOT NULL,
  `contact_number_1` VARCHAR(20) NOT NULL,
  `contact_number_2` VARCHAR(20) DEFAULT NULL,
  `occupation` VARCHAR(100) NOT NULL,
  `valid_id_1` VARCHAR(255) NOT NULL,
  `valid_id_2` VARCHAR(255) DEFAULT NULL,
  `agreement_pdf` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending approval','approved','rejected') DEFAULT 'pending approval',
  `approved_by` INT(11) DEFAULT NULL,
  `rejected_by` INT(11) DEFAULT NULL,
  `archive_date` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`application_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `plan_id` (`plan_id`),
  KEY `approved_by` (`approved_by`),
  KEY `rejected_by` (`rejected_by`),
  CONSTRAINT `application_form_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `application_form_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `application_form_ibfk_3` FOREIGN KEY (`plan_id`) REFERENCES `installment_plans` (`plan_id`) ON DELETE CASCADE,
  CONSTRAINT `application_form_ibfk_4` FOREIGN KEY (`approved_by`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL,
  CONSTRAINT `application_form_ibfk_5` FOREIGN KEY (`rejected_by`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Cart Table
-- ===============================
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Orders Table
-- ===============================
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `plan_id` INT(11) DEFAULT NULL,
  `quantity` INT(11) NOT NULL,
  `order_status` ENUM('pending approval','approved','rejected','ready for pick-up','completed') DEFAULT 'pending approval',
  `total_amount` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`plan_id`) REFERENCES `installment_plans` (`plan_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Installments Table
-- ===============================
CREATE TABLE IF NOT EXISTS `installments` (
  `installment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `plan_id` INT(11) NOT NULL,
  `application_id` INT(11) NOT NULL,
  `installment_amount` DECIMAL(10,2) NOT NULL,
  `remaining_balance` DECIMAL(10,2) NOT NULL,
  `next_due_date` DATE NOT NULL,
  `installment_status` ENUM('active','completed','cancelled') DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`installment_id`),
  KEY `order_id` (`order_id`),
  KEY `plan_id` (`plan_id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `installments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `installments_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `installment_plans` (`plan_id`) ON DELETE CASCADE,
  CONSTRAINT `installments_ibfk_3` FOREIGN KEY (`application_id`) REFERENCES `application_form` (`application_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Media Table
-- ===============================
CREATE TABLE IF NOT EXISTS `media` (
  `media_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `product_id` INT(11) DEFAULT NULL,
  `type` ENUM('product_image','primary_id','secondary_id') NOT NULL,
  `file_url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`media_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  CONSTRAINT `media_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Notifications Table
-- ===============================
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Password Reset Tokens Table
-- ===============================
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `otp_code` VARCHAR(6) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `expires_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Payments Table
-- ===============================
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) DEFAULT NULL,
  `installment_id` INT(11) DEFAULT NULL,
  `payment_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `amount_paid` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `payment_status` ENUM('pending','completed','failed','overdue') DEFAULT 'pending',
  `transaction_id` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`),
  KEY `installment_id` (`installment_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`installment_id`) REFERENCES `installments` (`installment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Permissions Table
-- ===============================
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `description` TEXT DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Role Permissions Table
-- ===============================
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role` ENUM('super_admin','staff') NOT NULL,
  `permission_id` INT(11) NOT NULL,
  PRIMARY KEY (`role`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Reserved Products Table
-- ===============================
CREATE TABLE IF NOT EXISTS `reserved_products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `user_id` INT(11) DEFAULT NULL,
  `reserved_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `status` ENUM('pending','processed') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `reserved_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Stock Logs Table
-- ===============================
CREATE TABLE IF NOT EXISTS `stock_logs` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `operation` ENUM('in','out') NOT NULL,
  `quantity` INT(11) NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`log_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `stock_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ===============================
-- Active Installments View
-- ===============================
DROP VIEW IF EXISTS `active_installments`;
CREATE ALGORITHM=UNDEFINED 
  DEFINER=`root`@`localhost` 
  SQL SECURITY DEFINER 
VIEW `active_installments` AS 
  SELECT 
    u.user_id,
    CONCAT(u.firstname, ' ', u.lastname) AS full_name,
    o.order_id,
    p.name AS product_name,
    i.installment_id,
    i.remaining_balance,
    i.next_due_date,
    i.installment_status
  FROM users u
  JOIN orders o ON u.user_id = o.user_id
  JOIN installments i ON o.order_id = i.order_id
  JOIN products p ON o.product_id = p.product_id
  WHERE i.installment_status = 'active';
