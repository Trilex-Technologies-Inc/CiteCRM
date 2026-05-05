-- Adds manufacturer + product catalog tables (safe to run multiple times).

CREATE TABLE IF NOT EXISTS `CRM_TABLE_MANUFACTURER` (
  `MANUFACTURER_ID` int(11) NOT NULL auto_increment,
  `MANUFACTURER_NAME` varchar(120) NOT NULL default '',
  `MANUFACTURER_WEBSITE` varchar(255) NOT NULL default '',
  `MANUFACTURER_ACTIVE` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`MANUFACTURER_ID`),
  UNIQUE KEY `MANUFACTURER_NAME` (`MANUFACTURER_NAME`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `CRM_TABLE_PRODUCT` (
  `PRODUCT_ID` int(11) NOT NULL auto_increment,
  `MANUFACTURER_ID` int(11) NOT NULL default '0',
  `SUBCAT_ID` int(20) NOT NULL default '0',
  `PRODUCT_SKU` varchar(60) NOT NULL default '',
  `PRODUCT_NAME` varchar(120) NOT NULL default '',
  `PRODUCT_DESCRIPTION` text,
  `PRODUCT_PRICE` decimal(10,2) NOT NULL default '0.00',
  `PRODUCT_ACTIVE` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`PRODUCT_ID`),
  KEY `MANUFACTURER_ID` (`MANUFACTURER_ID`),
  KEY `SUBCAT_ID` (`SUBCAT_ID`),
  KEY `PRODUCT_SKU` (`PRODUCT_SKU`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

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
