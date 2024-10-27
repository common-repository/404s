<?php
/*
 Plugin Name: 404s
 Plugin URI:   https://tooltips.org/
 Description: Log referrers URL, IP, browser... on 404 pages, for find the reason of Page Not Found, opt to redirect 404 error page to home page  
 Version: 3.5.9
 Author: Tomas
 Author URI: https://tooltips.org/
 Text Domain: wordpress-404s
 License: GPLv3 or later
 */
/*  Copyright 2016-2024 Tomas
 This program comes with ABSOLUTELY NO WARRANTY;
 https://www.gnu.org/licenses/gpl-3.0.html
 https://www.gnu.org/licenses/quick-guide-gplv3.html
 */
if (!defined('ABSPATH'))
{
	exit;
}

$m_hadInstall = get_option('tomas_404sinstalled');
if (empty($m_hadInstall))
{
	require_once("404sinstall.php");
	update_option('tomas_404sinstalled','3.5.9');
}

require_once ('404paginate.php'); 
require_once("404toemail.php");
require_once("rules/register404post.php");
require_once("rules/404sassignredirect.php");
require_once("rules/404export.php");
require_once("rules/loadtextdomain.php");

function tomas_process_404s_log()
{
	global $wpdb,$table_prefix,$post;

	$post_id = 0;
	if (is_object($post))
	{
		$post_id = $post->ID;
	}
	
	if( !is_404() )
	{
		return;
	}
	

	$avoid_log_bots = tomas_check_bots();
	if ($avoid_log_bots)
	{
		return;
	}

	//3.5.1
	$pagenotfoundurl = sanitize_text_field($_SERVER['REQUEST_URI']);
	//$pagenotfoundurl = $_SERVER['REQUEST_URI'];
	if (isset($_SERVER['HTTP_REFERER']))
	{
	    
	    //3.5.1
	    $pagenotfoundreferrers = sanitize_text_field($_SERVER['HTTP_REFERER']);
		//$pagenotfoundreferrers = $_SERVER['HTTP_REFERER'];
	}
	else
	{
		$pagenotfoundreferrers = '';
	}
	//3.5.1
	
	$pagenotfoundip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
	$pagenotfounduseragent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
	
	// $pagenotfoundip = $_SERVER['REMOTE_ADDR'];
	// $pagenotfounduseragent = $_SERVER['HTTP_USER_AGENT'];
	$pagenotfounddate = current_time('mysql');
	
	$table_name = $table_prefix . "404s";
	$selectEnable404Log = get_option('selectEnable404Log');

	//3.4.9
	if ($selectEnable404Log <> 'yes') 
	{
		$m_mysql = $wpdb->prepare("INSERT INTO $table_name (pagenotfoundurl,pagenotfoundreferrers,pagenotfoundip, pagenotfounduseragent, pagenotfounddate)
				VALUES (%s, %s, %s, %s, %s)",
				$wpdb->escape($pagenotfoundurl),$wpdb->escape($pagenotfoundreferrers),
				$wpdb->escape($pagenotfoundip),$wpdb->escape($pagenotfounduseragent),$wpdb->escape($pagenotfounddate)
		);
		$wpdb->query($m_mysql);
	}
		

		$select404toEmail = get_option('select404toEmail');
		if ('YES' == $select404toEmail)
		{
			@tomas_404_to_email($post_id,$pagenotfoundurl);
		}

		
	$var_redirectstatuscode = redirectstatuscode();

    //3.5.1
	$pagenotfound404requesturl = sanitize_text_field(trim($_SERVER['REQUEST_URI']));
	// $pagenotfound404requesturl = trim($_SERVER['REQUEST_URI']);
	$posttoredirectid = '';
	
//start 3.4.1
	if ((false === stripos($pagenotfound404requesturl, '.jpg')) && (false === stripos($pagenotfound404requesturl, '.jpeg')) && (false === stripos($pagenotfound404requesturl, '.png'))  && (false === stripos($pagenotfound404requesturl, '.gif')))
	{
	    
	}
	else
	{
	    $redirect404imagetospecificimage = get_option('redirect404imagetospecificimage');
	    $inputredirect404imagetospecificimage = get_option('inputredirect404imagetospecificimage');
	    
	    if ('YES' == $redirect404imagetospecificimage)
	    {
	        if (!(empty($inputredirect404imagetospecificimage)))
	        {
	            wp_safe_redirect($inputredirect404imagetospecificimage,$var_redirectstatuscode);
	            die();
	        }
	    }
	    else
	    {

	    }
	}
	    

// end 3.4.1
	if (!(empty($pagenotfound404requesturl)))
	{
		$results404redirectpostid = $wpdb->get_var(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE `meta_key` = '404surlredirect' AND `meta_value` = '$pagenotfound404requesturl'"
		);
	
		if (!(empty($results404redirectpostid)))
		{
			$posttoredirect = get_post($results404redirectpostid);
			if ((isset($posttoredirect->ID)) && (! (empty($posttoredirect->ID) )))
			{
				$post_redirect_404s_page_url = get_permalink($posttoredirect->ID);
				if (!(empty($post_redirect_404s_page_url)))
				{
					wp_safe_redirect($post_redirect_404s_page_url,$var_redirectstatuscode);
					die();
				}
			}
		}
	}
	

	$redirect404tospecificpage = get_option('redirect404tospecificpage');
	if ('YES' == $redirect404tospecificpage)
	{
		$inputredirect404tospecificpage = get_option('inputredirect404tospecificpage');
		if (!(empty($inputredirect404tospecificpage)))
		{
			wp_safe_redirect($inputredirect404tospecificpage,$var_redirectstatuscode);
			die();			
		}
	}
	

	$select404Redirect = get_option('select404Redirect');
	if ($select404Redirect == 'homepage')
	{
		wp_safe_redirect(home_url(),$var_redirectstatuscode);
		die();		
	}

	if ($select404Redirect == 'customurl')
	{
		$inputText404RedirectCustomURL = get_option('inputText404RedirectCustomURL');
		if (!(empty($inputText404RedirectCustomURL)))
		{
			$var_redirectstatuscode = redirectstatuscode();
			wp_safe_redirect($inputText404RedirectCustomURL,$var_redirectstatuscode);
			die();			
		}
		else 
		{
			$var_redirectstatuscode = redirectstatuscode();
			wp_safe_redirect(home_url(),$var_redirectstatuscode);
			die();			
		}
	}
}

