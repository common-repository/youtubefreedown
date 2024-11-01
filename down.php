<?php
$siteURL = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']); 
$url = $_POST['url'];
include('config.php');
include('curl.php');
$url = $_REQUEST['url'];
$parts = parse_url($url);
$host = $parts['host'];
$service = strtolower($host);
$host_parts = explode('.',$service);
$service = $host_parts[count($host_parts)-2];
// $service_path = sprintf("wp-content/plugins/youtubefreedown/services/%s.php",$service);
include('services/youtube.php');
$obj = new $service();
$obj->stream = $stream;
$videos = $obj->get($url);
if(!isset($videos[0]['url'])){
	if(isset($obj->error) && !empty ($obj->error)){
		$error = $obj->error;
        }else{
        	$error = "No Videos found or site not supported.";
        }
}
if(!isset($error) && isset($videos[0]['url'])) { 
	echo '<div class="gboxtop"></div>';
	echo '<div class="gbox" style="margin-top:10px; padding:10px;">';
	echo '<h3 style=" font-size:16px; width:100%;" >Download Details <a href="#" id="nuovovideo" style="float:right;margin-left:50px; font-size:16px;">Close</a></h3>';
	//echo '<p>Original video link <a href="'.$url.'" target="_blank">'.$url.'</a></p>';
	foreach ($videos as $video){ 
		echo '<p><a href="'.$video["url"].'">Download </a>'.sprintf(" video.%s - %s",$video["ext"],$video["type"]).'<br /></p>';
	}
	echo '</div>';
echo ' <script>	
  jQuery("a#nuovovideo").click(function ( event ) {
      event.preventDefault();
      jQuery("#apDiv6").toggle();
    });
</script>';
} elseif (isset($error) && !empty ($error)) {
	echo '<center><h3>'.$error.'</h3></center>';
} 
?>