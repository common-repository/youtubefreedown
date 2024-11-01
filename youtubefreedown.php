<?php
/*
Plugin Name: Youtube: Search - play - download
Plugin URI: http://tammax.it/youtube-freedown/
Description: Search your favorite video from Youtube and watch it, if you want you can also it download.
Author: Tammax
Version: 1.0
Author URI: http://tammax.it


Copyright 2009  tammax  (tammax@tammax.it)

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

/*PAGINA OPZIONI PLUGIN */
$options_pagevideo = get_option('siteurl') . '/wp-admin/admin.php?page=youtubefreedown/options.php';


//funzione che genera il menu
function youtubefreedown_options_page() {
         add_menu_page('Youtubefreedown', 'Youtube freedown', 10, 'youtubefreedown', 'youtubefreedownoption',  plugins_url() . '/youtubefreedown/images/Slide.png');
		
}
add_action('admin_menu', 'youtubefreedown_options_page');

function youtubefreedownoption() {
echo '
<div class="wrap">
	<h2>Youtube Freedown option page</h2>
		<div id="poststuff" class="metabox-holder has-right-sidebar">
			<div id="categorydiv" style="float:right; width:24%;" class="postbox ">
				<div class="handlediv" title="Click to toggle"></div>
				<h3 class="hndle">Donations</h3>
				<div class="inside">
					<em>Plugins  grow on trees.</em> Please remember that this plugin is provided to you free of charge, yet it takes many hours of work to maintain and improve!
					<div style="margin:10px 0 10px 0; text-align:center;">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="Y4JJ3V97HUDF8">
							<input type="image" src="https://www.paypalobjects.com/it_IT/IT/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - Il sistema di pagamento online più facile e sicuro!">
							<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
						</form>
					</div>
				</div>
			</div>
			<div id="categorydiv" style="margin-right:30px;width:75%;" class="postbox ">
				<div class="handlediv" title="Click to toggle"></div>
				<h3 class="hndle">Options - settings </h3>
				<div class="inside">
				<p>If you want use the short code <b style="color:#0099FF; font-size:15px;">[searchplaydownload]</b> direct on your page/post you must insert on Page Title field, the same title that you have write for your page. <b>"Case sensitive"</b></p>
				<p><br />Example : http://your_site.xx/Title-page  you must insert Title-page <b style="color:#0099FF">" OK "</b></p>
				<p><br />Example : http://your_site.xx/Title-page  if you insert title-page <b style="color:red">" NOT OK "</b> see t and T on name page.</p>
				<p><br />You can use another system for include the plugin <br />
					<ul style="list-style:square; padding:10px;">
						<li>Insert on your templeta this code, where you want to see it in action.<br /><input readonly style="backround:#0099FF; width:560px" type="text" value="<?php if ( function_exists(\'youtube_search_play_download\') ) { youtube_search_play_download(); }  ?>" />
						</li>
						<li>Insert in field Page Title this string <b style="font-size:15px; color:#0099FF">DIRECTLY</b></li>
					</ul>
				</p>
				<p>Save update your optins</p>
				<div style="margin-left:0px;">
					<form method="post" action="options.php">'.wp_nonce_field("update-options").'
						<fieldset name="general_options" class="options">     
							<div style="margin:0;padding:0;">
								<br /><b>Page title</b> : <input name="youtubefreedownpage" id="youtubefreedownpage" size="35" value="'. get_option("youtubefreedownpage").'"></input>
							</div>
							<br />
							<input type="hidden" name="action" value="update" />
							<input type="hidden" name="page_options" value="youtubefreedownpage" />
						</fieldset>		
						<p class="submit"><input type="submit" name="Submit" value="';	echo _e("Save / Update Options"); echo '" /></p>
					</form>';
		  echo '</div>';
		  echo '</div>';
	  echo '</div>';
  echo '</div>';
echo '</div>';
}