function tomas_check_bots()
{
	$bots = array();
	$bots[] = 'bot';
	$bots[] = 'spider';
	$bots[] = 'crawler';

	//3.5.1
	$user_bots_or_not_agent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
	// $user_bots_or_not_agent = $_SERVER['HTTP_USER_AGENT'];

	$user_bots_or_not_result = false;
	
	foreach ( $bots as $bot_single )
	{
			$bot_single = trim($bot_single);
			if ( !(empty($bot_single)))
			{
				$bot_single = strtolower($bot_single);
				$user_bots_or_not_agent = strtolower($user_bots_or_not_agent);
				
				if (strpos( $user_bots_or_not_agent, $bot_single ) === false)
				{
					$user_bots_or_not_result = false;
				}
				else 
				{
					$user_bots_or_not_result = true;
				}
			}
			else
			{
				$user_bots_or_not_result = false;
			}
	}
	return $user_bots_or_not_result;	
}

add_action( 'template_redirect', 'tomas_process_404s_log' );


function tomas_manage_404s()
{
	global $wpdb,$table_prefix;
	$table_name = $table_prefix . "404s";
	
	$m_checkHadThere = "SELECT * FROM $table_name";
	$m_checkResult = $wpdb->get_results($m_checkHadThere,ARRAY_A);
?>

<div style='margin:10px 5px;'>
<div style='padding-top:5px; font-size:22px;'>404 Page Not Found Log:</div>
</div>
<div style='clear:both'></div>
<?php 
	
?>
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">
								<div class="postbox">
									<div class="inside" style='padding-left:10px;'>
									<table id="bpmotable" width="100%" style="table-layout: fixed;">
									<tr>
										<th scope="row"  width="25%" style="padding: 20px; text-align:left;">
										<?php 
											echo  __( 'Pages Not Found', '404s' );
										?>
										</th>
									
										<th scope="row"  width="25%" style="padding: 20px; text-align:left;">
										<?php 
											echo  __( 'Referrers', '404s' );
										?>
										</th>

										<th scope="row"  width="15%" style="padding: 20px; text-align:left;">
										<?php 
											echo  __( 'IP', '404s' );
										?>
										</th>

										<th scope="row"  width="20%" style="padding: 20px; text-align:left;">
										<?php 
											echo  __( 'User Agent', '404s' );
										?>
										</th>

										<th scope="row"  width="15%" style="padding: 20px; text-align:left;">
										<?php 
											echo  __( 'Date', '404s' );
										?>
										</th>
										
									</tr>										

<?php 
		// 3.5.9 
		$results_page_pagination = '';
		if (empty($m_checkResult))
		{
			//return;
		}
		else 
		{
			$results_page_pagination = $m_checkResult;
			$show_404_page_current_result = wordpress_404s_pro_get_glossary_pagination($results_page_pagination);
			
			foreach ($show_404_page_current_result as $m_checkResult_single)
			{
?>								
									
										
										
										<tr valign="top">
										<td scope="row" style="padding: 20px; text-align:left; word-wrap: break-word;">
										<?php 
										//3.5.1
										echo  esc_attr($m_checkResult_single['pagenotfoundurl']);
											// echo  $m_checkResult_single['pagenotfoundurl'];
										?>
										</td>
										
										<td style="padding: 20px; text-align:left;  word-wrap: break-word;">
										<?php
										//3.5.1
										echo  esc_attr($m_checkResult_single['pagenotfoundreferrers']);
										//	echo  $m_checkResult_single['pagenotfoundreferrers'];
										?>
										</td>
										
										<td style="padding: 20px; text-align:left;  word-wrap: break-word;">
										<?php
										//3.5.1
										echo  esc_attr($m_checkResult_single['pagenotfoundip']);
											//echo  $m_checkResult_single['pagenotfoundip'];
										?>
										</td>

										<td style="padding: 20px; text-align:left;  word-wrap: break-word;">
										<?php
										//3.5.1
										echo  esc_attr($m_checkResult_single['pagenotfounduseragent']);
											//echo  $m_checkResult_single['pagenotfounduseragent'];
										?>
										</td>

										<td style="padding: 20px; text-align:left;  word-wrap: break-word;">
										<?php
										//3.5.1
										echo  esc_attr($m_checkResult_single['pagenotfounddate']);
											//echo  $m_checkResult_single['pagenotfounddate'];
										?>
										</td>
										</tr>
		<?php 
			}
		}
		?>
										</table>
										<br />
										
										<br />
									</div>
								</div>
							</div>
						</div>
					</div>
		    	</div>
			</div> <!--   dashboard-widgets-wrap -->
			<?php 
	//!!! 2.4.3 start
	$per_page = 20;
	$return_content = wordpress_404s_pro_show_pagination($results_page_pagination,$per_page);
	echo $return_content;
	?>
		</div> <!--  wrap -->

		<div style="clear:both"></div>
		<br />
<?php 
}

