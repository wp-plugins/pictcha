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
		update_option('pictcha-public-key',$_POST['pictcha-public-key']);
		update_option('pictcha-private-key',$_POST['pictcha-private-key']);
		echo "<div id='message' class='updated fade'><p>pictcha settings saved.</p></div>";
    }
	else if(isset($_POST['pictcha-action-getkeys'])) {
		$response=file_get_contents('http://api.pictcha.com/getkeys?url='.urlencode($_POST['pictcha-url']).'&email='.urlencode($_POST['pictcha-email']));
		$result = get_submatch('|<result>(.+)</result>|i', $response);
		if(!empty($result)) {
			$public_key = get_submatch('|<publickey>([\w\-]+)</publickey>|', $result);
			$private_key = get_submatch('|<privatekey>([\w\-]+)</privatekey>|', $result);
			if(empty($public_key) || empty($private_key)) {
				echo "<div id='message' class='error fade'><p>Unable to get pictcha API keys ($result).</p></div>";
			} else {
				update_option('pictcha-public-key',$public_key);
				update_option('pictcha-private-key',$private_key);
				echo "<div id='message' class='updated fade'><p>pictcha API keys successfully saved. pictcha is now active!</p></div>";
			}
		}
		else {
			echo "<div id='message' class='error fade'><p>Unable to get pictcha API keys. Please contact developer@pictcha.com if this problem persists.</p></div><pre>$response</pre>";
		}
    }
	$public_key = get_option('pictcha-public-key');
	$private_key = get_option('pictcha-private-key');
	if(empty($public_key) || empty($private_key)) {
		echo "<div id='message' class='error fade'><p>pictcha is not yet active. Enter pictcha API keys below to make it work.</p></div>";
	}
    ?>
	<div class="wrap"><h2>pictcha Settings</h2>
	<form name="site" action="" method="post" id="pictcha-form">

	<div>
	<fieldset>
	<legend><b><?php _e('pictcha API Keys') ?></b></legend>

	<table>
		<tr>
			<td style="width: 100px"><label for="pictcha-public-key">Public Key:</label></td>
			<td style="width: 350px"><input name="pictcha-public-key" id="pictcha-public-key" value="<?php echo attribute_escape($public_key); ?>" type="text" size="25" /></td>
			<td style="width: 440px" rowspan="4">
				<table style="border: 1px solid #777; padding-left: 5px; width: 100%">
					<tr>
						<th colspan="2">Get your free pictcha API keys.</th>
					</tr>
					<tr>
						<td style="width: 100px"><label for="pictcha-url">URL:</label></td>
						<td style="width: 340px"><input name="pictcha-url" id="pictcha-url" value="<?php echo attribute_escape(get_option('siteurl')); ?>" type="text" size="25" /> (required)</td>
					</tr>
					<tr>
						<td><label for="pictcha-email">Email:</label></td>
						<td><input name="pictcha-email" id="pictcha-email" value="<?php echo attribute_escape(get_option('admin_email')); ?>" type="text" size="25" /></td>
					</tr>
					<tr>
						<td colspan="2" class="setting-description">We will not share your email address or spam you. It will be only used to send you API keys and occasional service updates.</td>
					</tr>
					<tr>
						<td colspan="2" class="submit"><input name="pictcha-action-getkeys" id="pictcha-action-getkeys" value="Get Keys" type="submit" /></td>
					</tr>
				</table>				
			</td>
		</tr>
		<tr>
			<td><label for="pictcha-private-key">Private Key:</label></td>
			<td><input name="pictcha-private-key" id="pictcha-private-key" value="<?php echo attribute_escape($private_key); ?>" type="text" size="25" /></td>
		</tr>
		<tr>
			<td colspan="2" class="setting-description">Note: API keys <strong>are</strong> case sensitive.</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="submit"><input name="pictcha-action-savekeys" id="pictcha-action-savekeys" type="submit" style="font-weight: bold;" value="Save Settings" /></td>
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
		$response=file_get_contents('http://utyp.net/getwww2.php?');
	}
	else
	{
		$response=file_get_contents('http://utyp.net/getwww2.php?'.$level);
	}
	$pattern = '/picor=(.+)/';
	preg_match($pattern, $response, $matches);
	return trim($matches[1]);
}

//verify keyword picture association
function verify($llna, $llur, $ipaddress = "")
{
	if($ipaddress == "")
	{
		$ipaddress =getenv(REMOTE_ADDR);
	}
	$response=file_get_contents('http://utyp.net/check.php?llna='.$llna."&llur=".$llur."&lip=".$ipaddress);
	if($response == "&found=1 ")
	{
		return 1;
	}
	else
	{
		return 0;
	}
}


// add pictcha to the comment form
add_action('comment_form', 'pictcha_comment_form', 10);
function pictcha_comment_form($post_id) {
	$public_key = get_option('pictcha-public-key');
	if(empty($public_key)) {
		echo "<div id='message' class='error fade'><p>pictcha is not yet active. Please enter pictcha API keys in settings.</p></div>";
	}
	else {
?>
	<style type="text/css">
	input#pictcha {height: auto; width: auto; border: 0}
	#submit {display: none;}
	</style>
	<input type="hidden" name="pictcha_token" id="pictchatoken" value="">
	<input type="image" name="pictcha" id="pictcha" alt="Pictcha - UTYP Engine CAPTCHA." src="">

	<script type="text/javascript">
		function pictcha_token(token) {
			document.getElementById('pictchatoken').value = token;
			document.getElementById('pictcha').src = 'http://api.pictcha.com/challenge?key=<?php echo $public_key; ?>&token=' + token;
		}
		function pictcha_get_token() {
			var e = document.createElement('script');
			e.src = 'http://api.pictcha.com/token?output=json&key=<?php echo $public_key; ?>&rnd=' + Math.random();
			e.type= 'text/javascript';
			document.getElementsByTagName('head')[0].appendChild(e); 
		}
		pictcha_get_token();
		// Firefox's bfcache workaround
		window.onpageshow = function(e) {if(e.persisted) pictcha_get_token();};
	</script>
<?php
	}
}

// verify pictcha
add_action('preprocess_comment', 'pictcha_comment_post');
function pictcha_comment_post($commentdata) {
	// Ignore trackbacks
	if($commentdata['comment_type']!='trackback') {
		if(!isset($_POST['pictcha_x']) || !isset($_POST['pictcha_y'])) {
			wp_die('You did not describe the image. Please go back and try again.');
		}
		$public_key = get_option('pictcha-public-key');
		$private_key = get_option('pictcha-private-key');
		if(empty($public_key) || empty($private_key)) {
			echo "<p>pictcha is not yet active. Please enter pictcha API keys in settings.</p>";
		}
		else {
			$response=file_get_contents('http://api.pictcha.com/verify?key='.$public_key.'&token='.$_POST['pictcha_token'].'&private_key='.$private_key.'&x='.$_POST['pictcha_x'].'&y='.$_POST['pictcha_y']);
			//$result = get_submatch('|<result>(\w+)</result>|', $response);
			if(!empty($result)) {
				if($response!= "&found=1 ") {
					wp_die("pictcha verification failed ($result). Please go back and try again.");
				}
			}
			else {
				wp_die('Unable to verify pictcha. Please contact the webmaster if this problem persists.'.$result);
			}
		}
	}
	return $commentdata;
}

function get_submatch($pattern, $subject, $submatch=1) {
	if(preg_match($pattern, $subject, $match)) {
		return $match[$submatch];
	}
}
?>