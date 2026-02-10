<?php

CREATE TABLE `CONFIG_WORK_ORDER_STATUS` (
  `CONFIG_WORK_ORDER_STATUS_ID` int(11) NOT NULL auto_increment,
  `CONFIG_WORK_ORDER_STATUS` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`CONFIG_WORK_ORDER_STATUS_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 ;
$rs = $db->Execute($q);
		if(!$rs) {
			check_error($db->ErrorMsg());
		}


INSERT INTO `CONFIG_WORK_ORDER_STATUS` (`CONFIG_WORK_ORDER_STATUS_ID`, `CONFIG_WORK_ORDER_STATUS`) VALUES (1, 'Created');
INSERT INTO `CONFIG_WORK_ORDER_STATUS` (`CONFIG_WORK_ORDER_STATUS_ID`, `CONFIG_WORK_ORDER_STATUS`) VALUES (2, 'Assigned');
INSERT INTO `CONFIG_WORK_ORDER_STATUS` (`CONFIG_WORK_ORDER_STATUS_ID`, `CONFIG_WORK_ORDER_STATUS`) VALUES (3, 'Waiting For Parts');
$rs = $db->Execute($q);
		if(!$rs) {
			check_error($db->ErrorMsg());
		}


CREATE TABLE `TABLE_ADMIN` (
  `ADMIN_ID` int(11) NOT NULL auto_increment,
  `ADMIN_LOGIN` varchar(50) NOT NULL default '',
  `ADMIN_PASSW` varchar(50) NOT NULL default '',
  `ADMIN_EMAIL` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`ADMIN_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

$rs = $db->Execute($q);
		if(!$rs) {
			check_error($db->ErrorMsg());
		}


CREATE TABLE `TABLE_COMPNAY` (
  `COMPANY_NAME` varchar(60) NOT NULL default '',
  `COMPANY_ADDRESS` varchar(60) NOT NULL default '',
  `COMPANY_CITY` varchar(60) NOT NULL default '',
  `COMPANY_STATE` varchar(60) NOT NULL default '',
  `COMPANY_ZIP` varchar(20) NOT NULL default '',
  `COMPNAY_PHONE` varchar(20) NOT NULL default '',
  `COMPNAY_MOBILE` varchar(20) NOT NULL default '',
  `COMPANY_TOLL_FREE` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`COMPANY_NAME`)
) ENGINE=MyISAM;



