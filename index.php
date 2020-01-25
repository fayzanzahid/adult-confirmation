<?php
/**
 * @package adult-confirmation
 * @version 4.5
 */
/*
Plugin Name: Multipurpose Confirmation Alert
Plugin URI: http://xpertsol.org/adult-confirmation
Description: Adds a Customizable Confirmation Alert sitewide or your post/page using shortcode 
Version: 4.5
Author: Fiz
Author URI: https://fayzanzahid.com
*/


//Define all actions
add_action('admin_menu', 'acxp_adult_confirmation_menu');
add_shortcode( 'acxp_malert' , 'acxp_adultconfirm_sc' );
add_shortcode( 'acxp_adult_confirm' , 'acxp_adultconfirm_sc' );


if (!is_admin())
{ 
	add_action("wp_enqueue_scripts", "acxp_jquery_enqueue", 11);
	$acxp_option_sitewide =  get_option( 'xpsol_acxp_settings' );
	if( $acxp_option_sitewide['sitewide'] == 'true' )
	{
		
		add_action('wp_head', 'acxp_adultconfirm_dphpfc');
		
	}
	
}


function acxp_jquery_enqueue() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js", false, false);
    wp_enqueue_script('jquery');
    wp_register_script('jquery-confirm', plugin_dir_url( __FILE__ )."/js/confirm.js", false, false);
    wp_enqueue_script('jquery-confirm');
	wp_register_style( 'confirm-css', plugin_dir_url( __FILE__ ) . "css/jquery-confirm.css" );
    wp_enqueue_style( 'confirm-css' );
}



function acxp_adult_confirmation_menu(){

add_menu_page( 'Multipurpose Alert Settings', 'Multipurpose Alert', 'manage_options', 'acxp-adult-confirmation', 'acxp_main_pageac');
}