/*ADD SCRIPT AND CSS TO HEADER ONLY IF IS NECESSARY */
function youtube_search_play_download_script() {
	
	$paginavideo = get_option('youtubefreedownpage');
	
	if ($paginavideo == "DIRECTLY" OR is_page($paginavideo) ){

		echo '<link rel="stylesheet" type="text/css" href="' . plugins_url() . '/youtubefreedown/style.css" />' . "\n";
		echo '<script type="text/javascript" src="' . plugins_url() . '/youtubefreedown/js/jquery.mousewheel.min.js' . '"></script>' . "\n";
		echo '<script type="text/javascript" src="' . plugins_url() . '/youtubefreedown/js/jquery.gcomplete.0.1.2.min.js' . '"></script>' . "\n";
		echo '<script type="text/javascript" src="' . plugins_url() . '/youtubefreedown/js/youtube.js' . '"></script>' . "\n";
	}
}	

	add_action('wp_head', 'youtube_search_play_download_script');


/*AGGIUNGO IL TAG PER I POST E LE PAGINE */
function youtube_search_play_download_content($content) {

	$content = preg_replace_callback("/\[searchplaydownload\]/i", "youtube_search_play_download", $content);
	
	return $content;
}

add_filter('the_content', 'youtube_search_play_download_content');


function youtube_search_play_download() {

echo '<div id="corspo">
			<div id="errorbox">Please Load jQuery first</div>
			<div id="apDiv3">
				<input type="hidden" value="'.plugins_url().'" id="ursl_sito_js_file" />
				<input type="text" name="search" id="searchbox" onclick="if(this.value == \'Type your search in this field ............... and press......\')this.value=\'\';" onblur="if(this.value == \'\')this.value=\'Type your search in this field ............... and press......\';"/>
				<label>
					<input type="submit" name="submitsearch" id="submitsearch" value="Search"  />
				</label>
			</div>
			<div id="apDiv1"></div>';
?>
			<div id="scarica">
				
					
						<input type="text" name="url" class="search" id="link_down" value="<?php isset($url)?$url:'Enter URL of Video that you want download ...';?>" onclick="if(this.value == 'Enter URL of Video that you want download ...')this.value='';" onblur="if(this.value == '')this.value='Enter URL of Video that you want download ...';" />
						 <input type="submit" value="Download" id="submitdownload" />
					
				
				
			</div>
			<?php
  		 echo '<div id="apDiv6"></div> ';
		 echo '<div id="apDiv4">
				<table >
					<tr>
						<td><div id="video0" class="videodiv"></div></td>
						<td><div id="video1" class="videodiv"></div></td>
						<td><div id="video2" class="videodiv"></div></td>    
						<td><div id="video3" class="videodiv"></div></td>
					</tr>
					<tr>  
						<td><div id="video4" class="videodiv"></div></td>
						<td><div id="video5" class="videodiv"></div></td>
						<td><div id="video6" class="videodiv"></div></td>
						<td><div id="video7" class="videodiv"></div></td>
					</tr>
					<tr>
						<td><div id="video8" class="videodiv"></div></td>
						<td><div id="video9" class="videodiv"></div></td>
						<td><div id="video10" class="videodiv"></div></td>
						<td><div id="video11" class="videodiv"></div></td>
					</tr>
					
				</table>
			</div>
			<div id="apDiv2" >
				<div id="buttons" > 
					<input class="bottoni" type="image" src="' . plugins_url() . '/youtubefreedown/images/Arrowleft.png" id="leftbutton" alt="preview" />
					<input class="bottoni" type="image" src="' . plugins_url() . '/youtubefreedown/images/Error.png" id="exitbutton" alt="close" />
					<input class="bottoni" type="image" src="' . plugins_url() . '/youtubefreedown/images/Arrowright.png" id="rightbutton" alt="next" />
				</div>
			</div>
		</div>';
		echo '<script  type="text/javascript" >		
				jQuery("#submitdownload").click(function() { 
					var email = jQuery("input#link_down").val();   
					var dataString = email;
					jQuery.ajax({  type: "POST",  dataType:"HTML", 
						url: "' . plugins_url() . '/youtubefreedown/down.php", data: {url:dataString},
						success: function(result) { 
							jQuery("#apDiv6").html(result);
							jQuery("#apDiv6").show();
						}
					}); 
					return false;
				});
			</script>';
}
?>