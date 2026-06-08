-- Adds manufacturer + product catalog tables (safe to run multiple times).

CREATE TABLE IF NOT EXISTS `CRM_TABLE_MANUFACTURER` (
  `MANUFACTURER_ID` int(11) NOT NULL auto_increment,
  `MANUFACTURER_NAME` varchar(120) NOT NULL default '',
  `MANUFACTURER_WEBSITE` varchar(255) NOT NULL default '',
  `MANUFACTURER_ACTIVE` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`MANUFACTURER_ID`),
  UNIQUE KEY `MANUFACTURER_NAME` (`MANUFACTURER_NAME`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `CRM_TABLE_WAREHOUSE` (
  `WAREHOUSE_ID` int(11) NOT NULL auto_increment,
  `WAREHOUSE_NAME` varchar(120) NOT NULL default '',
  `WAREHOUSE_CODE` varchar(40) NOT NULL default '',
  `WAREHOUSE_ADDRESS` varchar(255) NOT NULL default '',
  `WAREHOUSE_CITY` varchar(80) NOT NULL default '',
  `WAREHOUSE_STATE` varchar(80) NOT NULL default '',
  `WAREHOUSE_ZIP` varchar(20) NOT NULL default '',
  `WAREHOUSE_COUNTRY` varchar(80) NOT NULL default '',
  `WAREHOUSE_ACTIVE` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`WAREHOUSE_ID`),
  UNIQUE KEY `WAREHOUSE_NAME` (`WAREHOUSE_NAME`),
  KEY `WAREHOUSE_CODE` (`WAREHOUSE_CODE`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `CRM_TABLE_PRODUCT` (
  `PRODUCT_ID` int(11) NOT NULL auto_increment,
  `MANUFACTURER_ID` int(11) NOT NULL default '0',
  `WAREHOUSE_ID` int(11) NOT NULL default '0',
  `CAT_ID` varchar(10) NOT NULL default '',
  `SUBCAT_ID` int(20) NOT NULL default '0',
  `PRODUCT_SKU` varchar(60) NOT NULL default '',
  `PRODUCT_NAME` varchar(120) NOT NULL default '',
  `PRODUCT_DESCRIPTION` text,
  `PRODUCT_PRICE` decimal(10,2) NOT NULL default '0.00',
  `PRODUCT_WEIGHT` decimal(10,2) NOT NULL default '0.00',
  `PRODUCT_LENGTH` decimal(10,2) NOT NULL default '0.00',
  `PRODUCT_WIDTH` decimal(10,2) NOT NULL default '0.00',
  `PRODUCT_HEIGHT` decimal(10,2) NOT NULL default '0.00',
  `PRODUCT_ACTIVE` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`PRODUCT_ID`),
  KEY `MANUFACTURER_ID` (`MANUFACTURER_ID`),
  KEY `WAREHOUSE_ID` (`WAREHOUSE_ID`),
  KEY `CAT_ID` (`CAT_ID`),
  KEY `SUBCAT_ID` (`SUBCAT_ID`),
  KEY `PRODUCT_SKU` (`PRODUCT_SKU`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

-- If the product table already exists, add `CAT_ID` once (idempotent).
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

-- If the product table already exists, add `WAREHOUSE_ID` once (idempotent).
SET @crm_prod_warehouse_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'WAREHOUSE_ID'
);
SET @crm_prod_warehouse_sql := IF(
  @crm_prod_warehouse_exists = 0,
  'ALTER TABLE `CRM_TABLE_PRODUCT` ADD COLUMN `WAREHOUSE_ID` int(11) NOT NULL default ''0'' AFTER `MANUFACTURER_ID`, ADD KEY `WAREHOUSE_ID` (`WAREHOUSE_ID`)',
  'SELECT 1'
);
PREPARE crm_prod_warehouse_stmt FROM @crm_prod_warehouse_sql;
EXECUTE crm_prod_warehouse_stmt;
DEALLOCATE PREPARE crm_prod_warehouse_stmt;

-- If the product table already exists, add `SUBCAT_ID` once (idempotent).
SET @crm_prod_subcat_exists := (
  SELECT COUNT(*)
  FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'CRM_TABLE_PRODUCT'
    AND COLUMN_NAME = 'SUBCAT_ID'
);
SET @crm_prod_subcat_sql := IF(
  @crm_prod_subcat_exists = 0,
  'ALTER TABLE `CRM_TABLE_PRODUCT` ADD COLUMN `SUBCAT_ID` int(20) NOT NULL default ''0'' AFTER `MANUFACTURER_ID`, ADD KEY `SUBCAT_ID` (`SUBCAT_ID`)',
  'SELECT 1'
);
PREPARE crm_prod_stmt FROM @crm_prod_subcat_sql;
EXECUTE crm_prod_stmt;
DEALLOCATE PREPARE crm_prod_stmt;
