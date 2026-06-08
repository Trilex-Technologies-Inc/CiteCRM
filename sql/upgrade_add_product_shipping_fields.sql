-- Add product shipping measurements and cart dimensions.
--
-- IMPORTANT:
-- - Replace `CRM_` with your configured PRFX if needed.
-- - Safe to run multiple times in MySQL/MariaDB.

SET @crm_prod_weight_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'PRODUCT_WEIGHT'
);
SET @crm_prod_weight_sql := IF(
  @crm_prod_weight_exists = 0,
  'ALTER TABLE `CRM_TABLE_PRODUCT` ADD COLUMN `PRODUCT_WEIGHT` decimal(10,2) NOT NULL default ''0.00'' AFTER `PRODUCT_PRICE`',
  'SELECT 1'
);
PREPARE crm_prod_weight_stmt FROM @crm_prod_weight_sql;
EXECUTE crm_prod_weight_stmt;
DEALLOCATE PREPARE crm_prod_weight_stmt;

SET @crm_prod_length_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'PRODUCT_LENGTH'
);
SET @crm_prod_length_sql := IF(
  @crm_prod_length_exists = 0,
  'ALTER TABLE `CRM_TABLE_PRODUCT` ADD COLUMN `PRODUCT_LENGTH` decimal(10,2) NOT NULL default ''0.00'' AFTER `PRODUCT_WEIGHT`',
  'SELECT 1'
);
PREPARE crm_prod_length_stmt FROM @crm_prod_length_sql;
EXECUTE crm_prod_length_stmt;
DEALLOCATE PREPARE crm_prod_length_stmt;

SET @crm_prod_width_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'PRODUCT_WIDTH'
);
SET @crm_prod_width_sql := IF(
  @crm_prod_width_exists = 0,
  'ALTER TABLE `CRM_TABLE_PRODUCT` ADD COLUMN `PRODUCT_WIDTH` decimal(10,2) NOT NULL default ''0.00'' AFTER `PRODUCT_LENGTH`',
  'SELECT 1'
);
PREPARE crm_prod_width_stmt FROM @crm_prod_width_sql;
EXECUTE crm_prod_width_stmt;
DEALLOCATE PREPARE crm_prod_width_stmt;

SET @crm_prod_height_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'PRODUCT_HEIGHT'
);
SET @crm_prod_height_sql := IF(
  @crm_prod_height_exists = 0,
  'ALTER TABLE `CRM_TABLE_PRODUCT` ADD COLUMN `PRODUCT_HEIGHT` decimal(10,2) NOT NULL default ''0.00'' AFTER `PRODUCT_WIDTH`',
  'SELECT 1'
);
PREPARE crm_prod_height_stmt FROM @crm_prod_height_sql;
EXECUTE crm_prod_height_stmt;
DEALLOCATE PREPARE crm_prod_height_stmt;

SET @crm_cart_length_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_CART'
    AND COLUMN_NAME = 'Length'
);
SET @crm_cart_length_sql := IF(
  @crm_cart_length_exists = 0,
  'ALTER TABLE `CRM_CART` ADD COLUMN `Length` varchar(20) NOT NULL default '''' AFTER `Weight`',
  'SELECT 1'
);
PREPARE crm_cart_length_stmt FROM @crm_cart_length_sql;
EXECUTE crm_cart_length_stmt;
DEALLOCATE PREPARE crm_cart_length_stmt;

SET @crm_cart_width_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_CART'
    AND COLUMN_NAME = 'Width'
);
SET @crm_cart_width_sql := IF(
  @crm_cart_width_exists = 0,
  'ALTER TABLE `CRM_CART` ADD COLUMN `Width` varchar(20) NOT NULL default '''' AFTER `Length`',
  'SELECT 1'
);
PREPARE crm_cart_width_stmt FROM @crm_cart_width_sql;
EXECUTE crm_cart_width_stmt;
DEALLOCATE PREPARE crm_cart_width_stmt;

SET @crm_cart_height_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_CART'
    AND COLUMN_NAME = 'Height'
);
SET @crm_cart_height_sql := IF(
  @crm_cart_height_exists = 0,
  'ALTER TABLE `CRM_CART` ADD COLUMN `Height` varchar(20) NOT NULL default '''' AFTER `Width`',
  'SELECT 1'
);
PREPARE crm_cart_height_stmt FROM @crm_cart_height_sql;
EXECUTE crm_cart_height_stmt;
DEALLOCATE PREPARE crm_cart_height_stmt;
