<!DOCTYPE html>
<html>
<head>
    <meta content="text/html" http-equiv="content-type" charset="utf-8">
    <title></title>
    <link href="Css/index.css" rel="stylesheet" type="text/css">
</head>
<body>

    <div class="main">
        <div class="left">
            <div class="left_top">
                search
            </div>
            <div class="left_bottom">
                bottom
            </div>
        </div>
        <div class="right">
            <div class="right_top">
                <div class="image">image</div>
                <div class="book_info">
                    <ul>
                        <li>书名：</li>
                        <li>ISBN：</li>
                        <li>作者：</li>
                        <li>出版社：</li>
                        <li>价格：</li>
                    </ul>
                </div>
            </div>
            <div class="right_middle">
                简介：
            </div>
            <div class="right_bottom">
                评论：
            </div>
        </div>
    </div>

</body>
</html>

<?php
	 session_start();
	 require_once('config.php');
	 require_once('douban.php');
	 $douban_t=isset($_SESSION['douban_t'])?$_SESSION['douban_t']:'';
	 //检查是否已登录
	 if($douban_t!=''){
	     $douban=new doubanPHP($douban_k, $douban_s, $douban_t);
	     //获取登录用户信息
	     $result=$douban->me();
	     var_dump($result);
	     }else{
	     //生成登录链接
	     $douban=new doubanPHP($douban_k, $douban_s);
	     $login_url=$douban->login_url($callback_url, $scope);
	     echo '<a href="',$login_url,'">点击进入授权页面</a>';
	 }    /**
17	     //access token到期后使用refresh token刷新access token
18	     $result=$douban->access_token_refresh($callback_url, $_SESSION['douban_r']);
19	     var_dump($result);
20	     **/

/**
23	     //发布分享
24	     $text='分享内容';
25	     $title='分享标题';
26	     $url='http://www.oschina.net/';
27	     $result=$douban->share($text, $title, $url);
28	     var_dump($result);
29	     **/
?>
