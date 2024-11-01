<?php

if(!isset($_GET['url']) && empty ($_GET['url'])){
    die('No url found');
}

include('curl.php');

$curl = new Curl(time());

$curl->stream(base64_decode($_GET['url']));

?>