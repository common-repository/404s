<?php
if (!defined('ABSPATH'))
{
	exit;
}

function wordpress_404s_pro_get_glossary_pagination($results_page_pagination)
{
	$per_page = 20;
	//3.5.1
	$glossary_current_page_id = isset( $_REQUEST['glossarypageid'] ) ? sanitize_text_field(intval( $_REQUEST['glossarypageid'] )) : 1;
	//$glossary_current_page_id = isset( $_REQUEST['glossarypageid'] ) ? intval( $_REQUEST['glossarypageid'] ) : 1;
	$glossary_num_of_per_page = isset ( $_REQUEST ['num'] ) ? sanitize_text_field(intval ( $_REQUEST ['num'] )) : $per_page;
	//$glossary_num_of_per_page = isset ( $_REQUEST ['num'] ) ? intval ( $_REQUEST ['num'] ) : $per_page;
	$glossary_page_start_from = ($glossary_current_page_id -1) * $glossary_num_of_per_page;
	$glossary_page_end_number = $glossary_page_start_from + $glossary_num_of_per_page;
	$show_glossary_page_current_result = array_slice($results_page_pagination, $glossary_page_start_from, $glossary_num_of_per_page);
	return $show_glossary_page_current_result;
}

function wordpress_404s_pro_show_pagination($results_page_pagination, $per_page = 20)
{
	global $activities_template;

	$glossaryfirstpage = 1;
	$glossary_page_id_arg = 'glossarypageid';
	//3.5.1
	$glossary_current_page_id = isset( $_REQUEST[$glossary_page_id_arg] ) ? sanitize_text_field(intval( $_REQUEST[$glossary_page_id_arg] )) : $glossaryfirstpage;
	//$glossary_current_page_id = isset( $_REQUEST[$glossary_page_id_arg] ) ? intval( $_REQUEST[$glossary_page_id_arg] ) : $glossaryfirstpage;
	$glossary_num_of_per_page  = isset( $_REQUEST['num'] ) ? sanitize_text_field(intval( $_REQUEST['num'] )) : $per_page;
	//$glossary_num_of_per_page  = isset( $_REQUEST['num'] ) ? intval( $_REQUEST['num'] ) : $per_page;

	$sitedomain =wordpress_404s_pro_get_current_page_url();

	//3.5.9 
	$results_page_pagination_total = 0;
	if ($results_page_pagination == 0)
	{
		$results_page_pagination_total = 0;
	}
	else
	{
		$results_page_pagination_total = $results_page_pagination;
	}
	$glossary_paginate_links = paginate_links( array(
		'base'      => add_query_arg( $glossary_page_id_arg, '%#%',$sitedomain ),
		'format'    => '',
		'total'     => ceil( (int) $results_page_pagination_total / (int) $glossary_num_of_per_page ),
		'current'   => (int) $glossary_current_page_id,
		'prev_text' => _x( '&larr;', 'Glossary pagination previous text', 'wordpress-404s' ),
		'next_text' => _x( '&rarr;', 'Glossary pagination next text', 'wordpress-404s' ),
		'mid_size'  => 1
) );

	/*
	//!!! before 3.5.9
	$glossary_paginate_links = paginate_links( array(
			'base'      => add_query_arg( $glossary_page_id_arg, '%#%',$sitedomain ),
			'format'    => '',
			'total'     => ceil( (int) count($results_page_pagination) / (int) $glossary_num_of_per_page ),
			'current'   => (int) $glossary_current_page_id,
			'prev_text' => _x( '&larr;', 'Glossary pagination previous text', 'wordpress-404s' ),
			'next_text' => _x( '&rarr;', 'Glossary pagination next text', 'wordpress-404s' ),
			'mid_size'  => 1
	) );
	*/
	$glossary_paginate_link_wrap_div_start = '<div class="glossary_paginate_links">';
	$glossary_paginate_link_wrap_div_end = '</div>';

	$glossary_paginate_links_with_style = $glossary_paginate_link_wrap_div_start.$glossary_paginate_links.$glossary_paginate_link_wrap_div_end;
	return $glossary_paginate_links_with_style;


}

function wordpress_404s_pro_get_current_page_url()
{
    //3.5.1
    $current_site = sanitize_text_field($_SERVER['HTTP_HOST']);
	//$current_site = $_SERVER['HTTP_HOST'];
    $current_uri = sanitize_text_field($_SERVER['REQUEST_URI']);
	//$current_uri = $_SERVER['REQUEST_URI'];
	$current_request_server_port = '';
	if (isset($_SERVER['REQUEST_SERVER_PORT']))
	{
	    $current_request_server_port = sanitize_text_field($_SERVER['REQUEST_SERVER_PORT']);
		// $current_request_server_port = $_SERVER['REQUEST_SERVER_PORT'];
	}
	
	
	$current_protocol =  ((!empty($_SERVER['HTTPS'])) &&  ($_SERVER['HTTPS'] !== 'off') ) || ($current_request_server_port == 443) ? 'https://' : 'http://';
	$current_link = $current_protocol.$current_site.$current_uri;
	return $current_link;
}

