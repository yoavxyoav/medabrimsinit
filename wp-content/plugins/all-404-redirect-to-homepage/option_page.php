<?php

include_once "cf_dropdown.php";

global $wpdb,$table_prefix;



$redirect_to = (isset($_POST['redirect_to'])) ? sanitize_text_field($_POST['redirect_to']) : '';
$nonce = $_REQUEST['_wpnonce'];

if($redirect_to !=='' && wp_verify_nonce( $nonce, 'p404home_nounce' ))
	{

		$newoptions['p404_redirect_to']= $redirect_to;
		$newoptions['p404_status']=sanitize_text_field($_POST['p404_status']);
		P404REDIRECT_update_my_options($newoptions);
		P404REDIRECT_option_msg('Options Saved!');
		
	}else {
                P404REDIRECT_failure_option_msg('Unable to save data!');
        }
	
$options= P404REDIRECT_get_my_options();
?>

<?php
if(P404REDIRECT_there_is_cache()!='') 
P404REDIRECT_info_option_msg("You have a cache plugin installed <b>'" . P404REDIRECT_there_is_cache() . "'</b>, you have to clear cache after any changes to get the changes reflected immediately! ");
?>

<div class="wrap">
<div class='procontainer'><div class='inner'>
<h2>All 404 Redirect to Homepage</h2>
<form method="POST">
	
	<br/><br/>
	404 Redirection Status: 
	<?php
		$drop = new dropdown('p404_status');
		$drop->add('Enabled','1');	
		$drop->add('Disabled','2');
		$drop->dropdown_print();
		$drop->select($options['p404_status']);
	?>
	
	<br/><br/>
	
	Redirect all 404 pages to: 
	<input type="text" name="redirect_to" id="redirect_to" size="30" value="<?php echo $options['p404_redirect_to']?>">		
	
	<br/><br/>
	<span style="color:red">Plugin detected some 404 pages</span>, upgrade to <a target="_blank" href="http://www.clogica.com/product/seo-redirection-premium-wordpress-plugin">pro</a> to manage all errors and improve your SEO
	
<br/><br/><br/>
<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $nonce = wp_create_nonce('p404home_nounce'); ?>" />
<input  class="button-primary" type="submit" value="  Update Options  " name="Save_Options"></form>  

</div></div>


<br/><br/>



<div class='procontainer'><div class='inner'>

<h3>Upgrade to <a target="_blank" href="http://www.clogica.com/product/seo-redirection-premium-wordpress-plugin">pro version</a> and empower your site SEO,<strong style="color:green"> Now 39% off</strong></h3>

</div>