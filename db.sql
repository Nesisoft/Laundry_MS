CREATE TABLE addresses (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `street` VARCHAR(255),
    `city` VARCHAR(255),
    `state` VARCHAR(255),
    `zip_code` VARCHAR(20),
    `country` VARCHAR(255),
    `latitude` DECIMAL(9,6),
    `longitude` DECIMAL(9,6),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX addresses_street_idx1 ON `addresses` (`street`);
CREATE INDEX addresses_city_idx1 ON `addresses` (`city`);
CREATE INDEX addresses_state_idx1 ON `addresses` (`state`);
CREATE INDEX addresses_zip_code_idx1 ON `addresses` (`zip_code`);
CREATE INDEX addresses_country_idx1 ON `addresses` (`country`);
CREATE INDEX addresses_latitude_idx1 ON `addresses` (`latitude`);
CREATE INDEX addresses_longitude_idx1 ON `addresses` (`longitude`);
CREATE INDEX addresses_created_at_idx1 ON `addresses` (`created_at`);
CREATE INDEX addresses_updated_at_idx1 ON `addresses` (`updated_at`);

CREATE TABLE settings (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `address_id` INT,
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    `name` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `email` VARCHAR(255),
    `logo` VARCHAR(255),
    `banner` VARCHAR(255),
    `motto` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX settings_name_idx1 ON `settings` (`name`);
CREATE INDEX settings_services_idx1 ON `settings` (`services`);
CREATE INDEX settings_logo_idx1 ON `settings` (`logo`);
CREATE INDEX settings_banner_idx1 ON `settings` (`banner`);
CREATE INDEX settings_logo_idx1 ON `settings` (`logo`);
CREATE INDEX settings_created_at_idx1 ON `settings` (`created_at`);
CREATE INDEX settings_updated_at_idx1 ON `settings` (`updated_at`);

CREATE TABLE users (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type` ENUM('admin', 'customer', 'manager') DEFAULT 'customer',
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `phone_number` VARCHAR(20) NOT NULL,
    `address_id` INT,
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(255) GENERATED ALWAYS AS (CONCAT(`first_name`,' ',`last_name`)) STORED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX users_type_idx1 ON `users` (`type`);
CREATE INDEX users_email_idx1 ON `users` (`email`);
CREATE INDEX users_password_idx1 ON `users` (`password`);
CREATE INDEX users_phone_number_idx1 ON `users` (`phone_number`);
CREATE INDEX users_first_name_idx1 ON `users` (`first_name`);
CREATE INDEX users_last_name_idx1 ON `users` (`last_name`);
CREATE INDEX users_full_name_idx1 ON `users` (`full_name`);
CREATE INDEX users_created_at_idx1 ON `users` (`created_at`);
CREATE INDEX users_updated_at_idx1 ON `users` (`updated_at`);

CREATE TABLE admins (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role` VARCHAR(50) NOT NULL,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX admins_role_idx1 ON `admins` (`role`);
CREATE INDEX admins_created_at_idx1 ON `admins` (`created_at`);
CREATE INDEX admins_updated_at_idx1 ON `admins` (`updated_at`);

CREATE TABLE managers (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role` VARCHAR(50) NOT NULL,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX managers_role_idx1 ON `managers` (`role`);
CREATE INDEX managers_created_at_idx1 ON `managers` (`created_at`);
CREATE INDEX managers_updated_at_idx1 ON `managers` (`updated_at`);

CREATE TABLE customers (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX customers_created_at_idx1 ON `customers` (`created_at`);
CREATE INDEX customers_updated_at_idx1 ON `customers` (`updated_at`);

CREATE TABLE user_notification_settings (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `type` ENUM('in-app', 'email', 'sms') NOT NULL,
    `enabled` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX user_notification_settings_type_idx1 ON `user_notification_settings` (`type`);
CREATE INDEX user_notification_settings_enabled_idx1 ON `user_notification_settings` (`enabled`);
CREATE INDEX user_notification_settings_created_at_idx1 ON `user_notification_settings` (`created_at`);
CREATE INDEX user_notification_settings_updated_at_idx1 ON `user_notification_settings` (`updated_at`);

CREATE TABLE promotional_codes (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255),
    `apply_to` ENUM('all', 'specific') DEFAULT 'all',
    `discount_type` ENUM('percentage', 'fixed') NOT NULL,
    `discount` DECIMAL(10,2) NOT NULL,
    `expiration_date` DATE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX promotional_codes_code_idx1 ON `promotional_codes` (`code`);
CREATE INDEX promotional_codes_discount_type_idx1 ON `promotional_codes` (`discount_type`);
CREATE INDEX promotional_codes_discount_idx1 ON `promotional_codes` (`discount`);
CREATE INDEX promotional_codes_expiration_date_idx1 ON `promotional_codes` (`expiration_date`);
CREATE INDEX promotional_codes_created_at_idx1 ON `promotional_codes` (`created_at`);
CREATE INDEX promotional_codes_updated_at_idx1 ON `promotional_codes` (`updated_at`);

CREATE TABLE users_promotional_codes (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code_id` INT,
    `user_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`code_id`) REFERENCES `promotional_codes`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX users_promotional_codes_created_at_idx1 ON `users_promotional_codes` (`created_at`);
CREATE INDEX users_promotional_codes_updated_at_idx1 ON `users_promotional_codes` (`updated_at`);

CREATE TABLE services (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255),
    `description` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX services_service_type_idx1 ON `services` (`name`);
CREATE INDEX services_created_at_idx1 ON `services` (`created_at`);
CREATE INDEX services_updated_at_idx1 ON `services` (`updated_at`);
INSERT INTO services(`name`) VALUES ('Washing only'), ('Ironing only'), ('Washing and Ironing');

CREATE TABLE pickup_requests (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `service_id` INT,
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `location` VARCHAR(255),
    `latitude` DECIMAL(9,6),
    `longitude` DECIMAL(9,6),
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `note` TEXT,
    `status` ENUM('pending', 'accepted', 'in-progress', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX pickup_requests_location_idx1 ON `pickup_requests` (`location`);
CREATE INDEX pickup_requests_latitude_idx1 ON `pickup_requests` (`latitude`);
CREATE INDEX pickup_requests_longitude_idx1 ON `pickup_requests` (`longitude`);
CREATE INDEX pickup_requests_date_idx1 ON `pickup_requests` (`date`);
CREATE INDEX pickup_requests_time_idx1 ON `pickup_requests` (`time`);
CREATE INDEX pickup_requests_amount_idx1 ON `pickup_requests` (`amount`);
CREATE INDEX pickup_requests_service_type_idx1 ON `pickup_requests` (`service_type`);
CREATE INDEX pickup_requests_created_at_idx1 ON `pickup_requests` (`created_at`);
CREATE INDEX pickup_requests_updated_at_idx1 ON `pickup_requests` (`updated_at`);

CREATE TABLE pickup_request_payments (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `request_id` INT,
    `amount` DECIMAL(10,2) NOT NULL,
    `method` ENUM('Cash', 'Mobile Money', 'Card') NOT NULL,
    `status` ENUM('Failed', 'Successful') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`request_id`) REFERENCES `pickup_requests`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX pickup_request_payments_amount_idx1 ON `pickup_request_payments` (`amount`);
CREATE INDEX pickup_request_payments_method_idx1 ON `pickup_request_payments` (`method`);
CREATE INDEX pickup_request_payments_status_idx1 ON `pickup_request_payments` (`status`);
CREATE INDEX pickup_request_payments_created_at_idx1 ON `pickup_request_payments` (`created_at`);
CREATE INDEX pickup_request_payments_updated_at_idx1 ON `pickup_request_payments` (`updated_at`);

CREATE TABLE items (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
);
CREATE INDEX items_name_idx1 ON `items` (`name`);
CREATE INDEX items_created_at_idx1 ON `items` (`created_at`);
CREATE INDEX items_updated_at_idx1 ON `items` (`updated_at`);

CREATE TABLE order_statuses (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX order_statuses_name_idx1 ON `order_statuses` (`name`);
CREATE INDEX order_statuses_created_at_idx1 ON `order_statuses` (`created_at`);
CREATE INDEX order_statuses_updated_at_idx1 ON `order_statuses` (`updated_at`);
INSERT INTO `order_statuses` (`name`) VALUES ('ready for washing'), ('ready for ironing'), ('ready for pickup'), ('ready for delivery');

CREATE TABLE orders (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `use_promo_code` BOOLEAN DEFAULT FALSE,
    `user_promotional_code_id` INT,
    `status` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`user_promotional_code_id`) REFERENCES `users_promotional_codes`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
);
CREATE INDEX orders_status_idx1 ON `orders` (`status`);
CREATE INDEX orders_created_at_idx1 ON `orders` (`created_at`);
CREATE INDEX orders_updated_at_idx1 ON `orders` (`updated_at`);

CREATE TABLE order_items (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT,
    `item_id` INT,
    `amount` DECIMAL(10,2) NOT NULL,
    `quantity` INT NOT NULL,
    `total_amount` DECIMAL(10, 2) GENERATED ALWAYS AS (`amount` * `quantity`) STORED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `pickup_requests`(id),
    FOREIGN KEY (`item_id`) REFERENCES `items`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX order_items_amount_idx1 ON `order_items` (`amount`);
CREATE INDEX order_items_quantity_idx1 ON `order_items` (`quantity`);
CREATE INDEX order_items_total_amount_idx1 ON `order_items` (`total_amount`);
CREATE INDEX order_items_created_at_idx1 ON `order_items` (`created_at`);
CREATE INDEX order_items_updated_at_idx1 ON `order_items` (`updated_at`);

CREATE TABLE invoices (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT,
    `amount` DECIMAL(10,2) NOT NULL,
    `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
    `actual_amount` DECIMAL(10,2) GENERATED ALWAYS AS (`amount` - `discount_amount`) STORED,
    `status` ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX invoices_amount_idx1 ON `invoices` (`amount`);
CREATE INDEX invoices_discount_idx1 ON `invoices` (`discount`);
CREATE INDEX invoices_discount_amount_idx1 ON `invoices` (`discount_amount`);
CREATE INDEX invoices_actual_amount_idx1 ON `invoices` (`actual_amount`);
CREATE INDEX invoices_status_idx1 ON `invoices` (`status`);
CREATE INDEX invoices_created_at_idx1 ON `invoices` (`created_at`);
CREATE INDEX invoices_updated_at_idx1 ON `invoices` (`updated_at`);

CREATE TABLE invoice_payments (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `invoice_id` INT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `method` ENUM('Cash', 'Mobile Money', 'Card') NOT NULL,
    `status` ENUM('Failed', 'Successful') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`invoice_id`) REFERENCES `invoices`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX invoice_payments_amount_idx1 ON `invoice_payments` (`amount`);
CREATE INDEX invoice_payments_method_idx1 ON `invoice_payments` (`method`);
CREATE INDEX invoice_payments_status_idx1 ON `invoice_payments` (`status`);
CREATE INDEX invoice_payments_created_at_idx1 ON `invoice_payments` (`created_at`);
CREATE INDEX invoice_payments_updated_at_idx1 ON `invoice_payments` (`updated_at`);

CREATE TABLE delivery_requests (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `location` VARCHAR(255),
    `latitude` DECIMAL(9,6),
    `longitude` DECIMAL(9,6),
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `note` TEXT,
    `status` ENUM('pending', 'accepted', 'in-progress', 'completed', 'cancelled') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX delivery_requests_status_idx1 ON `delivery_requests` (`status`);
CREATE INDEX delivery_requests_created_at_idx1 ON `delivery_requests` (`created_at`);
CREATE INDEX delivery_requests_updated_at_idx1 ON `delivery_requests` (`updated_at`);

CREATE TABLE delivery_request_payments (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `request_id` INT,
    FOREIGN KEY (`request_id`) REFERENCES `delivery_requests`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `amount` DECIMAL(10,2) NOT NULL,
    `method` ENUM('Cash', 'Mobile Money', 'Card') NOT NULL,
    `status` ENUM('Failed', 'Successful') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX delivery_request_payments_amount_idx1 ON `delivery_request_payments` (`amount`);
CREATE INDEX delivery_request_payments_method_idx1 ON `delivery_request_payments` (`method`);
CREATE INDEX delivery_request_payments_status_idx1 ON `delivery_request_payments` (`status`);
CREATE INDEX delivery_request_payments_created_at_idx1 ON `delivery_request_payments` (`created_at`);
CREATE INDEX delivery_request_payments_updated_at_idx1 ON `delivery_request_payments` (`created_at`);

CREATE TABLE service_ratings (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `rating` INT NOT NULL,
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX service_ratings_rating_idx1 ON `service_ratings` (`rating`);
CREATE INDEX service_ratings_created_at_idx1 ON `service_ratings` (`created_at`);
CREATE INDEX service_ratings_updated_at_idx1 ON `service_ratings` (`updated_at`);

CREATE TABLE user_notifications (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `type` ENUM('pickup', 'delivery', 'payment', 'general') NOT NULL,
    `title` VARCHAR(255),
    `message` TEXT,
    `is_read` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX user_notifications_type_idx1 ON `user_notifications` (`type`);
CREATE INDEX user_notifications_title_idx1 ON `user_notifications` (`title`);
CREATE INDEX user_notifications_is_read_idx1 ON `user_notifications` (`is_read`);
CREATE INDEX user_notifications_created_at_idx1 ON `user_notifications` (`created_at`);
CREATE INDEX user_notifications_updated_at_idx1 ON `user_notifications` (`updated_at`);

CREATE TABLE system_notifications (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    `title` VARCHAR(255),
    `message` TEXT,
    `is_read` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE INDEX system_notifications_title_idx1 ON `system_notifications` (`title`);
CREATE INDEX system_notifications_is_read_idx1 ON `system_notifications` (`is_read`);
CREATE INDEX system_notifications_created_at_idx1 ON `system_notifications` (`created_at`);
CREATE INDEX system_notifications_updated_at_idx1 ON `system_notifications` (`updated_at`);

CREATE TABLE `product_keys` (
    `key` VARCHAR(16) PRIMARY KEY,
    `allow_installations` INT DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `archived` TINYINT(1) DEFAULT 0
);
CREATE INDEX product_keys_allow_installations_idx1 ON `product_keys` (`allow_installations`);
CREATE INDEX product_keys_created_at_idx1 ON `product_keys` (`created_at`);
CREATE INDEX product_keys_archived_idx1 ON `product_keys` (`archived`);

INSERT INTO `product_keys` (`key`, `allow_installations`) VALUES ('ABCDEFGHIJKLMNOP', 1), ('ABC1DEF2GHI4JKL5', 2);

CREATE TABLE installations(
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_key` VARCHAR(16) NOT NULL,
    `device` JSON,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_key`) REFERENCES `product_keys`(`key`) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX installations_created_at_idx1 ON `installations` (`created_at`);
