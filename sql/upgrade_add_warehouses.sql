-- Adds warehouse catalog support and connects products to warehouses.

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
