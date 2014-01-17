<?php
    class doubanPHP
	{
         //构造函数：初始化API Key、Secret、token
	     function __construct($client_id, $client_secret, $access_token=NULL){
	         $this->client_id = $client_id;
	         $this->client_secret = $client_secret;
	         $this->access_token = $access_token;
	     }
         //生成用户登录连接
	     function login_url($callback_url, $scope=''){
	         $params = array(
	             'response_type' => 'code',         //
	             'client_id' => $this->client_id,   //应用唯一标识，对应APIkey
	             'redirect_uri' => $callback_url,   //授权完成回调地址
	             'scope' => $scope, //申请授权范围
                 'state' => md5(time()) //
	         );
	         return 'https://www.douban.com/service/auth2/auth?'.http_build_query($params);
	     }
	     function access_token($callback_url, $code){
	         $params = array(
	             'grant_type' => 'authorization_code',
	             'code' => $code,
	             'client_id' => $this->client_id,
	             'client_secret' => $this->client_secret,
	             'redirect_uri' => $callback_url
	         );
	         $url = 'https://www.douban.com/service/auth2/token';
	         return $this->http($url, http_build_query($params), 'POST');
	     }
	     function access_token_refresh($callback_url, $refresh_token){
	         $params = array(
                 'grant_type' => 'refresh_token',
	             'refresh_token' => $refresh_token,
	             'client_id' => $this->client_id,
	             'client_secret' => $this->client_secret,
	             'redirect_uri' => $callback_url
	         );
	         $url = 'https://www.douban.com/service/auth2/token';
	         return $this->http($url, http_build_query($params), 'POST');
         }
	     function me(){
	         $params = array();
	         $url = 'https://api.douban.com/v2/user/~me';
	         return $this->api($url, $params);
	     }
	     function share($text, $title, $url, $description='', $pic=''){
	         $params = array(
	             'text' => $text,
	             'rec_title' => $title,
	             'rec_url' => $url,
	             'rec_desc' => $description,
	             'rec_image' => $pic
	         );
	         $url = 'https://api.douban.com/shuo/v2/statuses/';
	         return $this->api($url, $params, 'POST');
	     }
         function api($url, $params, $method='GET'){
	         $headers[] = "Authorization: Bearer ".$this->access_token;
	         if($method == 'GET'){
	             $result = $this->http($url.'?'.http_build_query($params), '', 'GET',$headers);
	         }else{
	             $result = $this->http($url, http_build_query($params), 'POST', $headers);
	         }
	         return $result;
	     }
	     function http($url, $postfields='', $method='GET', $headers=array()){
	         $ci = curl_init();
             curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);   //cURL终止从服务端进行验证
	         curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);   //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
	         curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);  //在发起连接前等待的时间
	         curl_setopt($ci, CURLOPT_TIMEOUT, 30);         //cURL允许执行的最长秒数
	         if($method == 'POST'){
	             curl_setopt($ci, CURLOPT_POST, TRUE);      //发送一个常规的POST请求
	             if($postfields != '')
                     curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);//全部数据使用HTTP协议中的"POST"操作来发送
	         }
	         $headers[] = "User-Agent: doubanPHP(piscdong.com)";
	         curl_setopt($ci, CURLOPT_HTTPHEADER, $headers); //设置HTTP头字段的数组
	         curl_setopt($ci, CURLOPT_URL, $url);            //需要获取的URL地址
	         $response = curl_exec($ci);
	         curl_close($ci);
	         $json_r = array();
	         if($response != '')$json_r = json_decode($response, true);
	         return $json_r;
	     }
	     function getBookList($start, $count=12){
             $params = array();
             $title = $_GET['book_title'] ? $_GET['book_title'] : '科技';
             $url = "https://api.douban.com/v2/book/search?q='".$title."'&start=".$start."&count=".$count."&fields=id,title,url";
             return $this->api($url, $params);
         }
         function getBookDetials($book_id){
             $params = array();
             //需要：图片、书名、ISBN、作者、出版社、价格、简介、评价
             $url = "https://api.douban.com/v2/book/$book_id";
             return $this->api($url, $params);
         }
        function getBookComments($bookId){
            $url = 'http://api.douban.com/book/subject/'.$bookId.'/reviews?alt=json';
            return $this->http($url, '', 'GET','');;
        }
         function mySubStr($str, $start, $len){
            $preg = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $str = trim($str);
            preg_match_all($preg, $str, $match);
            if(count($match[0]) <= $len){
                $slice = join("",array_slice($match[0], $start, $len));
                return $slice;
            }else{
                $slice = join("",array_slice($match[0], $start, $len));
                return $slice.' ...';
            }
        }
    }
    class doubanPHP1{
        function http($url, $postfields='', $method='GET', $headers=array()){
            $ci = curl_init();
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);   //cURL终止从服务端进行验证
            curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);   //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);  //在发起连接前等待的时间
            curl_setopt($ci, CURLOPT_TIMEOUT, 30);         //cURL允许执行的最长秒数
            if($method == 'POST'){
                curl_setopt($ci, CURLOPT_POST, TRUE);      //发送一个常规的POST请求
                if($postfields != '')
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);//全部数据使用HTTP协议中的"POST"操作来发送
            }
            $headers[] = "User-Agent: doubanPHP(piscdong.com)";
            curl_setopt($ci, CURLOPT_HTTPHEADER, $headers); //设置HTTP头字段的数组
            curl_setopt($ci, CURLOPT_URL, $url);            //需要获取的URL地址
            $response = curl_exec($ci);
            curl_close($ci);
            $json_r = array();
            if($response != '')$json_r = json_decode($response, true);
            return $json_r;
        }
        function getBookComments(){
            $url = 'http://api.douban.com/book/subject/25801080/reviews?alt=json';
            return $this->http($url, '', 'GET','');;
        }
        function api($url, $params, $method='GET'){
            $headers[] = "Authorization: Bearer ".$this->access_token;
            if($method == 'GET'){
                $result = $this->http($url.'?'.http_build_query($params), '', 'GET',$headers);
            }else{
                $result = $this->http($url, http_build_query($params), 'POST', $headers);
            }
            return $result;
        }
    }
?>