function acxp_main_pageac(){
	if ( !is_admin( ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	if($_GET['action'] == 'save_settings' )
	{
		if(isset($_POST['submit_form']) && $_POST['submit_form'] == 'save' )
		{
		
		$theme = $_POST['theme_acxp'];
		$topbar = $_POST['topbar_acxp'];
		$width = $_POST['width_acxp'];
		$drag = $_POST['drag_acxp'];
		$oani = $_POST['opena_acxp'];
		$cani = $_POST['closea_acxp'];
		$title = trim(sanitize_text_field($_POST['title_text_acxp']));
		$desc = trim(str_replace("\r\n" , '' , $_POST['desc_text_acxp']));
		$abut = trim(sanitize_text_field($_POST['accept_text_acxp']));
		$dbut = trim(sanitize_text_field($_POST['decline_text_acxp']));
		$durl = $_POST['decline_url_acxp'];
		$mdev = $_POST['mdev_acxp'];		
		$sitewide = $_POST['sitewide_acxp'];
		$cook = $_POST['cook_acxp'];
			
			
		$acxp_options = array(
		
			'theme' => $theme,
			'topbar' => $topbar,
			'width' => $width,
			'drag' => $drag,
			'oani' => $oani,
			'cani' => $cani,
			'title' => $title,
			'desc' => $desc,
			'abut' => $abut,
			'dbut' => $dbut,
			'durl' => $durl,
			'mdev' => $mdev,
			'sitewide' => $sitewide, 
			'cook' => $cook
		
		);
		
		update_option( 'xpsol_acxp_settings', $acxp_options );
		
		?>
			<div class="notice notice-success is-dismissible"> 
            <p><strong>Settings Saved Successfully.</strong></p>
                <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
		<?php
		
		}
		else
		{
			?>
		<script type="text/javascript">
			window.location = '?page=acxp-adult-confirmation';
		</script>
		<?php
			exit;
		}
		
		
	}

	
	$acxp_options =  get_option( 'xpsol_acxp_settings' );
	
	if(empty($acxp_options['theme'])){ $acxp_options['theme'] = 'modern'; }
	if(empty($acxp_options['topbar'])){ $acxp_options['topbar'] = 'red'; }
	if(empty($acxp_options['width'])){ $acxp_options['width'] = '500'; }
	if(empty($acxp_options['drag'])){ $acxp_options['drag'] = 'true'; }
	if(empty($acxp_options['oani'])){ $acxp_options['oani'] = 'rotate'; }
	if(empty($acxp_options['cani'])){ $acxp_options['cani'] = 'rotate'; }
	if(empty($acxp_options['title'])){ $acxp_options['title'] = 'Confirmation Alert!'; }
	if(empty($acxp_options['desc'])){ $acxp_options['desc'] = 'Do you want to continue browsing?<br> <span><em>Press <strong>Y</strong> for YES or <strong>N</strong> for No or Click any button</em></span>'; }
	if(empty($acxp_options['abut'])){ $acxp_options['abut'] = 'Accept'; }
	if(empty($acxp_options['dbut'])){ $acxp_options['dbut'] = 'Decline'; }
	if(empty($acxp_options['durl'])){ $acxp_options['durl'] = site_url(); }
	if(empty($acxp_options['mdev'])){ $acxp_options['mdev'] = 'true'; }
	if(empty($acxp_options['sitewide'])){ $acxp_options['sitewide'] = 'true'; }	
	if(empty($acxp_options['cook'])){ $acxp_options['cook'] = '7'; }	
	
	
	
	?>
<style type="text/css">

	input, textarea , select  {
		
		width: 500px;
		padding:10px;
	}
	
	textarea{
		height:300px;
	}
	
	li{
		
		list-style: circle;
	}
	
	ul{
		
		padding: 30px;
	}
	
	code{
		
		padding: 5px;
	}
</style>

			<div class="notice notice-warning is-dismissible"> 
            <p><strong>What's New in Version 4.5</strong><br>
				When you save the "<b>Popup Description Text</b> with a new line, the plugin breaks and popup doesn't show up. In Version 4.5 we fixed that issue and also removed the ugly "spinner" icon from popup. <br>Be advised that, You can still use all html tags in Popup Description."
				</p>
                <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>


    <div class="postbox" id="boxid" style="max-width:98%;">
    <div title="Click to toggle" class="handlediv"></div>
    <h3 class="hndle" style="padding-left:15px; padding-bottom:15px;"><span>Confirmation Pop-up Settings</span></h3>
    <div class="inside">
		
	<form method="post" action="?page=acxp-adult-confirmation&action=save_settings">		
		<table class="table" class="form-table">
			
			
			<tr>
			<th>Enable Sitewide</th>
			<td><select class="select" name="sitewide_acxp">
				<option value="true" <?php if($acxp_options['sitewide'] == 'true'){ echo 'selected';} ?>>Yes</option>
				<option value="false" <?php if($acxp_options['sitewide'] == 'false'){ echo 'selected';} ?>>No</option>
				</select></td>
			</tr>			

			

			<tr>
			<th>Popup Theme</th>
			<td><select class="select" name="theme_acxp">
				<option value="light" <?php if($acxp_options['theme'] == 'light'){ echo 'selected';} ?> >Light</option>
				<option value="dark" <?php if($acxp_options['theme'] == 'dark'){ echo 'selected';} ?> >Dark</option>
				<option value="modern" <?php if($acxp_options['theme'] == 'modern'){ echo 'selected';} ?>>Modern</option>
				<option value="supervan" <?php if($acxp_options['theme'] == 'supervan'){ echo 'selected';} ?>>Supervan</option>
				<option value="material" <?php if($acxp_options['theme'] == 'material'){ echo 'selected';} ?>>Material</option>
				</select></td>
			</tr>

			
			<tr>
			<th>Popup Top bar</th>
			<td><select class="select" name="topbar_acxp">
				<option value="red" <?php if($acxp_options['topbar'] == 'red'){ echo 'selected';} ?>>Red</option>
				<option value="green" <?php if($acxp_options['topbar'] == 'green'){ echo 'selected';} ?>>Green</option>
				<option value="blue" <?php if($acxp_options['topbar'] == 'blue'){ echo 'selected';} ?>>Blue</option>
				<option value="purple" <?php if($acxp_options['topbar'] == 'purple'){ echo 'selected';} ?>>Purple</option>
				<option value="orange" <?php if($acxp_options['topbar'] == 'orange'){ echo 'selected';} ?>>Orange</option>
				<option value="dark" <?php if($acxp_options['topbar'] == 'dark'){ echo 'selected';} ?>>Dark</option>
				</select></td>
			</tr>
			
			<tr>
			<th>Popup Width (px)</th>
			<td><input type="number" min="250" max="650" name="width_acxp" value="<?php if(!empty($acxp_options['width'])){ echo $acxp_options['width']; } ?>" class="input" ></td>
			</tr>

			<tr>
			<th>Dragable</th>
			<td><select class="select" name="drag_acxp">
				<option value="true" <?php if($acxp_options['drag'] == 'true'){ echo 'selected';} ?>>Yes</option>
				<option value="false" <?php if($acxp_options['drag'] == 'false'){ echo 'selected';} ?>>No</option>
				</select></td>
			</tr>			


			<tr>
			<th>Popup Open Animation</th>
			<td><select class="select" name="opena_acxp">
				<option value="scale" <?php if($acxp_options['oani'] == 'scale'){ echo 'selected';} ?> >Scale</option>
				<option value="zoom" <?php if($acxp_options['oani'] == 'zoom'){ echo 'selected';} ?>>Zoom</option>
				<option value="scaleY" <?php if($acxp_options['oani'] == 'scaleY'){ echo 'selected';} ?>>ScaleY</option>
				<option value="scaleX" <?php if($acxp_options['oani'] == 'scaleX'){ echo 'selected';} ?>>ScaleX</option>
				<option value="rotate" <?php if($acxp_options['oani'] == 'rotate'){ echo 'selected';} ?>>Rotate</option>
				<option value="rotateY" <?php if($acxp_options['oani'] == 'rotateY'){ echo 'selected';} ?>>RotateY</option>
				<option value="rotateX" <?php if($acxp_options['oani'] == 'rotateX'){ echo 'selected';} ?>>RotateX</option>
				<option value="rotateYR" <?php if($acxp_options['oani'] == 'rotateYR'){ echo 'selected';} ?>>RotateYR</option>
				<option value="rotateXR" <?php if($acxp_options['oani'] == 'rotateXR'){ echo 'selected';} ?>>RotateXR</option>
				<option value="right" <?php if($acxp_options['oani'] == 'right'){ echo 'selected';} ?>>Slide Right</option>
				<option value="left" <?php if($acxp_options['oani'] == 'left'){ echo 'selected';} ?>>Slide Left</option>				
				<option value="top" <?php if($acxp_options['oani'] == 'top'){ echo 'selected';} ?>>Slide Top</option>				
				<option value="bottom" <?php if($acxp_options['oani'] == 'bottom'){ echo 'selected';} ?>>Slide Bottom</option>				
				<option value="opacity" <?php if($acxp_options['oani'] == 'opacity'){ echo 'selected';} ?>>Fade In</option>				
				</select></td>
			</tr>			

			
			<tr>
			<th>Popup Open Animation</th>
			<td><select class="select" name="closea_acxp">
				<option value="scale" <?php if($acxp_options['cani'] == 'scale'){ echo 'selected';} ?> >Scale</option>
				<option value="zoom" <?php if($acxp_options['cani'] == 'zoom'){ echo 'selected';} ?>>Zoom</option>
				<option value="scaleY" <?php if($acxp_options['cani'] == 'scaleY'){ echo 'selected';} ?>>ScaleY</option>
				<option value="scaleX" <?php if($acxp_options['cani'] == 'scaleX'){ echo 'selected';} ?>>ScaleX</option>
				<option value="rotate" <?php if($acxp_options['cani'] == 'rotate'){ echo 'selected';} ?>>Rotate</option>
				<option value="rotateY" <?php if($acxp_options['cani'] == 'rotateY'){ echo 'selected';} ?>>RotateY</option>
				<option value="rotateX" <?php if($acxp_options['cani'] == 'rotateX'){ echo 'selected';} ?>>RotateX</option>
				<option value="rotateYR" <?php if($acxp_options['cani'] == 'rotateYR'){ echo 'selected';} ?>>RotateYR</option>
				<option value="rotateXR" <?php if($acxp_options['cani'] == 'rotateXR'){ echo 'selected';} ?>>RotateXR</option>
				<option value="right" <?php if($acxp_options['cani'] == 'right'){ echo 'selected';} ?>>Slide Right</option>
				<option value="left" <?php if($acxp_options['cani'] == 'left'){ echo 'selected';} ?>>Slide Left</option>				
				<option value="top" <?php if($acxp_options['cani'] == 'top'){ echo 'selected';} ?>>Slide Top</option>				
				<option value="bottom" <?php if($acxp_options['cani'] == 'bottom'){ echo 'selected';} ?>>Slide Bottom</option>				
				<option value="opacity" <?php if($acxp_options['cani'] == 'opacity'){ echo 'selected';} ?>>Fade In</option>						</select></td>
			</tr>	
			
			<tr>
			<th>Popup Title Text</th>
			<td><input type="text" name="title_text_acxp" class="input" value="<?php if(!empty($acxp_options['title'])){ echo $acxp_options['title']; } ?>" ></td>
			</tr>	

			<tr>
			<th>Popup Description Text</th>
			<td><textarea class="textarea" name="desc_text_acxp"><?php if(!empty($acxp_options['desc'])){ echo $acxp_options['desc']; } ?></textarea></td>
			</tr>				
			
			<tr>
			<th>Accept Button Text</th>
			<td><input type="text" name="accept_text_acxp" class="input" value="<?php if(!empty($acxp_options['abut'])){ echo $acxp_options['abut']; } ?>" ></td>
			</tr>			

			<tr>
			<th>Decline Button Text</th>
			<td><input type="text" name="decline_text_acxp" class="input" value="<?php if(!empty($acxp_options['dbut'])){ echo $acxp_options['dbut']; } ?>" ></td>
			</tr>			
			
			<tr>
			<th>Decline Redirect URL</th>
			<td><input type="url" name="decline_url_acxp" class="input" value="<?php if(!empty($acxp_options['durl'])){ echo $acxp_options['durl']; } ?>" ></td>
			</tr>

			<tr>
			<th>Show Alert</th>
			<td><select class="select" name="cook_acxp">
				<option value="-1" <?php if($acxp_options['cook'] == '-1'){ echo 'selected';} ?>>Always</option>
				<option value="1" <?php if($acxp_options['cook'] == '1'){ echo 'selected';} ?>>Once a day</option>
				<option value="7" <?php if($acxp_options['cook'] == '7'){ echo 'selected';} ?>>Once a Week</option>
				<option value="30" <?php if($acxp_options['cook'] == '30'){ echo 'selected';} ?>>Once a Month</option>
				<option value="365" <?php if($acxp_options['cook'] == '365'){ echo 'selected';} ?>>Once a Year</option>
				</select></td>
			</tr>			
			
			
			<tr>
			<th>Meet Developer Button</th>
			<td><select class="select" name="mdev_acxp">
				<option value="true" <?php if($acxp_options['mdev'] == 'true'){ echo 'selected';} ?>>Yes</option>
				<option value="false" <?php if($acxp_options['mdev'] == 'false'){ echo 'selected';} ?>>No</option>
				</select></td>
			</tr>			
			
			
			<tr>
			<th><input type="hidden" name="submit_form" value="save" ></th>
			<td><button class="button-primary">Save</button></td>
			</tr>
			
		</table>
	</form>		
    
    </div>
	</div>  


	<div class="postbox" id="boxid" style="max-width:98%;">
    <div title="Click to toggle" class="handlediv"></div>
    <h3 class="hndle" style="padding-left:15px; padding-bottom:15px;"><span>How to Use?</span></h3>
    <div class="inside" style="padding-left: 30px;">

	<h2>Documentation</h2>
	
	<h3>Site-wide</h3>
    <p>You can enable sitewide confirmation alert and customize the alert in WP-Admin > Multipurpose Alert > Settings</p>
		
	<h3>Shortcode <code>[acxp_malert]</code></h3>
	<h4>Available Parameters</h4>
		<h5>All parameters can be used together. If you do not set any values in shotcodes, it will get values from WP-Admin -> Multipurpose Alert -> Settings. If you haven't set any settings in WP-Admin -> Multipurpose Alert -> Settings, Default values will be used given below. </h5>
	<ul>
	<li>title</li>
	<li>content</li>
	<li>accept</li>
	<li>decline</li>
	<li>width</li>
	<li>theme</li>
	<li>topbar</li>
	<li>open</li>
	<li>close</li>
	<li>drag</li>
	<li>url</li>
	<li>cookie</li>
	<li>dev</li>
	</ul>
		
	<h5><code>title</code> Default: Confirmation Alert!</h5>	
	<p>title parameter can be used to change the title of confirmation alert and it should be defined in double quotes. It does not support HTML but it does support all languages.<br>
	here is the sample to change alert box title to <strong>Confirmation Alert!</strong> <code>[acxp_malert title="Confirmation Alert!"]</code>	
	</p>
		
	<h5><code>content</code> Default: Do you want to continue browsing?</h5>	
	<p>content parameter can be used to change the description of confirmation alert and it should be defined in double quotes. It does support HTML and  all languages.<br>
	here is the sample to change the alert box description to <strong>Do you want to continue browsing?</strong> <code>[acxp_malert content="Do you want to continue browsing?"]</code>	
	</p>	
		

	<h5><code>accept</code> Default: Accept</h5>	
	<p>accept parameter can be used to change the text of <strong>Accept (Green)</strong> button in confirmation alert and it should be defined in double quotes. It does not support HTML but it supports all languages.<br>
	here is the sample to change the Accept (green) button text to <strong>Yes Go On</strong> <code>[acxp_malert accept="Yes Go On"]</code>	
	</p>			
		
	<h5><code>decline</code> Default: Decline</h5>	
	<p>decline parameter can be used to change the text of <strong>Decline (red)</strong> button in confirmation alert and it should be defined in double quotes. It does not support HTML but it supports all languages.<br>
	here is the sample to change the Decline (red) button text to <strong>No, Take me to Homepage</strong> <code>[acxp_malert decline="No, Take me to Homepage"]</code>	
	</p>
		
	<h5><code>width</code> Default: 500px</h5>	
	<p>width parameter can be used to change the width of confirmation alert and it should be defined in numbers between 250 - 650. For mobile and Tablets it is set to 300px by default to keep the design in place and you cannot change it.<br>
	here is the sample to set width to <strong>500px</strong> <code>[acxp_malert width=500]</code>	
	</p>

	<h5><code>theme</code> Default: Modern</h5>	
	<p>theme parameter can be used to change the theme of confirmation alert and preset background.<br>
	<h5>Available Themes</h5>
	<ul>
	<li>light</li>	
	<li>dark</li>	
	<li>modern</li>	
	<li>supervan</li>	
	<li>material</li>	
	</ul>
	here is the sample to apply <strong>dark</strong> theme <code>[acxp_malert theme=dark]</code>	
	</p>		

		
	<h5><code>topbar</code> Default: red</h5>	
	<p>topbar parameter can be used to change the color of confirmation alert's top bar<br>
	<h5>Available Colors</h5>
	<ul>
	<li>red</li>	
	<li>green</li>	
	<li>blue</li>	
	<li>purple</li>	
	<li>orange</li>	
	<li>dark</li>			
	</ul>
	here is the sample to make top bar <strong>green</strong> <code>[acxp_malert topbar=green]</code>	
	</p>	


	<h5><code>open</code> and <code>close</code> Default: rotate</h5>	
	<p>open and close parameter can be used to change the open and close animation of confirmation alert <br>
	<h5>Available Animations</h5>
	<ul>
	<li>scale</li>	
	<li>scaleY</li>	
	<li>scaleX</li>	
	<li>zoom</li>	
	<li>rotate</li>	
	<li>rotateX</li>
	<li>rotateY</li>
	<li>rotateYR</li>			
	<li>rotateXR</li>
	<li>right</li>
	<li>left</li>
	<li>top</li>
	<li>bottom</li>
	<li>opacity</li>
	</ul>
	here is the sample to apply <strong>scale</strong> animation as opening transition and <strong>rotate</strong> animation as closing. <code>[acxp_malert open=scale close=rotate]</code>	
	</p>	


	<h5><code>drag</code> Default: true</h5>	
	<p>drag parameter can be used to make the confirmation alert draggable<br>
	<h5>Available Options</h5>
	<ul>
	<li>true</li>	
	<li>false</li>	
	</ul>
	here is the sample to make it draggable <code>[acxp_malert drag=true]</code>	
	</p>


	<h5><code>cookie</code> Default: 7</h5>	
	<p>cookie parameter can be used to make sure people don't see the confirmation alert everytime the load the website/page/post. Shortcodes it works separately for every post and page.<br>
	<strong>Available Options ( x days)</strong> <br>
	here is the sample to hide confirmation alert for 30 days once user clicks it <code>[acxp_malert cookie=30]</code>	
	</p>


	<h5><code>url</code> Default: <?php echo site_url(); ?></h5>	
	<p>url parameter can be used to redirect people if they select Decline (red) button on the confirmation alert.<br>
	here is the sample to redirect users to https://google.com <code>[acxp_malert url=https://google.com]</code>	
	</p>

	<h5><code>dev</code> Default: true</h5>	
	<p>dev parameter can be used to enable or disable <strong>Meet the Developer</strong> button on the confirmation alert.<br>
	here is the sample to enable <code>[acxp_malert dev=true]</code>	
	</p>


		
    </div>
	</div> 		
	


    <div class="postbox" id="boxid" style="max-width:98%;">
    <div title="Click to toggle" class="handlediv"></div>
    <h3 class="hndle" style="padding-left:15px; padding-bottom:15px;"><span>About Plugin</span></h3>
    <div class="inside">

	For any queries contact us @ support@xpertsol.org<br />
	 We would appreciate if you report any bugs or send us improvement suggestions.
	<br />
  
    
		
    </div>
	</div> 		
	
	<?php

}



function acxp_adultconfirm_dphpfc()
{
	
	$acxp_options =  get_option( 'xpsol_acxp_settings' );

    ?>

    <script type="text/javascript">
		
		
		function setCookie_acxp(cname,cvalue,exdays) {
		  var d = new Date();
		  d.setTime(d.getTime() + (exdays*24*60*60*1000));
		  var expires = "expires=" + d.toGMTString();
		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}

		function getCookie_acxp(cname) {
		  var name = cname + "=";
		  var decodedCookie = decodeURIComponent(document.cookie);
		  var ca = decodedCookie.split(';');
		  for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
			  c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
			  return c.substring(name.length, c.length);
			}
		  }
		  return "";
		}

		
		 var confirm_acxp = getCookie_acxp("confirm_acxp");
		 		
		
		jQuery( document ).ready(function( $ ) {
			
			if(confirm_acxp == '')
			{

        $.confirm({
            title: '<?php if(!empty($acxp_options['title'])){ echo $acxp_options['title']; } else { echo 'Confirmation Alert!'; } ?>',
            content: '<?php if(!empty($acxp_options['desc'])){ echo $acxp_options['desc']; } else { echo "Do you want to continue browsing?<br> <span><em>Press <strong>Y</strong> for YES or <strong>N</strong> for No or Click any button</em></span>"; } ?>',
            type: '<?php if(!empty($acxp_options['topbar'])){ echo $acxp_options['topbar']; } else { echo 'green'; } ?>',
            animation: '<?php if(!empty($acxp_options['oani'])){ echo $acxp_options['oani']; } else { echo 'rotate'; } ?>',
            boxWidth: '<?php if ( wp_is_mobile() ) { 	echo '300';  } else { if(!empty($acxp_options['width'])){ echo $acxp_options['width']; } else { echo '500'; } } ?>px',
            columnClass: 'small',
            useBootstrap: false,
            bgOpacity: 0.8,
            theme: '<?php if(!empty($acxp_options['theme'])){ echo $acxp_options['theme']; } else { echo 'modern'; } ?>',
			draggable: <?php if(!empty($acxp_options['drag'])){ echo $acxp_options['drag']; } else { echo 'true'; } ?>,
            closeAnimation: '<?php if(!empty($acxp_options['cani'])){ echo $acxp_options['cani']; } else { echo 'rotate'; } ?>',
            buttons: {
                okay: {
                    text: '<?php if(!empty($acxp_options['abut'])){ echo $acxp_options['abut']; } else { echo 'Accept'; } ?>',
                    btnClass: 'btn-green any-other-class',
                    keys: ['y'],
        			action: function(){
			<?php if($acxp_options['cook'] != '-1'){ ?>
        				setCookie_acxp("confirm_acxp", 'yes', <?php if(!empty($acxp_options['cook'])){ echo $acxp_options['cook']; } else { echo '7'; } ?>);
			<?php } ?>
  							          }
                },
                cancle: {
                    text: '<?php if(!empty($acxp_options['dbut'])){ echo $acxp_options['dbut']; } else { echo 'Decline'; } ?>',
                    btnClass: 'btn-red any-other-class',
                    keys: ['n'],
                    action: function(){
                        
                    	window.location = "<?php if(!empty($acxp_options['durl'])){ echo $acxp_options['durl']; } else { echo site_url(); } ?>";
                        }
                }, <?php if(empty($acxp_options['mdev']) || $acxp_options['mdev'] == 'true') { ?>
			somethingElse: {
            text: 'Meet Developer',
            btnClass: 'btn-blue',
            keys: ['enter', 'shift'],
            action: function(){
                window.location = "https://fayzanzahid.com";
            	}
			},<?php } ?>
				                
            }
        });
		
		}
		
    });

		


	</script>
	

    
	<?php 
	
}


