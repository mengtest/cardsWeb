<?php
session_start();

if(isset($_POST['submit'])){
    require_once "lib/db/Db.php";
    $username = htmlspecialchars($_POST['username']);
    $oldpwd = htmlspecialchars($_POST['oldpwd']);
    $oldpwd = md5(md5($oldpwd));
    $newpwd = htmlspecialchars($_POST['oldpwd']);
    $newpwd = md5(md5($newpwd));
    $checkcode = htmlspecialchars($_POST['checkcode']);
  
    function check($username,$pwd,$checkcode){
        if(strtolower($checkcode) != strtolower($_SESSION['code'])){
            return   "你输入的验证码不正确";
        }
        require_once 'config/adminData.php';
        $db1 = Db::getInstance($config);
        $field = ['username','pwd'];
        $ret = $db1->getOne('user',$field,"username ='$username' AND pwd ='$pwd'");
        if($ret){
            return 'succeed';
        }else{
            return "用户名或者密码错误!";
        }
    }
    require_once 'config/adminData.php';
    $db1 = Db::getInstance($config);
    $ret = check($username,$oldpwd,$checkcode);
    if($ret == "succeed"){
        $_SESSION['username'] = $username;
        $data =['pwd'=>$newpwd];
        $where ="pwd='$oldpwd'";
        $is=$db1->updateData($data,$where);
        if($is){
            header("Location:index.php");
        }else{
            $errorInfo="修改失败";
        } 
    }else{
        $errorInfo = $ret;
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>changepwd</title>
    <style type="text/css">
    html{
        height:100%;
        width:100%;
    }
    body{
        position:relative;
        margin: 0; 
        padding:0; 
        overflow: hidden;
    }
    #formwrap{
        position:absolute;
        top:50%;
        left:50%;
        width:400px;
        margin-left:-200px;
        height:300px;
        margin-top:-150px;
        background:transparent;
        color:green;
        text-align:center;
        
    }
    #usernamewrap{
        width:70%;
        height:30px;
        overflow:hidden;
        border:1px solid green;
        margin-left:auto;
        margin-right:auto;
        margin-top:20%;
    }
    #pwdwrap{
        width:70%;
        height:30px;
        overflow:hidden;
        border:1px solid green;
        margin-left:auto;
        margin-right:auto;
        margin-top:20px;
    }
    #codeimg{
        width:70%;
        height:30px;
        overflow:hidden;
        border:1px solid green;
        margin-left:auto;
        margin-right:auto;
        margin-top:20px;
    }
    #checkwrap{
        float:left;
    }

    #checkimg{
        float:right;
        width:78px;
        height:30px;
        line-height:30px;
        text-align:center;
    }
    #checkimg img{
        width:78px;
        height:30px;
    }
    #username,#pwd,#check{
        float:left;
        width:100px;
        height:30px;
        line-height:30px;
        text-align:center;
        background:black;
    }
    #checkwrap input{
        height:30px;
        width:100px;
        background:transparent;
        color:red;
        border:none;
        font-size:18px;
    }
    #usernamewrap input,#pwdwrap input{
        height:30px;
        width:120px;
        background:transparent;
        color:red;
        border:none;
        font-size:18px;
    }
    #submit{
        width:100px;
        height:50px;
        margin-top:20px;
        color:green;
        background:black;
        font-size:20px;
        border:1px solid green;

    }
    #register{
        color:red;
        margin-top:10px;
    }
    #error{
        color:red;
        margin-top:10px;
    }
</style>
</head>
 
<body>
<canvas id="canvas"></canvas>
<div id='formwrap'>
    <form action="<?php __FILE__ ?>" method="post">
        <div id="usernamewrap"><div id="username">用户名:</div><input type="text" name="username"></div>
        <div id="pwdwrap"><div id="pwd">原密码:</div><input type="password" name="oldpwd"></div>
        <div id="pwdwrap"><div id="pwd">新密码:</div><input type="password" name="newpwd"></div>

        <div id="codeimg">
        <div id="checkwrap"><div id="check">验证码</div><input type="text" name="checkcode"></div>
        <div id="checkimg"><img src="lib/CheckCode.php" alt="Loading..." id="checkImg"></div>
        </div>
        
        <input type="submit" name="submit" value="登 录" id="submit">
    </form>
    <!-- <div id="register">没有账号？<a href="register.php">点击注册</a></div> -->
    <div id="error">
    <?php
        if(isset($errorInfo)){
            echo $errorInfo;
        }
    ?>
    </div>
</div>


<script type="text/javascript">
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
 
 
    canvas.height = window.innerHeight;
    canvas.width = window.innerWidth;
 
    var texts = '0123456789ABCEF'.split('');
 
    var fontSize = 16;
    var columns = canvas.width/fontSize;
    // 用于计算输出文字时坐标，所以长度即为列数
    var drops = [];
    //初始值
    for(var x = 0; x < columns; x++){
        drops[x] = 1;
    }
 
    function draw(){
        //让背景逐渐由透明到不透明
        ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        //文字颜色
        ctx.fillStyle = '#0F0';
        ctx.font = fontSize + 'px arial';
        //逐行输出文字
        for(var i = 0; i < drops.length; i++){
            var text = texts[Math.floor(Math.random()*texts.length)];
            ctx.fillText(text, i*fontSize, drops[i]*fontSize);
 
            if(drops[i]*fontSize > canvas.height || Math.random() > 0.95){
                drops[i] = 0;
            }
 
            drops[i]++;
        }
    }
 
    setInterval(draw, 33);

    var OcheckImg=document.getElementById("checkImg");
    OcheckImg.onclick=function(){
        OcheckImg.src="lib/CheckCode.php?id="+(new Date()).getTime();
    }
</script>
</body>