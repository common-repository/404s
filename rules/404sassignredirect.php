<?php
if (!defined('ABSPATH'))
{
	exit;
}

function content_func_404s_redirect_control_meta_box()
{
	global $post;
	$current_page_id = get_the_ID();
	$get_post_meta_value_for_this_page = get_post_meta($current_page_id, '404surlredirect', true);
	global $wpdb;
	?>
	<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
	    <tbody>
	    <tr class="form-field">
	        <td>
	        	<p>Please enter the 404 URL in here:</p>
	        	<input type="text" id="404surlredirect" name="404surlredirect" value="<?php echo esc_attr($get_post_meta_value_for_this_page);  ?>">
	        	<?php
	        	/*
				<input type="text" id="404surlredirect" name="404surlredirect" value="<?php echo $get_post_meta_value_for_this_page;  ?>">
				*/
				?>
				<p><i><font color="gray">We will redirect the specific 404 url to the current post</font></i></p>
				<p><i><font color="gray">for example: /dddeeefff.php</font></i></p>
	        </td>
	    </tr>
	    </tbody>
	</table>
	<?php

}


function func_404s_redirect_control_meta_box()
{
	global $post;

	if ($post->post_type == '404s')
	{
		add_meta_box("func_404s_redirect_control_meta_box_id", __( 'What is the 404 URL?', 'wordpress-404s' ), 'content_func_404s_redirect_control_meta_box', null, "side", "high", null);
	}

}

function save_content_func_404s_redirect_control_meta_box($post_id, $post, $update)
{
	global $post;

	$current_page_id = get_the_ID();

	$get_post_meta_value_for_this_page = get_post_meta($current_page_id, '404surlredirect', true);

	if(isset($_POST['404surlredirect']) != "") {
	    $meta_box_checkbox_value = sanitize_text_field(trim($_POST['404surlredirect']));
		update_post_meta( $current_page_id, '404surlredirect', $meta_box_checkbox_value );
	} else {
		update_post_meta( $current_page_id, '404surlredirect', '' );
	}
}

add_action( 'add_meta_boxes',  'func_404s_redirect_control_meta_box' );
add_action( 'save_post', 'save_content_func_404s_redirect_control_meta_box' , 10, 3);

