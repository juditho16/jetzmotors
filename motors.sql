-- JETZ MOTORS CLEAN DATABASE (FINAL BUILD)
-- Fully error-free import for MariaDB 10.4+
-- Includes: categories, brands, models, parts, users, admin, vehicles,
-- mechanics, appointments, invoices, invoice_items, sales, memberships, reset_tokens

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE IF NOT EXISTS motors CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE motors;

-- --------------------------------------------------------
-- CATEGORIES
-- --------------------------------------------------------
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (id,name,created_at) VALUES
(1,'Accessories',NOW()),
(2,'Spare Parts',NOW()),
(3,'Lubricants',NOW()),
(4,'Batteries',NOW()),
(5,'Electrical',NOW()),
(6,'Tires',NOW()),
(7,'Tools & Garage',NOW());

-- --------------------------------------------------------
-- BRANDS
-- --------------------------------------------------------
DROP TABLE IF EXISTS brands;
CREATE TABLE brands (
  id INT NOT NULL AUTO_INCREMENT,
  category_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_brands_category (category_id),
  UNIQUE KEY uq_brands_name_cat (category_id,name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO brands (id,category_id,name) VALUES
(1,2,'OEM'),
(2,2,'Aftermarket Pro'),
(3,2,'Racing Boy'),
(4,3,'Shell Advance'),
(5,3,'Motul'),
(6,4,'Motolite'),
(7,4,'FB Battery'),
(8,5,'Bosch'),
(9,5,'NGK'),
(10,6,'Pirelli'),
(11,6,'IRC'),
(12,1,'Aspira'),
(13,1,'SaddlePro'),
(14,7,'Klein Tools'),
(15,7,'Snap-On'),
(16,2,'Denso'),
(17,2,'Comstar'),
(18,1,'OKM'),
(19,5,'Denso Electrical'),
(20,3,'Castrol');

-- --------------------------------------------------------
-- MODELS
-- --------------------------------------------------------
DROP TABLE IF EXISTS models;
CREATE TABLE models (
  id INT NOT NULL AUTO_INCREMENT,
  brand_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_models_brand (brand_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO models (id,brand_id,name) VALUES
(1,1,'Universal Fit'),
(2,1,'Standard Replacement'),
(3,2,'Pro Chain 520'),
(4,3,'Alloy Rim Sport'),
(5,4,'4T 10W-40'),
(6,5,'300V 10W-50'),
(7,6,'3SM Battery'),
(8,7,'Maintenance Free 4SM'),
(9,8,'Alternator 12V (Bosch)'),
(10,9,'CPR8EA-9 (NGK)'),
(11,10,'Sport Tire 17\"'),
(12,11,'Underbone 17\"'),
(13,12,'Seat Cover Model A'),
(14,13,'Handle Grip Deluxe'),
(15,14,'Torque Wrench 1/2\" 100Nm'),
(16,15,'Professional Socket Set 6-22mm'),
(17,16,'Denso Starter Motor'),
(18,17,'Comstar Sprocket 428'),
(19,18,'OKM Mirror Type-A'),
(20,19,'Denso Ignition Coil'),
(21,20,'Castrol 4T Scooter 20W-40'),
(22,3,'Brake Lever Set Pro'),
(23,2,'Heavy Duty Brake Pads'),
(24,1,'Premium Drive Belt'),
(25,2,'Racing Clutch Kit');

-- --------------------------------------------------------
-- PARTS
-- --------------------------------------------------------
DROP TABLE IF EXISTS parts;
CREATE TABLE parts (
  id INT NOT NULL AUTO_INCREMENT,
  sku VARCHAR(60) NOT NULL,
  barcode VARCHAR(64) DEFAULT NULL,
  category_id INT DEFAULT NULL,
  brand_id INT DEFAULT NULL,
  model_id INT DEFAULT NULL,
  name VARCHAR(200) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_parts_sku (sku),
  UNIQUE KEY uq_parts_barcode (barcode),
  KEY idx_parts_category (category_id),
  KEY idx_parts_brand (brand_id),
  KEY idx_parts_model (model_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO parts VALUES
(1,'OIL-1L-001','8901000000001',3,4,5,'Engine Oil 1L - Shell Advance 10W-40',320,120,1,NOW()),
(2,'OIL-4L-002','8901000000002',3,5,6,'Engine Oil 4L - Motul 300V',1450,30,1,NOW()),
(3,'SPK-NGK-003','8901000000003',5,9,10,'Spark Plug NGK CPR8EA-9',120,240,1,NOW()),
(4,'BRK-PAD-004','8901000000004',2,2,23,'Brake Pads - Heavy Duty',450,50,1,NOW()),
(5,'CHAIN-520-005','8901000000005',2,2,3,'Drive Chain 520 Pro',980,35,1,NOW()),
(6,'BAT-3SM-006','8901000000006',4,6,7,'Motor Battery 3SM - Motolite',1800,12,1,NOW()),
(7,'BAT-MF-007','8901000000007',4,7,8,'Maintenance Free Battery - FB',2500,8,1,NOW()),
(8,'TIRE-17-008','8901000000008',6,10,11,'Sport Tire 17\" - Pirelli',2800,16,1,NOW()),
(9,'HLT-BULB-009','8901000000009',5,8,9,'Halogen Headlight Bulb - Bosch',250,60,1,NOW()),
(10,'CLT-001-010','8901000000010',2,2,25,'Racing Clutch Kit',5200,5,1,NOW());

-- --------------------------------------------------------
-- USERS
-- --------------------------------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50),
  middle_initial CHAR(1),
  last_name VARCHAR(50),
  suffix VARCHAR(10),
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  phone VARCHAR(20),
  address TEXT,
  password VARCHAR(255) NOT NULL,
  is_member TINYINT(1) DEFAULT 0,
  member_until DATE DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users VALUES
(1,'System','A','Admin','', 'System Admin','admin@example.com','09170000001','Main Street','admin',0,NULL,NOW()),
(2,'Sample',NULL,'User','', 'Sample User','user@example.com','09170000002','Client Address','password',0,NULL,NOW());

-- --------------------------------------------------------
-- ADMIN
-- --------------------------------------------------------
DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_admin_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin VALUES (1,'System Administrator','admin','admin',NOW());

-- --------------------------------------------------------
-- MECHANICS TABLE
-- --------------------------------------------------------
DROP TABLE IF EXISTS mechanics;
CREATE TABLE mechanics (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  specialty VARCHAR(120),
  phone VARCHAR(30),
  status ENUM('Available','Busy','Inactive') DEFAULT 'Available',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO mechanics VALUES
(1,'Juan Dela Cruz','Engine Specialist','09170000011','Available'),
(2,'Marco Technician','Electrical','09170000012','Busy');

-- --------------------------------------------------------
-- VEHICLES
-- --------------------------------------------------------
DROP TABLE IF EXISTS vehicles;
CREATE TABLE vehicles (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  brand_id INT DEFAULT NULL,
  model_id INT DEFAULT NULL,
  year YEAR NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_vehicles_user (user_id),
  KEY idx_vehicles_brand (brand_id),
  KEY idx_vehicles_model (model_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO vehicles VALUES (1,2,10,11,2019,NOW());

-- --------------------------------------------------------
-- STOCK LOGS
-- --------------------------------------------------------
DROP TABLE IF EXISTS stock_logs;
CREATE TABLE stock_logs (
  id INT NOT NULL AUTO_INCREMENT,
  part_id INT NOT NULL,
  type ENUM('IN','OUT') NOT NULL,
  quantity INT NOT NULL,
  remarks TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_stock_part (part_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- SERVICES
-- --------------------------------------------------------
DROP TABLE IF EXISTS services;
CREATE TABLE services (
  id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  base_price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO services VALUES
(1,'Oil Change','Complete oil change service',1000,NOW()),
(2,'Brake Replacement','Replace brake pads',1500,NOW()),
(3,'Full Service','Tune-up + inspection',3000,NOW());

-- --------------------------------------------------------
-- INVOICES
-- --------------------------------------------------------
DROP TABLE IF EXISTS invoices;
CREATE TABLE invoices (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT DEFAULT NULL,
  total DECIMAL(10,2) NOT NULL,
  discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_invoices_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- INVOICE ITEMS
-- --------------------------------------------------------
DROP TABLE IF EXISTS invoice_items;
CREATE TABLE invoice_items (
  id INT NOT NULL AUTO_INCREMENT,
  invoice_id INT NOT NULL,
  product_id INT NOT NULL,
  qty INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_invoice_items_invoice (invoice_id),
  KEY idx_invoice_items_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- SALES
-- --------------------------------------------------------
DROP TABLE IF EXISTS sales;
CREATE TABLE sales (
  id INT NOT NULL AUTO_INCREMENT,
  invoice_id INT NOT NULL,
  user_id INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  final_amount DECIMAL(10,2) NOT NULL,
  purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_sales_invoice (invoice_id),
  KEY idx_sales_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- MEMBERSHIPS
-- --------------------------------------------------------
DROP TABLE IF EXISTS memberships;
CREATE TABLE memberships (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  membership_id VARCHAR(50) NOT NULL,
  discount_rate DECIMAL(5,2) DEFAULT 3.00,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  status ENUM('Active','Expired') DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_membership_code (membership_id),
  KEY idx_memberships_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- RESET TOKENS
-- --------------------------------------------------------
DROP TABLE IF EXISTS reset_tokens;
CREATE TABLE reset_tokens (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  phone VARCHAR(20),
  otp_code VARCHAR(10),
  expires_at DATETIME,
  is_used TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- APPOINTMENTS
-- --------------------------------------------------------
DROP TABLE IF EXISTS appointments;
CREATE TABLE appointments (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT NOT NULL,
  vehicle_id INT NOT NULL,
  service_id INT NOT NULL,
  mechanic_id INT DEFAULT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  remarks TEXT,
  status ENUM('Pending','Confirmed','In Progress','Completed','Cancelled') DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_appointments_user (user_id),
  KEY idx_appointments_vehicle (vehicle_id),
  KEY idx_appointments_service (service_id),
  KEY idx_appointments_mechanic (mechanic_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE vehicle_brands (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE vehicle_models (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand_id INT NOT NULL,
  name VARCHAR(150) NOT NULL,
  FOREIGN KEY (brand_id) REFERENCES vehicle_brands(id) ON DELETE CASCADE
);

INSERT INTO vehicle_brands (name) VALUES
('Yamaha'), ('Honda'), ('Suzuki'), ('Kawasaki');

INSERT INTO vehicle_models (brand_id,name) VALUES
(1,'Aerox 155'),
(1,'NMAX 155'),
(2,'CLICK 125'),
(2,'Beat 110'),
(3,'Raider 150'),
(4,'Rouser NS200');



-- --------------------------------------------------------
-- FOREIGN KEYS
-- --------------------------------------------------------

ALTER TABLE brands
  ADD CONSTRAINT fk_brands_category FOREIGN KEY (category_id)
    REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE models
  ADD CONSTRAINT fk_models_brand FOREIGN KEY (brand_id)
    REFERENCES brands(id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE parts
  ADD CONSTRAINT fk_parts_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_parts_brand FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_parts_model FOREIGN KEY (model_id) REFERENCES models(id) ON DELETE SET NULL;

ALTER TABLE vehicles
  ADD CONSTRAINT fk_vbrand FOREIGN KEY (brand_id) REFERENCES vehicle_brands(id) ON DELETE SET NULL,
  ADD CONSTRAINT fk_vmodel FOREIGN KEY (model_id) REFERENCES vehicle_models(id) ON DELETE SET NULL;

ALTER TABLE stock_logs
  ADD CONSTRAINT fk_stocklogs_part FOREIGN KEY (part_id) REFERENCES parts(id) ON DELETE CASCADE;

ALTER TABLE invoices
  ADD CONSTRAINT fk_invoices_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

ALTER TABLE invoice_items
  ADD CONSTRAINT fk_invoiceitems_invoice FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_invoiceitems_product FOREIGN KEY (product_id) REFERENCES parts(id) ON DELETE RESTRICT;

ALTER TABLE sales
  ADD CONSTRAINT fk_sales_invoice FOREIGN KEY (invoice_id) REFERENCES invoices(id),
  ADD CONSTRAINT fk_sales_user FOREIGN KEY (user_id) REFERENCES users(id);

ALTER TABLE memberships
  ADD CONSTRAINT fk_memberships_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE appointments
  ADD CONSTRAINT fk_appointments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_appointments_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_appointments_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
  ADD CONSTRAINT fk_appointments_mechanic FOREIGN KEY (mechanic_id) REFERENCES mechanics(id) ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
