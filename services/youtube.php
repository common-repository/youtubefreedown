<?php


class youtube {

    var $conn = false;
    var $src  = true;
    var $stream = false;
    var $error  = false;
   

    function get($url)
    {
        global $siteURL;

        $curl = new Curl('youtube');

        if ( (preg_match( "/v=([a-zA-Z0-9\\_\\-]+)/", $url, $videoID ) || preg_match( "/video_id=([a-zA-Z0-9\\_\\-]+)/", $url, $videoID )  || preg_match( "/youtube\\.com\\/v\\/([a-zA-Z0-9\\_\\-]+)/", $url, $videoID )) )      {
            $videoID = $videoID[1];
        } {
            $this->error = "Invalid Youtube URL";
        }

        $html = $curl->get($url);
        
        if(strstr($html,'verify-age-thumb'))
        {
            $this->error = "Adult Video Detected";
            return false;
        }

        if(strstr($html,'das_captcha'))
        {
            $this->error = "Captcah Found please run on diffrent server";
            return false;
        }

        if(!preg_match('/stream_map=(.[^&]*?)&/i',$html,$match))
        {
            $this->error = "Error Locating Downlod URL's";
            return false;
        }

        preg_match('%<title>YouTube - (.[^<]*?)</title>%',$html,$tmatch);

        if(!empty($tmatch[1]))
        {
            $title = urlencode($tmatch[1]);
        } else {
            $title = "video";
        }

        $fmt_url =  urldecode($match[1]);


        if(preg_match('/^(.*?)\\\\u0026/',$fmt_url,$match))
        {
            $fmt_url = $match[1];
        }

        $urls = explode(',',$fmt_url);
        $foundArray = array();

        foreach($urls as $url)
        {
          if(preg_match('/url=(.*?)&.*?itag=([0-9]+)/si',$url,$um))
            {
                $u = urldecode($um[1]);
                $foundArray[$um[2]] = $u;
            }
        }


        $formats = array(
            '13'=>array('3gp','Low Quality'),
            '17'=>array('3gp','Medium Quality'),
            '36'=>array('3gp','High Quality'),
            '5'=>array('flv','Low Quality'),
            '6'=>array('flv','Low Quality'),
            '34'=>array('flv','High Quality (320p)'),
            '35'=>array('flv','High Quality (480p)'),
            '18'=>array('mp4','High Quality (480p)'),
            '22'=>array('mp4','High Quality (720p)'),
            '37'=>array('mp4','High Quality (1080p)'),
        );

        foreach ($formats as $format => $meta) {
            if (isset($foundArray[$format])) {
                $videos[] = array('ext'=>$meta[0],'type'=>$meta[1],'url'=>$siteURL.'/stream.php?url='.base64_encode($foundArray[$format]."&title=".$title));
            }
        }

        return $videos;

    }

}
?>