function tomas_404s_menu()
{
	add_submenu_page('edit.php?post_type=404s', __('404s','404s'), __('404s','404s'), "manage_options", '404s', 'tomas_manage_404s');
	add_submenu_page('edit.php?post_type=404s', __('404s','404s'), __('Settings','404s'), "manage_options", '404settings', 'tomas_404s_setitngs');
	add_submenu_page("edit.php?post_type=404s", __('404s','404s'), __("Export 404s", "404s"), "manage_options", "panel404sExport","panel404sExport");
}

add_action( 'admin_menu', 'tomas_404s_menu');

function tomas_setting_panel_404s($title = '', $content = '')
{
	?>
<div class="wrap">
	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="post-body">
				<div id="dashboard-widgets-main-content">
					<div class="postbox-container" style="width: 90%;">
						<div class="postbox">					
							<h3 class='hndle' style='padding: 10px 0px; border-bottom: 0px solid #eee !important;'>
							<span>
								<?php
								   echo $title; 	
								?>
							</span>
							</h3>
						
							<div class="inside postbox" style='padding-top:10px; padding-left: 10px; ' >
								<?php
								    echo $content;
								?>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="clear: both"></div>
<?php	
}

function setting_panel_404s_head($title)
{
	?>
		<div style='padding-top:20px; font-size:22px;'><?php echo $title; ?></div>
		<div style='clear:both'></div>
<?php 
}

