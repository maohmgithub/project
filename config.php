<?php
	 //配置文件
	 header('Content-Type: text/html; charset=UTF-8');

	 $douban_k = '065d07e2548cad24131717a6fda6076c'; //豆瓣应用API Key
	 $douban_s = '4cecb03a20ea098c'; //豆瓣应用Secret
     $callback_url = 'http://localhost/PHP/project/callback.php';
     //$callback_url = 'http://www.douban.com/accounts/apptokens/callback.php';
	 //$callback_url='http://yoururl/callback.php'; //授权回调网址
	 $scope = 'douban_basic_common, book_basic_r, book_basic_w'; //权限列表，评论、获取图书信息
?>