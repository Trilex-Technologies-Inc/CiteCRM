<?php
require 'conf.php';

$smarty->assign('page_title', 'Email Connectors');

$providers = array(
    array('id' => 'gmail', 'label' => 'Gmail (Google)') ,
    array('id' => 'outlook', 'label' => 'Outlook / Microsoft 365')
);

$smarty->assign('providers', $providers);
$smarty->display('messaging' . SEP . 'connectors.tpl');

?>
