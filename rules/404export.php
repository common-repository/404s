<?php
if (!defined('ABSPATH'))
{
	exit;
}

function panel404sExport()
{
?>
<div class="wrap 404saddonclass">
	<h2>
	<?php
		echo __("Export 404s", "wordpress-404s");
	?>
	</h2>
	<table class="wp-list-table widefat fixed" style="margin-top:20px;">
		<tr><td>
    			<form action="" method="POST">			
    			<h3><?php echo __("Export 404s to csv", "wordpress-404s"); ?></h3>
    			<?php 
    			wp_nonce_field ( 'fuc404sExportCSVnonce' );
    			?>
			    <div style="margin-top:30px !important;margin-bottom:30px  !important;">
   				<input type="submit" value=" <?php echo __("Export", "wordpress-404s"); ?> " name="export_404s" />
    			</div>
			</form>
			<div>
			<hr />
				<h4>Please note:</h4>
				<div style="margin-bottom:10px;">
				<span style="color:#888;">#1</span> In EXPORT.csv, there are 5 fields: 404 page,referrer, IP, Agent, Date  
				</div>
				<div style="margin-bottom:10px;">
				<span style="color:#888;">#2</span> We just export 404s records with 404 page,referrer, IP, Agent, Date. Related settings of 404s will not be exported, you still need finish settings of 404s manually</span> 
				</div>
			</div>
		</td></tr>
	</table>
<?php
}

function fuc404sExportCSV()
{
	global $wpdb,$table_prefix;

	if (isset($_POST['export_404s']))
	{
		check_admin_referer ( 'fuc404sExportCSVnonce' );	
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="export.csv"');
		$table_name = $table_prefix . "404s";
		$m_404sql = "SELECT `pagenotfoundurl`, `pagenotfoundreferrers`, `pagenotfoundip`, `pagenotfounduseragent`, `pagenotfounddate` FROM `".$table_name ."`";
		$m_404Result = $wpdb->get_results($m_404sql,ARRAY_A);
		
		$m_showExcelHead = "404 page,referrer, IP, Agent, Date";
	
		if (empty($m_404Result))
		{
			die(0);
		}
			
		$m_first_name = '404 page';
		$m_second_name = 'Referrer';
		$m_3rd_name = 'IP';
		$m_4_name = 'Agent';
		$m_5_name = 'Date';
		
		echo $m_first_name.',';
		echo $m_second_name.',';
		echo $m_3rd_name.',';
		echo $m_4_name.',';
		echo $m_5_name;
		echo "\n";
		
		foreach ($m_404Result as $single_404s_exporting)
		{
			$m_first_name = $single_404s_exporting['pagenotfoundurl'];
			$m_second_name = $single_404s_exporting['pagenotfoundreferrers'];
			$m_3rd_name = $single_404s_exporting['pagenotfoundip'];
			$m_4_name = $single_404s_exporting['pagenotfounduseragent'];
			$m_5_name = $single_404s_exporting['pagenotfounddate'];
			
			$m_first_name = str_replace('"', '\"', $m_first_name);
			$m_second_name = str_replace('"', '\"', $m_second_name);
			$m_3rd_name = str_replace('"', '\"', $m_3rd_name);
			$m_4_name = str_replace('"', '\"', $m_4_name);
			$m_5_name = str_replace('"', '\"', $m_5_name);
			
			echo "\"$m_first_name\",";
			echo "\"$m_second_name\",";
			echo "\"$m_3rd_name\",";
			echo "\"$m_4_name\",";
			echo "\"$m_5_name\",";
			echo "\n";
		}
		die(0);
	}
}

add_action('init', 'fuc404sExportCSV');

