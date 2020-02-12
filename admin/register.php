<?php
session_start();
require_once "./lib/db/Db.php"; 
require_once 'config/adminData.php';

  //验证用户提交的信息
function checkUser($username,$pwd,$checkcode){
    $namePattern = " /^\w{3,8}$/ ";
    $pwdPattern =" /^\w{8,16}$/ ";
    if(!strtolower($checkcode) == strtolower($_SESSION['code'])){
        return "你输入的验证码不正确";
    }
    $nameRes = preg_match($namePattern,$username);
    if(!$nameRes){
        return  "请输入3-8位的数字、字母、下划线作为用户名";
    }
    $pwdRes=preg_match($pwdPattern,$pwd);
    if(!$pwdRes){
        return "请输入8-16位的数字、字母、下划线作为密码";
    }
    return "succeed!";
  }

  if(!empty($_POST)){
    $username = htmlspecialchars($_POST['username']);
    $pwd = htmlspecialchars($_POST['pwd']);
    $email =htmlspecialchars($_POST['email']);
    $checkcode = htmlspecialchars($_POST['checkcode']);
    
    $ret = checkUser($username,$pwd,$checkcode);
    
    if($ret == "succeed!"){
        // echo "注册成功";  
     
        $db1 = Db::getInstance($config);
        $data = ['username'=>$username,'reg_time'=>time(),'pwd'=>md5(md5($pwd)),'user_email'=>$email];
        $ret = $db1->insertData('user',$data);
        if($ret){
            $_SESSION['username2']=$username;
            $errorInfo="注册成功!";
            header("refresh:3;url='index.php'");
        }else{
            $errorInfo="注册失败!";
        }
    }else{
        $errorInfo = $ret;
    }
 }
   
   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>添加管理账号</title>
    <style>
    *{
        margin:0;
        padding:0;
    }
    html{
        height:100%;
        width:100%;
    }
    body{
        background:black;
        color:green;
        width:100%;
        height:100%;
    }
    #bigDiv{
        width:600px;
        height:400px;
        margin-left:auto;
        margin-right:auto;
        margin-top:15%;
        margin-bottom:15px;
        background:transparent;
        overflow: hidden;
        border:1px solid green;
    }
    #erroInfo{
        width:600px;
        height:300px;
        margin:0 auto;
        color:red;
    }
    
    #regwrap{
        float:left;
        border:1px solid green;
        width:100%;
        height:50px;
        line-height:50px;
        font-size:17px;
    }
    #title{
        margin-left:5%;
    }
    #leftwrap{
        float:left;
        width:60%;
        height:346px;
        border:1px solid green;
        text-align: center;
    }
    #rightwrap{
        float:left;
        width:236px;
        height:87%;
        line-height: 87%;
        text-align:center;
        border:1px solid green;
   
    }
    #rightwrap div{
        margin-top:40px;
    }
    li{
        list-style: none;
        border:1px solid green;
    }
   
    #formtop li{ 
        width:70%;
        height:33px;
        line-height:33px;
        margin-top:10px;
        margin-left:auto;
        margin-right:auto;    
    }
    #formtop li label{
        position:relative;
        width:100%;
        height:100%;
        display:block;
    }
    
    #formtop input{
        position: absolute;
        top:0;
        right:0;
        height:100%;
        line-height: 100%;
        width:70%;
        color:red;
        font-size:15px;
        border:none;
        background: transparent;
    }
    #formtop div{
        position:absolute;
        top:0;
        left:0;
        width:70px;
        height:33px;
        line-height:33px;
        text-align: center;
        display: inline-block;
        
    }
    #formbottom{
        overflow: hidden;
        width:70%;
        margin:30px auto;
    }
    #formbottom li{
        float:left;
        width:175px;
        height:33px;
        line-height:33px;
        margin-right:10px;

    }
    #formbottom img{
        width:175px;
        height:33px; 
    }
    #checkcode{
        width:70px;
        display:inline-block;
    }
    #checkinput{
        width:100px;
        height:33px;
        line-height:33px;
        background:transparent;
        border:none;
        color:red;
    }
    #submit{
        width: 100px;
        height:33px;
        line-height: 33px;
        margin:0 auto;
    }
    #submit input{
        position:static;
        border:none;
        color:red;
        background: transparent;
        font-size:20px;
        
    }
   
    </style>
</head>
<body>
    <div id="bigDiv">
        <div id="regwrap"><h2 id="title">添加管理账号</h2></div>
        <div id="leftwrap">
            <form action="<?php __FILE__?>" method="post">
                <ul id="formtop">
                    <li>
                        <label><div id="username">用户名 |</div><input type="text" name="username" placeholder="3-8位数字及字母" id=""></label>
                    </li>
                    <li>
                            <label>
                                    <div id="pwd">密   码  |</div>
                                    <input type="password" name="pwd" placeholder="输入8-16位密码" id="">
                            </label>
                    </li>
                    <li>
                            <label>
                                    <div id="email">邮   箱  |</div>
                                    <input type="email" name="email" id="">
                            </label>
                    </li>
                </ul>
                <ul id ="formbottom" >
                    <li>
                        <label>
                            <div id="checkcode">验证码  |</div><input type="text" name="checkcode" placeholder="不区分大小写" id="checkinput">
                        </label>
                    </li>
                    <li>
                        <img src="lib/CheckCode.php" alt="loading..." title="点击刷新" id="checkImg">
                    </li>
                </ul>
                <li id="submit"><input type="submit" value="注册"></li>
            </form>
        </div>
        <div id="rightwrap">
            <div>已有账号？</div>
            <div><a href="login.php">登录</a></div>
        </div>
    </div>
    <?php
    if(isset($errorInfo)){
        echo "<div id='erroInfo'>$errorInfo</div>";
    }
   ?>
    
    <script>
        window.onload=function(){
            var OcheckImg=document.getElementById("checkImg");
            OcheckImg.onclick=function(){
                OcheckImg.src="lib/CheckCode.php?id="+(new Date()).getTime();
            }
        }
    </script>
    
</body>
</html>