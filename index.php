<!DOCTYPE html>
<html>
<head>
    <meta content="text/html" http-equiv="content-type" charset="utf-8">
    <title>project</title>
    <script type="text/javascript" src="Js/jquery-2.0.0.js" charset="utf-8"></script>
    <script type="text/javascript" src="Js/myjs.js" charset="utf-8"></script>
    <link href="Css/index.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
	 session_start();
	 require_once('config.php');
	 require_once('douban.php');
     error_reporting(E_ALL & ~E_NOTICE);  //设置错误报告级别

	 $douban_t = isset($_SESSION['douban_t']) ? $_SESSION['douban_t'] : '';
	 //检查是否已登录
	 if($douban_t != ''){
	     $douban = new doubanPHP($douban_k, $douban_s, $douban_t);
	     //获取登录用户信息
	     $result = $douban->me();
?>
    <div class="title"><a href="index.php" title="返回首页">欢迎来到豆瓣读书频道</a></div>
    <div class="main">
        <div class="left">
            <div class="left_top">
                <form method="get" action="index.php" id="search_form">
                    <input type="text" name="book_title" id="book_title"><img src="Img/search.png" id="img">
                </form>
            </div>
            <div class="left_bottom">
                <ul>
                    <?php
                        $count = 12;
                        $_SESSION['start'] = $_GET['start'] ? $_GET['start'] : 0;
                        $start = $_SESSION['start'];
                        $book_info = $douban->getBookList($start, $count);
                        for($i = 0; $i < $count; $i++){
                            $title = $book_info['books'][$i]['title'];
                            $j = $i + 1;
                            if(strlen($title) > 11)
                                $title = $douban->mySubStr($title, 0, 11);
                            if(isset($_GET['book_title']) && $_GET['book_title'] != '')
                                echo "<li>$j.&nbsp;&nbsp;<a href ='index.php?book_id=".$book_info["books"][$i]["id"]."&book_title=".trim($_GET['book_title'], '"\'')."&start=".$_SESSION['start']."' title='".$book_info['books'][$i]['title']."'>".$title."</a></li>";
                            else
                                echo "<li>$j.&nbsp;&nbsp;<a href ='index.php?book_id=".$book_info["books"][$i]["id"]."&start=".$_SESSION['start']."' title='".$book_info['books'][$i]['title']."'>".$title."</a></li>";
                        }
                    //var_dump($book_info);
                    ?>
                </ul>
                <table>
                    <tr>
                        <?php
                            if(isset($_GET['book_title']) && $_GET['book_title'] != ''){?>
                                <!--<td><a href="index.php?book_title='<?php //echo $_GET['book_title']; ?>'&start=0">首页</a></td>-->
                                <td>
                                    <?php
                                        if(is_numeric($_SESSION['start']) && $_SESSION['start'] > 0) $temp_start = $_SESSION['start'] - $count;
                                        else $temp_start = 0;
                                        echo "<a href='index.php?book_title=".trim($_GET['book_title'], '"\'')."&start=".$temp_start."'";
                                    ?>
                                    >上一页</a></td>
                                <td><a href="index.php?book_title=<?php echo trim($_GET['book_title'], '"\''); ?>&start=<?php
                                    if(is_numeric($_SESSION['start'])) echo $_SESSION['start'] + $count;
                                    //else echo 0;
                                    ?>">下一页</a></td>
                                <!--<td><a href="index.php?book_title='<?php //echo $_GET['book_title']; ?>'&start=0">尾页</a></td>-->
                         <?php
                            }else{ ?>
                                <td><a href="index.php?start=<?php
                                    if(is_numeric($_SESSION['start']) && $_SESSION['start'] > 0) echo $_SESSION['start'] - $count;
                                    else echo 0;
                                    ?>">上一页</a></td>

                                <td><a href="index.php?start=<?php
                                    if(is_numeric($_SESSION['start'])) echo $_SESSION['start'] + $count;
                                    //else echo 0;
                                    ?>">下一页</a></td>
                         <?php
                            }?>
                    </tr>
                </table>
            </div>
        </div>
        <div class="right1">
             <img src="Img/678.jpg">
             <table class="index_table">
                 <tr><td>程序设计者：毛火明</td></tr>
                 <tr><td>指导老师：姚峰</td></tr>
                 <tr><td>班级：软件一班</td></tr>
                 <tr><td>学号：201005070110</td></tr>
             </table>
        </div>
        <div class="right2">
            <div class="right_top">
            <?php
                $book_details = $douban->getBookDetials($_GET['book_id']);
            ?>
                <div class="image"><a href="<?php echo $book_details['alt']; ?>" target="_blank"><img src="<?php echo $book_details['images']['large'];?>" height="200px" width="160"></a></div>
                <div class="book_info">
                    <ul>
                        <li><b>书名：</b><?php echo $book_details['title'];?></li>
                        <li><b>ISBN：</b><?php echo $book_details['isbn10'];?></li>
                        <li><b>作者：</b><?php echo $book_details['author'][0];?></li>
                        <li><b>出版社：</b><?php echo $book_details['publisher'];?></li>
                        <li><b>价格：</b><?php echo $book_details['price'];?></li>
                    </ul>
                </div>
            </div>
            <div class="right_middle">
                <p>图书简介：</p>
                <div class="summary"><?php
                    if(strlen($book_details['summary']) > 180) echo $douban->mySubStr($book_details['summary'], 0, 180)."&nbsp;&nbsp;<a href='javascript:showContext(1);' id='summary'>更多</a>";
                    else echo $book_details['summary'];
                ?></div>
            </div>
            <div class="right_middle1">
                <p>图书简介：</p>
                <div class="summary_context"><?php echo $book_details['summary'];?></div>
                <a href="javascript:showContext1();">返回</a>
            </div>
            <div class="right_bottom">
                <p>图书评论：</p>
                <div class="comment">
                    <?php $comment = $douban->getBookComments($_GET['book_id']);?>
                    <div class="img"><?php echo "<img src='".$comment['entry'][0]['author']['link'][2]['@href']."' title='".$comment['entry'][0]['author']['name']['$t']."'>"; ?></div>
                    <div class="comment_context">
                        <ul>
                            <li><a href="<?php echo $comment['entry'][0]['link'][1]['@href']; ?>" title="<?php echo $comment['entry'][0]['title']['$t']; ?>" target="_blank"><?php echo $comment['entry'][0]['title']['$t']; ?></a></li>
                            <li><a href="<?php echo $comment['entry'][0]['author']['link'][1]['@href']; ?>" target="_blank"><?php echo $comment['entry'][0]['author']['name']['$t']; ?></a><img src="Img/allstar.jpg" title="力荐"></li>
                            <li style="font-size: 15px"><?php echo $comment['entry'][0]['summary']['$t']; ?></li>
                            <li style="padding-left: 20px;"><?php echo substr($comment['entry'][0]['published']['$t'], 0, 10).'&nbsp;&nbsp;&nbsp;&nbsp;'.substr($comment['entry'][0]['published']['$t'], 11, 8); ?><a href="javascript:showContext(2);" class="more_comment">更多评论</a><a href="javascript:showContext(3);" class="new_comment_a">发表评论</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="right_bottom1">
                <p>图书评论：</p>
                <div class="comment_context">
                <?php
                    $len = count($comment['entry']);
                    for($i = 0; $i < $len; $i++){
                        ?>
                        <div class="img"><?php
                            if(!is_null($comment['entry'][$i]['author']['link'][2]['@href'])) echo "<img src='".$comment['entry'][$i]['author']['link'][2]['@href']."' title='".$comment['entry'][$i]['author']['name']['$t']."'>";
                            else echo "<img src='Img/user_normal.jpg' title='".$comment['entry'][$i]['author']['name']['$t']."'>"; ?>
                        </div>
                        <ul>
                            <li><a href="<?php echo $comment['entry'][$i]['link'][1]['@href']; ?>" title="<?php echo $comment['entry'][$i]['title']['$t']; ?>" target="_blank"><?php echo $comment['entry'][$i]['title']['$t']; ?></a></li>
                            <li><a href="<?php echo $comment['entry'][$i]['author']['link'][1]['@href']; ?>" target="_blank"><?php echo $comment['entry'][$i]['author']['name']['$t']; ?></a><img src="Img/allstar.jpg" title="力荐"></li>
                            <li style="font-size: 15px"><?php echo $comment['entry'][$i]['summary']['$t']; ?></li>
                            <li style="padding-left: 20px;"><?php echo substr($comment['entry'][$i]['published']['$t'], 0, 10).'&nbsp;&nbsp;&nbsp;&nbsp;'.substr($comment['entry'][$i]['published']['$t'], 11, 8); ?>
                        </ul>
                        <hr>
                    <?php } ?>
                    </div>
                <a href="javascript:showContext1();">返回</a>
            </div>
            <div class="new_comment">
                <p>发表评论</p>
                <form action="submit.php" method="post" onsubmit="return checkSubmit(this);">
                    <p>标题：<input type="text" name="title" size="65"></p>
                    <input type="hidden" name="book_id" value="<?php echo $_GET['book_id']; ?>">
                    <p>评价：<input type="radio" name="rating" value="1">很差
                         <input type="radio" name="rating" value="2">较差
                         <input type="radio" name="rating" value="3">还行
                         <input type="radio" name="rating" value="4">推荐
                         <input type="radio" name="rating" value="5" checked>力推</p>
                    <p>内容：<textarea name="content" cols="50" rows="10" style="resize: none;"></textarea></p>
                    <p style="text-align: center"><input type="submit" value="提交" style="margin-right: 40px"><input type="reset" value="重填"></p>
                    <p style="float: right; margin: -35px 20px 0 0;"><a href="javascript:showContext1();">返回</a></p>
                </form>
            </div>
        </div>
    </div>
<?php
         //var_dump($book_details);
         //echo '<pre>';print_r($douban->getBookComments($_GET['book_id']));echo '</pre>';
         //echo $douban->getBookDetials($_GET['book_id']);
         //echo $book_details['url'];
     }else{
     //生成登录链接
     $douban = new doubanPHP($douban_k, $douban_s);
     $login_url = $douban->login_url($callback_url, $scope);
     echo '<center style="margin-top:100px;"><a href="'.$login_url.'">点击进入授权页面</a></center>';
	 }
?>

</body>
</html>