CREATE TABLE `TABLE_CUSTOMER` (
  `CUSTOMER_ID` int(11) NOT NULL auto_increment,
  `CUSTOMER_DISPLAY_NAME` varchar(80) NOT NULL default '',
  `CUSTOMER_ADDRESS` varchar(30) default NULL,
  `CUSTOMER_CITY` varchar(20) default NULL,
  `CUSTOMER_STATE` varchar(20) default NULL,
  `CUSTOMER_ZIP` varchar(8) default NULL,
  `CUSTOMER_PHONE` varchar(13) default NULL,
  `CUSTOMER_WORK_PHONE` varchar(13) NOT NULL default '',
  `CUSTOMER_MOBILE_PHONE` varchar(13) NOT NULL default '',
  `CUSTOMER_EMAIL` varchar(30) default NULL,
  `CUSTOMER_TYPE` varchar(20) default NULL,
  `CUSTOMER_FIRST_NAME` varchar(20) default NULL,
  `CUSTOMER_LAST_NAME` varchar(20) default NULL,
  PRIMARY KEY  (`CUSTOMER_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;



CREATE TABLE `TABLE_EMPLOYEE` (
  `EMPLOYEE_ID` int(11) NOT NULL auto_increment,
  `EMPLOYEE_LOGIN` varchar(50) NOT NULL default '',
  `EMPLOYEE_PASSWD` varchar(50) NOT NULL default '',
  `EMPLOYEE_EMAIL` varchar(50) NOT NULL default '',
  `EMPLOYEE_FIRST_NAME` varchar(40) NOT NULL default '',
  `EMPLOYEE_LAST_NAME` varchar(40) NOT NULL default '',
  `EMPLOYEE_DISPLAY_NAME` varchar(80) NOT NULL default '',
  `EMPLOYEE_SSN` int(9) NOT NULL default '0',
  `EMPLOYEE_ADDRESS` varchar(40) NOT NULL default '',
  `EMPLOYEE_CITY` varchar(40) NOT NULL default '',
  `EMPLOYEE_STATE` char(40) NOT NULL default '',
  `EMPLOYEE_ZIP` int(11) NOT NULL default '0',
  `EMPLOYEE_TYPE` varchar(60) NOT NULL default '',
  `EMPLOYEE_WORK_PHONE` varchar(13) NOT NULL default '',
  `EMPLOYEE_HOME_PHONE` varchar(13) NOT NULL default '',
  `EMPLOYEE_MOBILE_PHONE` varchar(13) NOT NULL default '',
  `EMPLOYEE_ACL` int(11) NOT NULL default '0',
  `EMPLOYEE_STATUS` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`EMPLOYEE_ID`),
  UNIQUE KEY `EMPLOYEE_LOGIN` (`EMPLOYEE_LOGIN`)
) ENGINE=MyISAM AUTO_INCREMENT=12 ;

 


CREATE TABLE `TABLE_INVOICE` (
  `INVOICE_ID` int(11) NOT NULL auto_increment,
  `INVOICE_DATE` varchar(30) default NULL,
  `CUSTOMER_ID` int(11) NOT NULL default '0',
  `WORKORDER_ID` int(11) NOT NULL default '0',
  `EMPLOYEE_ID` int(11) default NULL,
  `INVOICE_PAID` char(3) default 'no',
  `INVOICE_AMOUNT` varchar(8) default '0.00',
  PRIMARY KEY  (`INVOICE_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;



CREATE TABLE `TABLE_INVOICE_LABOR` (
  `INVOICE_LABOR_ID` int(11) NOT NULL auto_increment,
  `INVOICE_ID` int(11) NOT NULL default '0',
  `EMPLOYEE_ID` int(11) NOT NULL default '0',
  `INVOICE_LABOR_DESCRIPTION` text,
  `INVOICE_LABOR_RATE` varchar(10) NOT NULL default '',
  `INVOICE_LABOR_UNIT` varchar(4) NOT NULL default '',
  `INVOICE_LABOR_SUBTOTAL` varchar(7) NOT NULL default '',
  PRIMARY KEY  (`INVOICE_LABOR_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;



CREATE TABLE `TABLE_INVOICE_PARTS` (
  `INVOICE_PARTS_ID` int(11) NOT NULL auto_increment,
  `INVOICE_ID` int(11) NOT NULL default '0',
  `EMPLOYEE_ID` int(11) NOT NULL default '0',
  `INVOICE_PARTS_MANUF` varchar(60) NOT NULL default '',
  `INVOCIE_PARTS_MFID` varchar(30) NOT NULL default '',
  `INVOICE_PARTS_DESCRIPTION` varchar(60) NOT NULL default '',
  `INVOICE_PARTS_WARANTY` varchar(30) NOT NULL default '',
  `INVOICE_PARTS_AMOUNT` int(4) NOT NULL default '0',
  `INVOICE_PARTS_SUBTOTA` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`INVOICE_PARTS_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;



CREATE TABLE `TABLE_PAYMENT` (
  `PAYMENT_ID` int(11) NOT NULL auto_increment,
  `INVOICE_ID` int(11) NOT NULL default '0',
  `PAYMENT_MONTH` varchar(25) NOT NULL default '',
  `PAYMENT_DAY` int(2) NOT NULL default '0',
  `PAYMENT_YEAR` int(4) NOT NULL default '0',
  `PAYMENT_TYPE` varchar(20) NOT NULL default '',
  `PAYMENT_AMOUNT` varchar(10) NOT NULL default '',
  `PAYMENT_NUMBER` varchar(10) NOT NULL default '',
  `EMPLOYEE_ID` int(11) NOT NULL default '0',
  `CUSTOMER_ID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`PAYMENT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;


CREATE TABLE `TABLE_PERMISSIONS` (
  `PERMISION_ID` int(11) NOT NULL auto_increment,
  `PERMISIONS_RESID` char(80) NOT NULL default '',
  `PERMISIONS_ACL` int(11) NOT NULL default '0',
  PRIMARY KEY  (`PERMISION_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;



CREATE TABLE `TABLE_SCHEDUAL` (
  `SCHEDUAL_ID` int(11) NOT NULL auto_increment,
  `SCHEDUAL_START` bigint(20) NOT NULL default '0',
  `SCHEDUAL_END` bigint(20) NOT NULL default '0',
  `WORK_ORDER_ID` int(11) NOT NULL default '0',
  `EMPLOYEE_ID` varchar(32) NOT NULL default '',
  `SCHEDUAL_NOTES` text NOT NULL,
  PRIMARY KEY  (`SCHEDUAL_ID`),
  KEY `WORK_ORDER_ID` (`WORK_ORDER_ID`,`EMPLOYEE_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;


CREATE TABLE `TABLE_WORK_ORDER` (
  `WORK_ORDER_ID` int(4) NOT NULL auto_increment,
  `CUSTOMER_ID` int(4) NOT NULL default '0',
  `WORK_ORDER_OPEN_DATE` varchar(30) NOT NULL default '',
  `WORK_ORDER_STATUS` varchar(20) NOT NULL default '',
  `WORK_ORDER_CURENT_STATUS` varchar(20) NOT NULL default '',
  `WORK_ORDER_CREATE_BY` int(11) NOT NULL default '0',
  `WORK_ORDER_ASSIGN_TO` int(11) NOT NULL default '0',
  `WORK_ORDER_SCOPE` varchar(200) NOT NULL default '',
  `WORK_ORDER_DESCRIPTION` text NOT NULL,
  `WORK_ORDER_COMMENT` text,
  `WORK_ORDER_SCHEDUALE` int(11) NOT NULL default '0',
  `WORK_ORDER_SCHEDUALE_NOTES` text,
  `WORK_ORDER_CLOSE_DATE` varchar(30) default NULL,
  `WORK_ORDER_RESOLUTION` text,
  `WORK_ORDER_CLOSE_BY` varchar(30) default NULL,
  PRIMARY KEY  (`WORK_ORDER_ID`),
  KEY `WORK_ORDER_STATUS` (`WORK_ORDER_STATUS`),
  KEY `WORK_ORDER_CURENT_STATUS` (`WORK_ORDER_CURENT_STATUS`),
  KEY `WORK_ORDER_ASSIGN_TO` (`WORK_ORDER_ASSIGN_TO`),
  KEY `WORK_ORDER_CREATE_BY` (`WORK_ORDER_CREATE_BY`),
  KEY `WORK_ORDER_CLOSE_BY` (`WORK_ORDER_CLOSE_BY`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;



CREATE TABLE `TABLE_WORK_ORDER_NOTES` (
  `WORK_ORDER_NOTES_ID` int(11) NOT NULL auto_increment,
  `WORK_ORDER_ID` int(11) NOT NULL default '0',
  `WORK_ORDER_NOTES_DESCRIPTION` text NOT NULL,
  `WORK_ORDER_NOTES_ENTER_BY` varchar(128) NOT NULL default '',
  `WORK_ORDER_NOTES_DATE` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`WORK_ORDER_NOTES_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;



CREATE TABLE `TABLE_WORK_ORDER_STATUS` (
  `WORK_ORDER_STATUS_ID` int(11) NOT NULL auto_increment,
  `WORK_ORDER_ID` int(11) NOT NULL default '0',
  `WORK_ORDER_STATUS_DATE` varchar(30) NOT NULL default '',
  `WORK_ORDER_STATUS_NOTES` text NOT NULL,
  `WORK_ORDER_STATUS_ENTER_BY` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`WORK_ORDER_STATUS_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;
?>

