<?php
// Run leads install.php directly against configured DB
chdir(__DIR__ . '/..');
if (!defined('SKIP_AUTH')) define('SKIP_AUTH', true);
require_once 'conf.php';
// include install script
echo "Running leads/install.php...\n";
include 'modules' . SEP . 'leads' . SEP . 'install.php';
echo "Done.\n";
