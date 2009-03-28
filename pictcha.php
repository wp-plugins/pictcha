<?php
/*
Plugin Name: Pictcha
Plugin URI: http://utyp.net/lab.php
Description: UTYP Engine CAPTCHA.
Author: Gilemon
Version: 0.1
Author URI: http://nthinking.net/
*/

/*  Copyright 2009  gilemon  (email : gilemon@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Add settings link to the plugin page
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'pictcha_add_action_links' );
function pictcha_add_action_links( $links ) { 
	$link = '<a href="options-general.php?page=pictcha">Settings</a>'; 
	array_unshift( $links, $link ); 
	return $links; 
}

// Add admin menu for settings
add_action('admin_menu', 'pictcha_add_option_page');
function pictcha_add_option_page() {
    // Add a new submenu under options:
    add_options_page('pictcha', 'pictcha', 'edit_themes', 'pictcha', 'pictcha_options_page');
}

function pictcha_options_page() {
	if(isset($_POST['pictcha-action-savekeys'])) {
		update_option('pictcha_security_level',$_POST['pictcha_security_level']);
		echo "<div id='message' class='updated fade'><p>Pictcha settings saved.</p></div>";
    }
	$security_level = get_option('pictcha_security_level');
	if($security_level == "")
	{
		$security_level = 40;
	}
	
    ?>
	<div class="wrap"><h2>Pictcha Settings</h2>
	<form name="site" action="" method="post" id="pictcha-form">

	<div>
	<fieldset>
	<legend><b><?php _e('pictcha API Keys') ?></b></legend>

	<table>
		<tr>
			<td style="width: 120px"><label for="pictcha_security_level">Security_level :</label></td>
			<td style="width: 350px"><input name="pictcha_security_level" id="pictcha_security_level" value="<?php echo attribute_escape($security_level); ?>" type="text" size="25" /></td>
			<td>&nbsp;</td>
			<td class="submit"><input name="pictcha-action-savekeys" id="pictcha-action-savekeys" type="submit" style="font-weight: bold;" value="Save Settings" /></td>
		</tr>
	</table>
	<table>
		<tr>
			<td>Security_level = how much the picture has been indentified, higher = more tags associated<br>40 is a good balance between risk and user-friendliness<br>Maximum advised is 200<br>Minimum advised is 10</td>
		</tr>
	</table>
	
	
	</fieldset>
	</div>
	</form>
	<small><a href="http://utyp.net/lab.php">Pictcha - UTYP Engine CAPTCHA.</a></small>
	</div>
	<?php
}

//retrieve a random picture from UTYP engine
//if passing level, the engine will return picture higher or equal to this level
//levels meaning how much the picture has been indentified, higher = more tags associated
//30 is a good balance between risk, user friendliness
function get_picture($level="")
{
	if($level == "")
	{
		$response=file_get_contents('http://utyp.net/getwww2.php?session=1');
	}
	else
	{
		$response=file_get_contents('http://utyp.net/getwww2.php?session=1&level'.$level);
	}
	//echo $response. "|";
	$matches = explode("=", trim($response));
	$arra = array($matches[0],$matches[1]);
	return $arra;
}

//verify keyword picture association
function verify($llna, $llur, $ipaddress = "")
{
	if($ipaddress == "")
	{
		$ipaddress =getenv(REMOTE_ADDR);
		//echo $ipaddress. "|";
	}
	$llna = str_replace(" ","+",$llna);
	$llur = str_replace(" ","+",$llur);
	$cURL = 'http://utyp.net/check.php?llna='.$llna."&llid=".$llur."&lip=".$ipaddress ;
	//echo $cURL. "|";
	$response=file_get_contents($cURL);
	//echo $response. "|";
	if($response == "&found=1 ")
	{
		return 1;
	}
	else
	{
		if($response == "&found=2")
		{
			wp_die('Your Pictcha Session has Expired, try refreshing your browser.');
		}
		return 0;
	}
}


// add pictcha to the comment form
add_action('comment_form', 'pictcha_comment_form', 10);
function pictcha_comment_form($post_id)
{
	$security_level = get_option('pictcha_security_level');
	if($security_level == "")
	{
		$security_level = 40;
	}
	$arra = get_picture($security_level);
	$lasess = $arra[0];
	$lapic = $arra[1];
?>
	<style type="text/css">
	input#pictcha {height: auto; width: auto; border: 0}
	#submit {display: none;}
	</style>
	<input type="hidden" name="pictureUrl" id="pictureUrl" value="<?php echo $lasess; ?>">
	<img src="<?php echo $lapic; ?>">
	<br> Question: <a href="http://utyp.net/pictcha-help.html" target="help">What</a>'s this picture?<br>
	<input type="text" id="inputOr" name="inputOr" SIZE="15" maxlength="25" /><br>
	<input type="submit" value="send" />

<?php
}

// verify pictcha
add_action('preprocess_comment', 'pictcha_comment_post');
function pictcha_comment_post($commentdata) {
	
	if(!isset($_POST['inputOr'])) 
	{
		wp_die('You did not describe the image. Please go back and try again.');
	}
	if(!isset($_POST['pictureUrl'])) 
	{
		wp_die('Unable to retrieve Picture. Please contact the webmaster if this problem persists.');
	}
	if(!verify($_POST['inputOr'], $_POST['pictureUrl']))
	{
		wp_die('Wrong Pictcha Answer. Try again if you\'re sure you\'re not a robot');
	}
	return $commentdata;
}

function get_submatch($pattern, $subject, $submatch=1) {
	if(preg_match($pattern, $subject, $match)) {
		return $match[$submatch];
	}
}
?>