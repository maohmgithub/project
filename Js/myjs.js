$(function(){
    var location = window.location;
    if(String(location).indexOf("book_id") > 0){
        $(".right1").css("display", "none");
        $(".right2").css("display", "block");
    }
    else
        $(".right2").css("display", "none");
    $("#img").click(function(){
        if($("#book_title").val() != '')
            $("#search_form")[0].submit();
        else
            alert('请输入查询内容！');
    });
    $('.right_middle1, .right_bottom1, .new_comment').css('display', 'none');
    //$('.right_middle, .right_bottom, .right_bottom1').css('display', 'none');
})
function showContext(flag){
    if(flag == 1){
        $('.right_middle1').css('display', 'block');
        $('.right_middle, .right_bottom, .right_bottom1, .new_comment').css('display', 'none');
    }
    else if(flag == 2){
        $('.right_bottom1').css('display', 'block');
        $('.right_middle, .right_middle1, .right_bottom, .new_comment').css('display', 'none');
    }
    else if(flag == 3){
        $('.new_comment').css('display', 'block');
        $('.right_middle1, .right_bottom1,.right_middle, .right_bottom').css('display', 'none');
    }
    return false;
}
function showContext1(){
    $('.right_middle, .right_bottom').css('display', 'block');
    $('.right_middle1, .right_bottom1, .new_comment').css('display', 'none');
    return false;
}
function checkSubmit(form){
    if(form.title.value == ''){
        alert("请输入标题！");
        form.title.focus();
        return false;
    }
    if(form.content.value == ''){
        alert("请输入内容！");
        form.content.focus();
        return false;
    }
    return true;
}