<?php

require_once(ABSPATH . 'wp-config.php');
global $wpdb;

define("PLUGIN_VOUCHER_URL",plugins_url() . "/" . basename(__DIR__) . "/");
define("APO_VOUCHER_TABLE",$wpdb->prefix . "apovoucher");
define("TABLE_PROPERTIES_VOUCHER","apo_imoveissite");


// function to create the DB / Options / Defaults					
function create_apovoucher_table() {
   	global $wpdb;  	

	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '" . APO_VOUCHER_TABLE . "'") != APO_VOUCHER_TABLE) 
	{
		$sql = "CREATE TABLE `" . APO_VOUCHER_TABLE . "` (
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`property_reference` VARCHAR(30) NOT NULL DEFAULT '0',
					`property_type` VARCHAR(20) NOT NULL DEFAULT '0',
					`property_size` VARCHAR(20) NOT NULL DEFAULT '0',
					`original_price` FLOAT(10,2) NOT NULL DEFAULT '0',
					`promotional_price` FLOAT(10,2) NOT NULL DEFAULT '0',
					`validity` DATE NOT NULL DEFAULT '0000-00-00',
					`name` VARCHAR(50) NOT NULL DEFAULT '0',
					`email` VARCHAR(150) NOT NULL DEFAULT '0',
					`fone` VARCHAR(25) NOT NULL DEFAULT '0',
					`document` VARCHAR(25) NOT NULL DEFAULT '0',
					`newsletter` INT(1) NOT NULL DEFAULT '0',
					`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
					PRIMARY KEY (id)
				)";
 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
 
}


