<?php
	 //授权回调页面，即配置文件中的$callback_url
	 session_start();
	 require_once('config.php');
	 require_once('douban.php');

	 if(isset($_GET['code']) && $_GET['code'] != ''){
	     $douban = new doubanPHP($douban_k, $douban_s);
	     $result = $douban->access_token($callback_url, $_GET['code']);
	 }
	 if(isset($result['access_token']) && $result['access_token'] != ''){
	     echo '授权完成，请记录<br/>access token：<input size="50" value="',$result['access_token'],'"><br/>refresh token：<input size="50" value="',$result['refresh_token'],'">';

	     //保存登录信息，此示例中使用session保存
	     $_SESSION['douban_t'] = $result['access_token']; //access token
	     $_SESSION['douban_r'] = $result['refresh_token']; //refresh token
	 }else{
	     echo '授权失败';
	 }
	 echo '<br/><a href="./">返回</a>';
?>
