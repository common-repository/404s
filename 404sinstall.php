<?php
if (!defined('ABSPATH'))
{
	exit;
}
require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
global $table_prefix, $wpdb;

function toams_404s_Install()
{	
	global $table_prefix, $wpdb;
	$charset_collate = '';
	if($wpdb->supports_collation()) 
	{
		if(!empty($wpdb->charset)) 
		{
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		}
		if(!empty($wpdb->collate)) 
		{
			$charset_collate .= " COLLATE $wpdb->collate";
		}
	}

	$table_name = $table_prefix . "404s";
	$wpdb->query("DROP TABLE `".$table_name."`");
	if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") !== $table_name)
	{	
		$createRrRatingSql = "CREATE TABLE `".$table_name."` (".
			"`record_id` INT(11) NOT NULL auto_increment,".
			"`pagenotfoundurl` VARCHAR(255) NOT NULL default '',".
			"`pagenotfoundreferrers` VARCHAR(255) NOT NULL default '',".
			"`pagenotfoundgoto` VARCHAR(255) NOT NULL default '',".
			"`pagenotfoundip` VARCHAR(255) NOT NULL default '',".
			"`pagenotfoundcountry` VARCHAR(255) NOT NULL default '',".
			"`pagenotfounduseragent` VARCHAR(255) NOT NULL default '',".
			"`pagenotfounddate` DATETIME ,".
			"`pagenotfounderrorreason`  VARCHAR(255) NOT NULL  default '',".
			"`pagenotfoundextendtable` VARCHAR(255) NOT NULL  default '',".
			"`pagenotfoundextendid` VARCHAR(255) NOT NULL  default '',".
			"PRIMARY KEY (record_id)) $charset_collate;";
	}
	dbDelta($createRrRatingSql);
}
toams_404s_Install();
?>