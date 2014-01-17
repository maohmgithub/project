<?php
    require_once('config.php');
    require_once('douban.php');

    $douban_t = isset($_SESSION['douban_t']) ? $_SESSION['douban_t'] : '';
    $douban = new doubanPHP($douban_k, $douban_s, $douban_t);
    //获取登录用户信息
    $result = $douban->me();
    $douban1 = new doubanPHP1();
    $request_url = "https://api.douban.com/v2/book/reviews";
    $params['book'] = $_POST['book_id'];
    $params['title'] = $_POST['title'];
    $params['content'] = $_POST['content'];
    $params['rating'] = $_POST['rating'];
    return $douban->api($request_url, $params, 'post');
    echo "<script>alert('感谢您的评论！');window.history.back();</script>";
?>