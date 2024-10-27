<?php
if (!defined('ABSPATH'))
{
	exit;
}

function tomas_404_to_email($post_id = 0, $post_url = '', $anonymous_data = false, $topic_author = 0)
{
	$inputText404toEmail = get_option('inputText404toEmail');
	if (empty($inputText404toEmail))
	{
		$inputText404toEmail = get_option('admin_email');
	}
	
	$blog_name = esc_attr(wp_specialchars_decode(get_option('blogname'), ENT_QUOTES));

	$email_subject = $blog_name. " New 404s Alert: ";

	$email_body = "Site name: ".$blog_name." \n\r";

	$email_body .= "--------------------------------\n\r";
	$email_body .= "404 Url: ".$post_url."\n\r";

	if (!(empty($inputText404toEmail)))
	{
		@wp_mail( $inputText404toEmail, $email_subject, $email_body );
	}
}