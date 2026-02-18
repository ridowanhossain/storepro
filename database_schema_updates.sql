-- Database Schema Updates and Migrations

-- 1. Create order_returns table
CREATE TABLE IF NOT EXISTS `order_returns` (
  `return_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT(11) NOT NULL,
  `order_item_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `return_quantity` DECIMAL(10,2) NOT NULL,
  `return_amount` DECIMAL(10,2) NOT NULL,
  `return_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `return_reason` TEXT,
  `processed_by` VARCHAR(50) NOT NULL,
  INDEX(`order_id`),
  INDEX(`product_id`),
  INDEX(`return_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2. Insert default shop settings
INSERT IGNORE INTO `shop_settings` (`id`, `shop_name`, `owner_name`, `allproduct_name`, `shop_address`, `shop_mobile`, `company_name`, `contact_no`, `email_addr`) VALUES
(1, '', '', '', '', '', '', '', '');


-- 3. Update date columns to DATETIME
ALTER TABLE `company` MODIFY `c_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `orders` MODIFY `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `order_item` MODIFY `order_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `pement_details` MODIFY `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `pro` MODIFY `pdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `product` MODIFY `pdate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `spend` MODIFY `spend_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `spend_report` MODIFY `paid_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `sr` MODIFY `c_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- 4. Create due table
-- Create a separate table to track manual previous due (opening balance)
CREATE TABLE IF NOT EXISTS `due` (
  `due_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `sr_id` INT(11) NOT NULL,
  `due` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX(`sr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
