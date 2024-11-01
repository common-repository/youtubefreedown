<?php

class Curl {
	var $callback = false;
	var $secure = false;
	var $conn = false;
	var $cookiefile =false;
	var $header = false;
	var $cookie = false;
	var $follow = true;
        var $dump   = false;
        var $range  = false;
        var $timeout = false;
	var $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";

	function Curl($u = false) {
		$this->conn = curl_init();
		if (!$u) {
			$u = rand(0,100000);
		}

                $file = dirname(__FILE__).'/temp/'.md5($u);
                $file = str_replace('\\','/', $file);
		$this->cookiefile= $file;

	}

	function setCallback($func_name) {
		$this->callback = $func_name;
	}

	function close() {
		curl_close($this->conn);
		if (is_file($this->cookiefile)) {
			//unlink($this->cookiefile);
		}

	}

	function doRequest($method, $url, $vars) {

		$ch = $this->conn;

		curl_setopt($ch, CURLOPT_URL, $url);
		if ($this->header) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
		} else {
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		}
		curl_setopt($ch, CURLOPT_USERAGENT,$this->user_agent);
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR'], "HTTP_X_FORWARDED_FOR: ".$_SERVER['REMOTE_ADDR'])); // send users ip ???


		if($this->secure) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		}

		if ($this->cookie)
        {
        	curl_setopt($ch, CURLOPT_COOKIE,$this->cookie);
        }

        if ($this->follow) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        } else {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        }

        if($this->dump) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        } else {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        }

        if($this->range)
        {
            curl_setopt($ch, CURLOPT_RANGE,$this->range);
        } 

        if($this->timeout)
        {
            curl_setopt($ch, CURLOPT_TIMEOUT,$this->timeout);
        } else {
            curl_setopt($ch, CURLOPT_TIMEOUT,false);
        }

        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiefile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiefile);

        if ($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect: ')); // lighttpd fix
        }

        $data = curl_exec($ch);

        if ($data) {
                if ($this->callback)
                {
                        $callback = $this->callback;
                        $this->callback = false;
                        return call_user_func($callback, $data);
                } else {
                        return $data;
                }
        } else {
                return false;
        }
	}

	function get($url) {
		return $this->doRequest('GET', $url, 'NULL');
	}


        function processHeader($ch,$string)
        {
            if(preg_match('%Content-Range: bytes 0-1024/([0-9]+)%', $string,$match))
            {
                $this->size =  $match[1];
                return false;
            }

            if(preg_match('%Content-Length: ([0-9]+)%', $string,$match))
            {
                $this->size =  $match[1];
                return false;
            }

            return strlen($string);
        }

        function getSize($url) {

            $this->size = false;

            $this->header  =  true;
            $this->range   = "0-1024";
            $this->dump    =  true; // some sites dont echo in curl

            curl_setopt($this->conn,CURLOPT_HEADERFUNCTION,array($this,processHeader));
            ob_start();
            $this->doRequest('GET', $url,false);
            $result = ob_get_contents();
            ob_end_clean();

            $this->dump    = false;
            $this->header  = false;
            $this->range   = false;
          
            return $this->size;
	}


	function getError()
	{
		return curl_error($this->conn);
	}

	function post($url, $params = false) {

		$post_data = '';

		if (is_array($params)) {

			foreach($params as $var=>$val) {
				if(!empty($post_data))$post_data.='&';
				$post_data.= $var.'='.urlencode($val);
			}

		} else {
			$post_data = $params;
		}

		return $this->doRequest('POST', $url, $post_data);
	}
        
        function streamHeader($ch,$string)
        {
            if(empty ($string)) return;

            header($string);
            return strlen($string);
        }

        function stream($url) 
        {
            $this->dump = true;
            curl_setopt($this->conn,CURLOPT_HEADERFUNCTION,array($this,"streamHeader"));
            $this->doRequest('GET', $url,false);

        }

        function getRedirect($url)
	{
	    $this->follow = false;
	    $this->header = true;

	    $html =  $this->get($url);

	    if(preg_match('/Location: (.*?)[\r\n]+/',$html,$match) || preg_match('/http-equiv=\'Refresh\' content=\'[0-9]+;url=(.*?)\'/s',$html,$match))
	    {
	        return $match[1];
	    }

	    $this->follow = true;
	    $this->header = false;
	}
        
}

function getPage($url,$post = false,$cookie = false)
{
    $pURL = parse_url($url);

    $curl = new Curl($pURL['host']);

    if (strstr($url,'https://'))
    {
        $curl->secure = true;
    }

    if ($post) {
    	return $curl->post($url,$post);
    } else {
        return $curl->get($url);
    }

}
?>