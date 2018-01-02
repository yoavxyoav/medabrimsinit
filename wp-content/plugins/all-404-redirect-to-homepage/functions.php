<?php

function P404REDIRECT_get_current_URL()
{
	$prt = $_SERVER['SERVER_PORT'];
	$sname = $_SERVER['SERVER_NAME'];
	
	if (array_key_exists('HTTPS',$_SERVER) && $_SERVER['HTTPS'] != 'off' && $_SERVER['HTTPS'] != '')
	$sname = "https://" . $sname; 
	else
	$sname = "http://" . $sname; 
	
	if($prt !=80)
	{
	$sname = $sname . ":" . $prt;
	} 
	
	$path = $sname . $_SERVER["REQUEST_URI"];
	
	return $path ;

}

//---------------------------------------------------- 

function P404REDIRECT_init_my_options()
{	
	add_option(OPTIONS404);
	$options = array();
	$options['p404_redirect_to']= site_url();
	$options['p404_status']= '1';	
	update_option(OPTIONS404,$options);
} 

//---------------------------------------------------- 

function P404REDIRECT_update_my_options($options)
{	
	update_option(OPTIONS404,$options);
} 

//---------------------------------------------------- 

function P404REDIRECT_get_my_options()
{	
	$options=get_option(OPTIONS404);
	if(!$options)
	{
		P404REDIRECT_init_my_options();
		$options=get_option(OPTIONS404);
	}
	return $options;			
}

//---------------------------------------------------- 

function P404REDIRECT_option_msg($msg)
{	
	echo '<div id="message" class="updated"><p>' . $msg . '</p></div>';		
}

//---------------------------------------------------- 

function P404REDIRECT_info_option_msg($msg)
{	
	echo '<div id="message" class="updated"><p><div class="info_icon"></div> ' . $msg . '</p></div>';		
}

//---------------------------------------------------- 

function P404REDIRECT_warning_option_msg($msg) 
{	
	echo '<div id="message" class="error"><p><div class="warning_icon"></div> ' . $msg . '</p></div>';		
}

//---------------------------------------------------- 

function P404REDIRECT_success_option_msg($msg)
{	
	echo '<div id="message" class="updated"><p><div class="success_icon"></div> ' . $msg . '</p></div>';		
}

//---------------------------------------------------- 

function P404REDIRECT_failure_option_msg($msg)
{	
	echo '<div id="message" class="error"><p><div class="failure_icon"></div> ' . $msg . '</p></div>';		
}


//---------------------------------------------------- 
function P404REDIRECT_there_is_cache()
{	

$plugins=get_option( 'active_plugins' );

		    for($i=0;$i<count($plugins);$i++)
		    {   
		       if (stripos($plugins[$i],'cache')!==false)
		       {
		       	  return $plugins[$i];
		       }
		    }


	return '';				
}

   