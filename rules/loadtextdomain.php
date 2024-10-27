<?php

if (!defined('ABSPATH'))
{
	exit;
}

/**** localization ****/
add_action('plugins_loaded','func_404s_load_textdomain');

function func_404s_load_textdomain()
{
	load_plugin_textdomain('wordpress-404s', false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}