function acxp_adultconfirm_sc($atts)
{
		$a = shortcode_atts( array(
        	'title' => 'title',
			'content' => 'content',
			'accept' => 'accept',
			'decline' => 'decline',
			'width' => 'width',
			'topbar' => 'topbar',
			'theme' => 'theme',
			'open' => 'open',
			'close' => 'close',
			'drag' => 'drag',
			'dev' => 'dev',
			'url' => 'url',
			'cookie' => 'cookie'

			
    ), $atts );
	
	
	$acxp_options =  get_option( 'xpsol_acxp_settings' );
	
	
	if($a['title'] == 'title' ){ $a['title'] = $acxp_options['title']; } elseif (empty($acxp_options['title'])){ $a['title'] = 'Confirmation Alert!'; }
	if($a['content'] == 'content'){ $a['content'] = $acxp_options['desc']; } elseif (empty($acxp_options['desc'])){ $a['content'] = 'Do you want to continue browsing?<br> <span><em>Press <strong>Y</strong> for YES or <strong>N</strong> for No or Click any button</em></span>'; }
	if($a['accept'] == 'accept'){ $a['accept'] = $acxp_options['abut']; } elseif (empty($acxp_options['abut'])){ $a['accept'] = 'Accept'; }
	if($a['decline'] == 'decline' ){ $a['decline'] = $acxp_options['dbut']; } elseif (empty($acxp_options['dbut'])){ $a['decline'] = 'Decline'; }
	if($a['width'] == 'width'){ $a['width'] = $acxp_options['width']; } elseif (empty($acxp_options['width'])){ $a['width'] = '500'; }
	if($a['topbar'] == 'topbar' ){ $a['topbar'] = $acxp_options['topbar']; } elseif (empty($acxp_options['topbar'])){ $a['topbar'] = 'green'; }
	if($a['theme'] == 'theme'){ $a['theme'] = $acxp_options['theme']; } elseif (empty($acxp_options['theme'])){ $a['theme'] = 'modern'; }
	if($a['open'] == 'open'){ $a['open'] = $acxp_options['oani']; } elseif (empty($acxp_options['oani'])){ $a['open'] = 'rotate'; }
	if($a['close'] == 'close' ){ $a['close'] = $acxp_options['cani']; } elseif (empty($acxp_options['cani'])){ $a['close'] = 'rotate'; }
	if($a['drag'] == 'drag' ){ $a['drag'] = $acxp_options['drag']; } elseif (empty($acxp_options['drag'])){ $a['drag'] = 'true'; }
	if($a['dev'] == 'dev' ){ $a['dev'] = $acxp_options['mdev']; } elseif (empty($acxp_options['mdev'])){ $a['dev'] = 'true'; }
	if($a['url'] == 'url'){ $a['url'] = $acxp_options['durl']; } elseif (empty($acxp_options['durl'])){ $a['url'] = site_url(); }
	if($a['cookie'] == 'cookie'){ $a['cookie'] = $acxp_options['cook']; } elseif (empty($acxp_options['cook'])){ $a['cookie'] = '7'; }	
	

    ?>


<script type="text/javascript">

			function setCookie_acxp(cname,cvalue,exdays) {
		  var d = new Date();
		  d.setTime(d.getTime() + (exdays*24*60*60*1000));
		  var expires = "expires=" + d.toGMTString();
		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}

		function getCookie_acxp(cname) {
		  var name = cname + "=";
		  var decodedCookie = decodeURIComponent(document.cookie);
		  var ca = decodedCookie.split(';');
		  for(var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
			  c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
			  return c.substring(name.length, c.length);
			}
		  }
		  return "";
		}

		
		 var confirm_acxp = getCookie_acxp("confirm_<?php echo get_the_ID(); ?>");
		 		
	
	
	jQuery( document ).ready(function( $ ) {

		if(confirm_acxp == ''){

        $.confirm({
            title: '<?php echo $a['title']; ?>',
            content: '<?php echo $a['content']; ?>',
            type: '<?php echo $a['topbar']; ?>',
            animation: '<?php echo $a['open']; ?>',
            boxWidth: '<?php if ( wp_is_mobile() ) { 	echo '300';  } else { echo $a['width'];  } ?>px',
            columnClass: 'small',
            useBootstrap: false,
            bgOpacity: 0.8,
            theme: '<?php echo $a['theme']; ?>',
			draggable: <?php echo $a['drag']; ?>,
            closeAnimation: '<?php echo $a['close']; ?>',
            buttons: {
                okay: {
                    text: '<?php echo $a['accept']; ?>',
                    btnClass: 'btn-green any-other-class',
                    keys: ['y'],
        			action: function(){ 
			
					<?php if($a['cookie'] != '-1'){ ?>

						setCookie_acxp("confirm_<?php echo get_the_ID(); ?>", 'yes', <?php echo $a['cookie']; ?> );	
					<?php } ?>
		
						}
                },
                cancle: {
                    text: '<?php echo $a['decline']; ?>',
                    btnClass: 'btn-red any-other-class',
                    keys: ['n'],
                    action: function(){
                        
                    	window.location = "<?php echo $a['url']; ?>";
                        }
                }, <?php if( $a['dev'] == 'true') { ?>
			somethingElse: {
            text: 'Meet Developer',
            btnClass: 'btn-blue',
            keys: ['enter', 'shift'],
            action: function(){
                window.location = "https://fayzanzahid.com";
            	}
			}, <?php } ?>
				                
            }
        });
	}
    });
	
	
	
</script>

<?php 
	

}
	



add_filter('plugin_action_links', 'acxp_settings_link', 10, 2);
function acxp_settings_link($links, $file) {
 
    if ( $file == 'adult-confirmation/index.php' ) {
        /* Insert the link at the end*/
        $links['settings'] = sprintf( '<a href="%s"> %s </a>', admin_url( 'admin.php?page=acxp-adult-confirmation' ), __( 'Settings', 'plugin_domain' ) );
		$links['demo'] = sprintf( '<a href="%s" target="_new"> %s </a>', 'https://xpertsol.org/demo/', __( 'Demo', 'plugin_domain' ) );
    }
    return $links;
 
}