function tomas_404s_setitngs()
{
	global $wpdb,$table_prefix;

	if (isset($_POST['clear404LogSubmit']))
	{
	
		//$selectClear404Log = $_POST['selectClear404Log'];
	    $selectClear404Log = sanitize_text_field($_POST['selectClear404Log']); 	//3.4.5
	    update_option('selectClear404Log',$selectClear404Log); 	//3.4.5
	    
		if (strtolower($selectClear404Log) == 'yes')
		{
			$table_name = $table_prefix . "404s";
			$clearLogsQuery = "DELETE FROM $table_name";
			$wpdb->query($clearLogsQuery);
			messageBarFor404s('404 logs has been removed');
		}
	}
	
	$selectClear404Log = get_option('selectClear404Log');
	
	
	if (isset($_POST['enable404LogSubmit']))
	{
		//$selectEnable404Log = $_POST['selectEnable404Log'];
	    $selectEnable404Log = sanitize_text_field($_POST['selectEnable404Log']); 	//3.4.5
		update_option('selectEnable404Log',$selectEnable404Log);
	}
	$selectEnable404Log = get_option('selectEnable404Log');
	
	if (isset($_POST['404RedirectSubmit']))
	{
		//$select404Redirect = $_POST['select404Redirect'];
	    $select404Redirect = sanitize_text_field($_POST['select404Redirect']); 	//3.4.5
		update_option('select404Redirect',$select404Redirect);
	}
	$select404Redirect = get_option('select404Redirect');
	
	
	if (isset($_POST['inputText404RedirectCustomURL']))
	{
		//$inputText404RedirectCustomURL = $_POST['inputText404RedirectCustomURL'];
	    $inputText404RedirectCustomURL = sanitize_text_field($_POST['inputText404RedirectCustomURL']); 	//3.4.5
		update_option('inputText404RedirectCustomURL',$inputText404RedirectCustomURL);
	}
	$inputText404RedirectCustomURL = get_option('inputText404RedirectCustomURL');
	
	if (isset($_POST['use404to301Submit']))
	{
		//$selectuse404to301 = $_POST['selectuse404to301'];
	    $selectuse404to301 = sanitize_text_field($_POST['selectuse404to301']); 	//3.4.5
		update_option('selectuse404to301',$selectuse404to301);
	}
	$selectuse404to301 = get_option('selectuse404to301');	

	if (isset($_POST['404toEmailSubmit']))
	{
		//$select404toEmail = $_POST['select404toEmail'];
	    $select404toEmail = sanitize_text_field($_POST['select404toEmail']); 	//3.4.5
		update_option('select404toEmail',$select404toEmail);
	}
	$select404toEmail = get_option('select404toEmail');
	
	
	if (isset($_POST['inputText404toEmail']))
	{
		//$inputText404toEmail = $_POST['inputText404toEmail'];
	    $inputText404toEmail = sanitize_text_field($_POST['inputText404toEmail']); 	//3.4.5
		update_option('inputText404toEmail',$inputText404toEmail);
	}
	$inputText404toEmail = get_option('inputText404toEmail');	




	if (isset($_POST['redirect404tospecificpageSubmit']))
	{
		//$redirect404tospecificpage = $_POST['redirect404tospecificpage'];
	    $redirect404tospecificpage = sanitize_text_field($_POST['redirect404tospecificpage']); 	//3.4.5
		update_option('redirect404tospecificpage',$redirect404tospecificpage); 
	}
	$redirect404tospecificpage = get_option('redirect404tospecificpage');
	
	
	if (isset($_POST['inputredirect404tospecificpage']))
	{
		//$inputredirect404tospecificpage = $_POST['inputredirect404tospecificpage'];
	    $inputredirect404tospecificpage = sanitize_text_field($_POST['inputredirect404tospecificpage']); 	//3.4.5
		update_option('inputredirect404tospecificpage',$inputredirect404tospecificpage);
	}
	$inputredirect404tospecificpage = get_option('inputredirect404tospecificpage');

	//start 3.4.1
	if (isset($_POST['redirect404tospecificimageSubmit']))
	{
	    //$redirect404imagetospecificimage = $_POST['redirect404imagetospecificimage'];
	    $redirect404imagetospecificimage = sanitize_text_field($_POST['redirect404imagetospecificimage']); 	//3.4.5
	    update_option('redirect404imagetospecificimage',$redirect404imagetospecificimage);
	}
	$redirect404imagetospecificimage = get_option('redirect404imagetospecificimage');
	
	
	if (isset($_POST['inputredirect404imagetospecificimage']))
	{
	    //$inputredirect404imagetospecificimage = $_POST['inputredirect404imagetospecificimage'];
	    $inputredirect404imagetospecificimage = sanitize_text_field($_POST['inputredirect404imagetospecificimage']); 	//3.4.5
	    update_option('inputredirect404imagetospecificimage',$inputredirect404imagetospecificimage);
	}
	$inputredirect404imagetospecificimage = get_option('inputredirect404imagetospecificimage');
	
	if ('YES' == $redirect404imagetospecificimage)
	{
	    if (!(empty($inputredirect404imagetospecificimage)))
	    {

	    }
	}
	else
	{

	}
	
	//end 3.4.1
	$title = '404s Global Settings';
	setting_panel_404s_head($title);

	$title = 'CLear 404 Log Records from Database?';
	$content = '';
	
	$content .= '<form class="form404s" name="form404s" action="" method="POST">';
	$content .= '<table id="table404s" width="100%">';
	
	$content .= '<tr style="text-align:left;">';
	$content .= '<td width="25%"  style="text-align:left;">';
	
	$content .= 'Clear 404 logs now ? ';
	$content .= '</td>';
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<select id="selectClear404Log" name="selectClear404Log" style="width:300px;">';
	
	//3.4.5
	$selectClear404Log = get_option('selectClear404Log');
	if ($selectClear404Log == 'yes')
	{
	    //selected
	    $content .= '<option id="OptionClear404Log" value="yes" selected > YES </option>';
	}
	else 
	{
	    $content .= '<option id="OptionClear404Log" value="yes"  > YES </option>';
	}
	
	if ($selectClear404Log == 'no')
	{
	    $content .= '<option id="OptionClear404Log" value="no" selected > NO </option>';
	}
	else 
	{
	    $content .= '<option id="OptionClear404Log" value="no"  > NO </option>';
	}
	//end 3.4.5
	
	//$content .= '<option id="OptionClear404Log" value="yes"  > YES </option>';	
	//$content .= '<option id="OptionClear404Log" value="no"  > NO </option>';
	$content .= '</select>';
	$content .= '</td>';
											
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<input type="submit" class="button-primary" id="clear404LogSubmit" name="clear404LogSubmit" value=" Clear Log ">';
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</table>';
	$content .= '</form>';
	
	tomas_setting_panel_404s($title, $content);
	
	$title = 'Enable / Disable Trace New 404 logs';
	$content = '';
	
	$content .= '<form class="form404s" name="form404s" action="" method="POST">';
	$content .= '<table id="table404s" width="100%">';
	
	$content .= '<tr style="text-align:left;">';
	$content .= '<td width="25%"  style="text-align:left;">';
	
	
	
	$content .= 'Stop Insert New 404 Log Records into Database? ';
	$content .= '</td>';
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<select id="selectEnable404Log" name="selectEnable404Log" style="width:300px;">';

	$selectEnable404Log = get_option('selectEnable404Log');
	if ($selectEnable404Log == 'yes')
	{
		$content .= '<option id="OptionEnable404Log" value="yes" selected > YES </option>';
	}
	else
	{
		$content .= '<option id="OptionEnable404Log" value="yes"  > YES </option>';		
	}

	if ($selectEnable404Log == 'no')
	{
		$content .= '<option id="OptionEnable404Log" value="no" selected > NO </option>';
	}
	else 
	{
		$content .= '<option id="OptionEnable404Log" value="no"  > NO </option>';		
	}
	$content .= '</select>';
	$content .= '</td>';
		
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<input type="submit" class="button-primary" id="enable404LogSubmit" name="enable404LogSubmit" value=" Submit ">';
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</table>';
	$content .= '</form>';
	
	tomas_setting_panel_404s($title, $content);	
	
 
	$title = 'Redirect 404 to HomePage?';
	$content = '';
	
	$content .= '<form class="form404s" name="form404s" action="" method="POST">';
	$content .= '<table id="table404s" width="100%">';
	
	$content .= '<tr style="text-align:left;">';
	$content .= '<td width="25%"  style="text-align:left;">';
	
	$content .= 'Redirect 404 to HomePage ?  ';
	$content .= '</td>';
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<select id="select404Redirect" name="select404Redirect" style="width:300px;">';
	
	if ($select404Redirect == 'no')
	{
		$content .= '<option id="Option404Redirect" selected value="no"  > NO, Use Wordpress 404.php Template </option>';
	}
	else
	{
		$content .= '<option id="Option404Redirect" value="no"  > NO, Use Wordpress 404.php Template </option>';		
	}
	
	if ($select404Redirect == 'homepage')
	{
		$content .= '<option id="Option404Redirect" selected value="homepage"  > Home Page </option>';
	}
	else
	{
		$content .= '<option id="Option404Redirect" value="homepage"  > Home Page </option>';
	}
	
	if ($select404Redirect == 'customurl')
	{
		$content .= '<option id="Option404Redirect" selected value="customurl"  > Custom URL </option>';
	}
	else
	{
		$content .= '<option id="Option404Redirect" value="customurl"  > Custom URL </option>';
	}
	
	$content .= '</select>';
	$content .= '<p>';
	//3.5.1
	$content .= '<input type="text" style="width:300px;" id="inputText404RedirectCustomURL" name="inputText404RedirectCustomURL" value="'. esc_attr($inputText404RedirectCustomURL) .'" placeholder="'.__('for example:https://yourdomain.com/landingpage', "wordpress-404s").'">';
	//$content .= '<input type="text" style="width:300px;" id="inputText404RedirectCustomURL" name="inputText404RedirectCustomURL" value="'. $inputText404RedirectCustomURL .'" placeholder="'.__('for example:https://yourdomain.com/landingpage', "wordpress-404s").'">';
	$content .= '</p>';
	
	
	$content .= '</td>';
		
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<input type="submit" class="button-primary" id="404RedirectSubmit" name="404RedirectSubmit" value=" Submit ">';
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</table>';
	$content .= '</form>';
	
	tomas_setting_panel_404s($title, $content);
	
	$title = 'Use 301 status code or 302  status code to redirect all 404 pages?';
	$content = '';
	
	$content .= '<form class="form404s" name="form404s" action="" method="POST">';
	$content .= '<table id="table404s" width="100%">';
	
	$content .= '<tr style="text-align:left;">';
	$content .= '<td width="25%"  style="text-align:left;">';
	
	$content .= 'Redirect 404 with 301 moved permanently status code ?  ';
	$content .= '</td>';
	$content .= '<td width="30%"  style="text-align:left;">';

	$content .= '<select id="selectuse404to301" name="selectuse404to301" style="width:300px;">';
	
	$selectuse404to301 = get_option('selectuse404to301');
	if ($selectuse404to301 == '301')
	{
		$content .= '<option id="Optionuse404to301" selected value="301"  > 301 </option>';
	}
	else
	{
			$content .= '<option id="Optionuse404to301" value="301"  > 301 </option>';
	}
	
	if ($selectuse404to301 == '302')
	{
		$content .= '<option id="Optionuse404to301" selected value="302"  > 302 </option>';
	}
	else
	{
		$content .= '<option id="Optionuse404to301" value="302"  > 302 </option>';
	}
	
	
	$content .= '</select>';
	
	$content .= '</td>';
	
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<input type="submit" class="button-primary" id="use404to301Submit" name="use404to301Submit" value=" Submit ">';
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</table>';
	$content .= '</form>';
	
	tomas_setting_panel_404s($title, $content);	
	
//!!! 2.8.3
	$title = 'Send 404 Alert with Page URL to Email?';
	$content = '';
	
	$content .= '<form class="form404s" name="form404s" action="" method="POST">';
	$content .= '<table id="table404s" width="100%">';
	
	$content .= '<tr style="text-align:left;">';
	$content .= '<td width="25%"  style="text-align:left;">';
	
	$content .= 'Send 404 Alert with Page URL to Email?';
	$content .= '</td>';
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<select id="select404toEmail" name="select404toEmail" style="width:300px;">';
	$select404toEmail = get_option('select404toEmail');

	
	if ($select404toEmail == 'NO')
	{
		$content .= '<option id="Optionselect404toEmail" selected value="NO"  > NO </option>';
	}
	else
	{
		$content .= '<option id="Optionselect404toEmail" value="NO"  > NO </option>';
	}
	
	if ($select404toEmail == 'YES')
	{
		$content .= '<option id="Optionselect404toEmail" selected value="YES"  > YES </option>';
	}
	else
	{
		$content .= '<option id="Optionselect404toEmail" value="YES"  > YES </option>';
	}
	
	$inputText404toEmail = get_option('inputText404toEmail');
	if (empty($inputText404toEmail))
	{
		$inputText404toEmail = get_option('admin_email');
	}
	
	$content .= '</select>';
	$content .= '<p>';
	//3.5.1
    $content .= '<input type="text" style="width:300px;" id="inputText404toEmail" name="inputText404toEmail" value="'. esc_attr($inputText404toEmail) .'" placeholder="'.__('for example:admin@tooltips.org', "wordpress-404s").'">';
	// $content .= '<input type="text" style="width:300px;" id="inputText404toEmail" name="inputText404toEmail" value="'. $inputText404toEmail .'" placeholder="'.__('for example:admin@tooltips.org', "wordpress-404s").'">';
	$content .= '</p>';
	
	
	$content .= '</td>';
	
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<input type="submit" class="button-primary" id="404toEmailSubmit" name="404toEmailSubmit" value=" Submit ">';
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</table>';
	$content .= '</form>';
	
	tomas_setting_panel_404s($title, $content);

	

	$title = 'Redirect 404 to Specific Page?';
	$content = '';
	
	$content .= '<form class="form404s" name="form404s" action="" method="POST">';
	$content .= '<table id="table404s" width="100%">';
	
	$content .= '<tr style="text-align:left;">';
	$content .= '<td width="25%"  style="text-align:left;">';
	
	$content .= 'Redirect 404 to Specific Page?';
	$content .= '</td>';
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<select id="redirect404tospecificpage" name="redirect404tospecificpage" style="width:300px;">';
	$redirect404tospecificpage = get_option('redirect404tospecificpage');
	
	
	if ($redirect404tospecificpage == 'NO')
	{
		$content .= '<option id="Optionredirect404tospecificpage" selected value="NO"  > NO, Use Wordpress 404.php Template </option>';
	}
	else
	{
		$content .= '<option id="Optionredirect404tospecificpage" value="NO"  > NO, Use Wordpress 404.php Template </option>';
	}
	
	if ($redirect404tospecificpage == 'YES')
	{
		$content .= '<option id="Optionredirect404tospecificpage" selected value="YES"  > YES </option>';
	}
	else
	{
		$content .= '<option id="Optionredirect404tospecificpage" value="YES"  > YES </option>';
	}
	
	$inputredirect404tospecificpage = get_option('inputredirect404tospecificpage');
	if (empty($inputredirect404tospecificpage))
	{
		$inputredirect404tospecificpage = '';
	}
	
	$content .= '</select>';
	$content .= '<p>';
	//3.5.1
	$content .= '<input type="text" style="width:300px;" id="inputredirect404tospecificpage" name="inputredirect404tospecificpage" value="'. esc_attr($inputredirect404tospecificpage) .'" placeholder="'.__('for example:https://yourdomain.com/landingpage', "wordpress-404s").'">';
	//$content .= '<input type="text" style="width:300px;" id="inputredirect404tospecificpage" name="inputredirect404tospecificpage" value="'. $inputredirect404tospecificpage .'" placeholder="'.__('for example:https://yourdomain.com/landingpage', "wordpress-404s").'">';
	$content .= '</p>';
	
	
	$content .= '</td>';
	
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<input type="submit" class="button-primary" id="redirect404tospecificpageSubmit" name="redirect404tospecificpageSubmit" value=" Submit ">';
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</table>';
	$content .= '</form>';
	
	tomas_setting_panel_404s($title, $content);
	
// start 3.4.1
	$title = 'Redirect broken images to specific image?';
	$content = '';
	
	$content .= '<form class="form404s" name="form404s" action="" method="POST">';
	$content .= '<table id="table404s" width="100%">';
	
	$content .= '<tr style="text-align:left;">';
	$content .= '<td width="25%"  style="text-align:left;">';
	
	$content .= 'Redirect broken images to specific image?';
	$content .= '</td>';
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<select id="redirect404imagetospecificimage" name="redirect404imagetospecificimage" style="width:300px;">';
	

	
	if ($redirect404imagetospecificimage == 'NO')
	{
	    $content .= '<option id="Optionredirect404imagetospecificimage" selected value="NO"  > NO, do not fix broken image </option>';
	}
	else
	{
	    $content .= '<option id="Optionredirect404imagetospecificimage" value="NO"  > NO, do not fix broken image </option>';
	}
	
	if ($redirect404imagetospecificimage == 'YES')
	{
	    $content .= '<option id="Optionredirect404imagetospecificimage" selected value="YES"  > YES, fix broken image </option>';
	}
	else
	{
	    $content .= '<option id="Optionredirect404imagetospecificimage" value="YES"  > YES, fix broken image </option>';
	}
	
	$inputredirect404imagetospecificimage = get_option('inputredirect404imagetospecificimage');
	if (empty($inputredirect404imagetospecificimage))
	{
	    $inputredirect404imagetospecificimage = '';
	}
	
	$content .= '</select>';
	$content .= '<p>';
	//3.5.1
	$content .= '<input type="text" style="width:300px;" id="inputredirect404imagetospecificimage" name="inputredirect404imagetospecificimage" value="'. esc_attr($inputredirect404imagetospecificimage) .'" placeholder="'.__('for example:https://yourdomain.com/fix.jpg', "wordpress-404s").'">';
	//$content .= '<input type="text" style="width:300px;" id="inputredirect404imagetospecificimage" name="inputredirect404imagetospecificimage" value="'. $inputredirect404imagetospecificimage .'" placeholder="'.__('for example:https://yourdomain.com/fix.jpg', "wordpress-404s").'">';
	$content .= '</p>';
	
	
	$content .= '</td>';
	
	$content .= '<td width="30%"  style="text-align:left;">';
	$content .= '<input type="submit" class="button-primary" id="redirect404tospecificimageSubmit" name="redirect404tospecificimageSubmit" value=" Submit ">';
	$content .= '</td>';
	$content .= '</tr>';
	$content .= '</table>';
	$content .= '<i>Please check our detailed step by step document to know how to fix broken images in wordpress at "how to fixed broken images in wordpress automatically" </i>';
	$content .= '</form>';
	
	tomas_setting_panel_404s($title, $content);
//end 3.4.1
}

