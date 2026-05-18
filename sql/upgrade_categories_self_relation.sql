-- Upgrade: replace SUB_CAT with CAT self-relation (PARENT_ID)
-- Safe to run multiple times.

-- 1) Add PARENT_ID to CAT (idempotent)
SET @crm_cat_parent_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_CAT'
    AND COLUMN_NAME = 'PARENT_ID'
);
SET @crm_cat_parent_sql := IF(
  @crm_cat_parent_exists = 0,
  'ALTER TABLE `CRM_CAT` ADD COLUMN `PARENT_ID` varchar(10) NOT NULL default '''' AFTER `DESCRIPTION`, ADD KEY `PARENT_ID` (`PARENT_ID`)',
  'SELECT 1'
);
PREPARE crm_cat_parent_stmt FROM @crm_cat_parent_sql;
EXECUTE crm_cat_parent_stmt;
DEALLOCATE PREPARE crm_cat_parent_stmt;

-- 2) Migrate SUB_CAT rows into CAT as children (ID = SUB_CATEGORY, DESCRIPTION = DESCRIPTION, PARENT_ID = CAT)
-- Only runs if SUB_CAT exists.
SET @crm_subcat_exists := (
  SELECT COUNT(*)
  FROM information_schema.TABLES
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_SUB_CAT'
);
SET @crm_subcat_migrate_sql := IF(
  @crm_subcat_exists > 0,
  'INSERT IGNORE INTO `CRM_CAT` (`ID`,`DESCRIPTION`,`PARENT_ID`) SELECT `SUB_CATEGORY`,`DESCRIPTION`,`CAT` FROM `CRM_SUB_CAT`',
  'SELECT 1'
);
PREPARE crm_subcat_migrate_stmt FROM @crm_subcat_migrate_sql;
EXECUTE crm_subcat_migrate_stmt;
DEALLOCATE PREPARE crm_subcat_migrate_stmt;

-- 3) Ensure TABLE_PRODUCT has CAT_ID (idempotent)
SET @crm_prod_catid_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'CAT_ID'
);
SET @crm_prod_catid_sql := IF(
  @crm_prod_catid_exists = 0,
  'ALTER TABLE `CRM_TABLE_PRODUCT` ADD COLUMN `CAT_ID` varchar(10) NOT NULL default '''' AFTER `MANUFACTURER_ID`, ADD KEY `CAT_ID` (`CAT_ID`)',
  'SELECT 1'
);
PREPARE crm_prod_catid_stmt FROM @crm_prod_catid_sql;
EXECUTE crm_prod_catid_stmt;
DEALLOCATE PREPARE crm_prod_catid_stmt;

-- 4) Copy numeric SUBCAT_ID -> CAT_ID (SUB_CATEGORY code), if SUB_CAT and SUBCAT_ID exist
SET @crm_prod_subcat_col_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'SUBCAT_ID'
);
SET @crm_prod_copy_sql := IF(
  @crm_subcat_exists > 0 AND @crm_prod_subcat_col_exists > 0,
  'UPDATE `CRM_TABLE_PRODUCT` p JOIN `CRM_SUB_CAT` sc ON sc.ID = p.SUBCAT_ID SET p.CAT_ID = sc.SUB_CATEGORY WHERE (p.CAT_ID = '''' OR p.CAT_ID IS NULL)',
  'SELECT 1'
);
PREPARE crm_prod_copy_stmt FROM @crm_prod_copy_sql;
EXECUTE crm_prod_copy_stmt;
DEALLOCATE PREPARE crm_prod_copy_stmt;

-- 5) Drop SUB_CAT table (optional but requested)
SET @crm_subcat_drop_sql := IF(
  @crm_subcat_exists > 0,
  'DROP TABLE `CRM_SUB_CAT`',
  'SELECT 1'
);
PREPARE crm_subcat_drop_stmt FROM @crm_subcat_drop_sql;
EXECUTE crm_subcat_drop_stmt;
DEALLOCATE PREPARE crm_subcat_drop_stmt;

