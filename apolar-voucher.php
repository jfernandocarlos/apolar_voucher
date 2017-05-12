<?php

/*
Plugin Name: Apolar Voucher
Description: Sistema de emissão de Voucher Aporlae.
Version: 0.1
License: GPL
Author: José Fernando Carlos
Author URI: http://apolar.com.br
*/
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
require_once(dirname(__FILE__) . DS . "settings.php");
require_once(dirname(__FILE__) . DS . "voucher.php");
require_once(dirname(__FILE__) . DS . "admin.php");


register_activation_hook( __FILE__, 'create_apovoucher_table' );

