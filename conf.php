<?php
#############################################################
# Cite CRM	Customer Relations Management							#
# Copyright (C) 2003 - 2005 In-Cite CRM								#
# www.citecrm.com  dev@incitecrm.com									#
# This program is distributed under the terms and 				#
# conditions of the GPL	and is free to use or modify			#
# 																				#
# Installer																	#
# Version 0.0.1	Fri Oct 21 05:49:41 PDT 2005						#
#############################################################

include('version.php');
@define('SEP',                '/');
@define('FILE_ROOT',            '/home/incitecrm/domains/demo.incitecrm.com/public_html/' . SEP);
@define('WWW_ROOT',                'http://demo.incitecrm.com/');
@define('IMG_URL',               WWW_ROOT . 'images');
@define('INCLUDE_URL',           FILE_ROOT . 'include' . SEP);
@define('SQL_URL',              FILE_ROOT . 'sql');
@define('CALENDAR_PATH',        FILE_ROOT . 'DateTime');
@define('SMARTY_URL',             INCLUDE_URL . 'SMARTY' . SEP);
@define('ACCESS_LOG',            FILE_ROOT . 'log' . SEP . 'access.log');
@define('INSTALL_DATE',        'Dec 23 2005 10:50:45 PM');
@define('debug', 'no');

/* Database Settings */
@define('PRFX',            'CRM_');
@define('DB_HOST',     'localhost');
@define('DB_USER',         'demo_incitecrm');
@define('DB_PASS',     'tdqX02gYul4LInPC95YfjD8bN');
@define('DB_NAME',     'citecrm');

/* IN Cite CRM locations */
@define('INCITCRM', "http://dev.incitecrm.com/index.php");

/* Load required Includes */
// Allow public endpoints to skip session/auth by defining SKIP_AUTH before including conf.php
if (!defined('SKIP_AUTH')) {
    require(INCLUDE_URL . SEP . 'session.php');
    require(INCLUDE_URL . SEP . 'auth.php');
}

/* Set Path for SMARTY in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL . 'SMARTY' . SEP);
require('Smarty.class.php');

/* Set Path for ADODB in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL . 'ADODB' . SEP);
require('adodb.inc.php');

/* Load smarty template engine */
global $smarty;
$smarty = new Smarty;
$smarty->template_dir     = FILE_ROOT . 'templates';
$smarty->compile_dir         = FILE_ROOT . 'cache';
$smarty->config_dir         = SMARTY_URL . 'configs';
$smarty->cache_dir         = SMARTY_URL . 'cache';
$smarty->load_filter('output', 'trimwhitespace');

$strKey = 'kcmp7n2permbtr0dqebme6mpejhn3ki';

/* create adodb database connection */
$db = &ADONewConnection('mysqli');
$db->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