function messageBarFor404s($p_message)
{

	echo "<div id='message' class='updated fade'>";

	echo $p_message;

	echo "</div>";

}

function changeselectboxtoshowcustomurlfield()
{
?>
	<script type="text/javascript">
	jQuery("document").ready(function()
	{
		if (jQuery("#select404Redirect option:selected").val() != 'customurl' )
		{
			jQuery("#inputText404RedirectCustomURL").css('display','none');
		}
		else
		{

		}
		
		jQuery("#select404Redirect").change(function()
		{
			var currentredirecturlvalue = this.value;
			if ((jQuery(this).val() != undefined ) && (currentredirecturlvalue == 'customurl' ) )
			{
				jQuery("#inputText404RedirectCustomURL").css('display','block');
				
			}
			else
			{
				jQuery("#inputText404RedirectCustomURL").css('display','none');
			}
		}

		);

		if (jQuery("#select404toEmail option:selected").val() != 'YES' )
		{
			jQuery("#inputText404toEmail").css('display','none');
		}
		else
		{

		}
		
		jQuery("#select404toEmail").change(function()
		{
			var currentredirecturlvalue = this.value;
			if ((jQuery(this).val() != undefined ) && (currentredirecturlvalue == 'YES' ) )
			{
				jQuery("#inputText404toEmail").css('display','block');
				
			}
			else
			{
				jQuery("#inputText404toEmail").css('display','none');
			}
		}

		);

		
		if (jQuery("#redirect404tospecificpage option:selected").val() != 'YES' )
		{
			jQuery("#inputredirect404tospecificpage").css('display','none');
		}
		else
		{

		}
		
		jQuery("#redirect404tospecificpage").change(function()
		{
			var currentredirecturlvalue = this.value;
			if ((jQuery(this).val() != undefined ) && (currentredirecturlvalue == 'YES' ) )
			{
				jQuery("#inputredirect404tospecificpage").css('display','block');
				
			}
			else
			{
				jQuery("#inputredirect404tospecificpage").css('display','none');
			}
		}

		);
		
		if (jQuery("#redirect404imagetospecificimage option:selected").val() != 'YES' )
		{
			jQuery("#inputredirect404imagetospecificimage").css('display','none');
		}
		else
		{

		}
		
		jQuery("#redirect404imagetospecificimage").change(function()
		{
			var currentredirecturlvalue = this.value;
			if ((jQuery(this).val() != undefined ) && (currentredirecturlvalue == 'YES' ) )
			{
				jQuery("#inputredirect404imagetospecificimage").css('display','block');
				
			}
			else
			{
				jQuery("#inputredirect404imagetospecificimage").css('display','none');
			}
		}

		);				
	});	
	</script>	
<?php 
}

add_action('admin_footer','changeselectboxtoshowcustomurlfield');

function wordpress404scss()
{
?>
<style type="text/css">
.glossary_paginate_links
{
	text-align:center;
}

.glossary_paginate_links .page-numbers
{
	margin:0px 10px;
}
</style>
<?php 	
}

add_action('admin_head','wordpress404scss');

function redirectstatuscode()
{
	$returnstatuscode = '301';
	$selectuse404to301 = get_option('selectuse404to301');
	if (empty($selectuse404to301))
	{
		$returnstatuscode = '301';
	}
	else 
	{
		if ($selectuse404to301 == '301')
		{
			$returnstatuscode = '301';
		}
		
		if ($selectuse404to301 == '302')
		{
			$returnstatuscode = '302';
		}
		
	}
	return $returnstatuscode;
}


