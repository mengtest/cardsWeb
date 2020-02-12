<?php
session_start();
if(isset($_GET['action']) && $_GET['action'] == 'logout'){
    unset($_SESSION['username2']);
}
if(!isset($_SESSION['username2'])){
    header("Location:login.php");
}
require_once "lib/db/Db.php";
require_once "config/database.php";
$db1 = Db::getInstance($config);
// 添加数据
if(isset($_POST['insert'])){
    $account = htmlspecialchars($_POST['account']);
    // $pwd = md5(htmlspecialchars($_POST['pwd']));
    $pwd = htmlspecialchars($_POST['pwd']);
    $email = htmlspecialchars($_POST['email']);
    // $emailpwd = md5(htmlspecialchars($_POST['emailpwd']));
    $emailpwd = htmlspecialchars($_POST['emailpwd']);
    $hero1= htmlspecialchars($_POST['hero1']);
    $hero2 =htmlspecialchars($_POST['hero2']);
    $hero3 = htmlspecialchars($_POST['hero3']);
    $hero4 =htmlspecialchars($_POST['hero2']);
    $hero5 = htmlspecialchars($_POST['hero3']);
    //查重
    $table = 'user';
    $field =['*'];
    $where="account='$account' and pwd='$pwd' and email='$email' and emailpwd='$emailpwd' and hero1='$hero1' and hero2='$hero2' and hero3='$hero3' and hero4='$hero4' and hero5='$hero5'";
    $count = $db1->getCount($table,$field,$where);
    if($count==0){
        //添加数据
        $data = ['account'=>$account,'pwd'=>$pwd,'email'=>$email,'emailpwd'=>$emailpwd,'hero1'=>$hero1,'hero2'=>$hero2,'hero3'=>$hero3,'hero4'=>$hero4,'hero5'=>$hero5];
        $ret = $db1->insertData($table,$data);
        if($ret){
            $alert ="插入成功";
        }else{
            $alert ="插入失败";
        }
    }else{
        $alert="该数据已经存入数据库";
    }
   
}
//添加文本数据
if(isset($_POST['file_submit'])){
    require_once "lib/pictureupload/upload.php";
    $fileInfo = $_FILES['file'];
    $upload = new Upload($fileInfo);
    // var_dump($fileInfo);
    // exit;
    if($fileInfo['error']!=0){
        exit("上传文件有问题");
    }
    //验证文件的扩展名
    $ext = pathInfo($fileInfo['name'],PATHINFO_EXTENSION);
    $allow_ext = ['txt'];//白名单
    if(!in_array($ext,$allow_ext)){
        exit('该类型文件不可以上传,请上传txt类型的文件');}
    //验证文件的大小
    $allow_size = 1024*1024 * 10;//单位字节 10MB
    if($fileInfo['size'] > $allow_size){
        exit("文件大小超过10MB,无法上传");
    }
    //创建上传目录
    $dir = 'uploads';

    //$sub_dir = $dir.'/'.date('Y-m-d');
    $sub_dir = $dir.DIRECTORY_SEPARATOR.date('Y-m-d');

    if(!is_dir($dir)){
        mkdir($dir);
    }
    if(!is_dir($sub_dir)){
        mkdir($sub_dir);
    }
    //$img_name = date('YmsHis').rand(1000,9999).'.'.$ext;
    $file_name = date('YmsHis').rand(1000,9999).'.'.$ext;
    $full_file_name= $sub_dir.DIRECTORY_SEPARATOR.$file_name;
    move_uploaded_file($fileInfo['tmp_name'],$full_file_name);
    require_once 'common/filefunction.php';
    // var_dump(read($full_file_name));
    $lines = read($full_file_name);
    $file_alert='';
    foreach($lines as $line){
        $arr = explode('----',$line);
        $heros = explode('/',array_pop($arr));
        @$values = array_merge($arr,$heros);
        // $values[1]=md5($values[1]);
        // $values[3]=md5($values[3]);
        while(count($values) < 9){
            array_push($values,'');
        }
        $keys =['account','pwd','email','emailpwd','hero1','hero2','hero3','hero4','hero5'];
        @$data = array_combine($keys,$values);
        $table="user";
        // 查重
        $field =['*'];
        $where="account='$data[account]' and pwd='$data[pwd]' and email='$data[email]' and emailpwd='$data[emailpwd]' and hero1='$data[hero1]' and hero2='$data[hero2]' and hero3='$data[hero3]' and hero4='$data[hero4]' and hero5='$data[hero5]'";
        $count = $db1->getCount($table,$field,$where);
         //添加数据
        if($count==0){
            @$ret = $db1->insertData($table,$data);
            if($ret){
                $file_alert.="账户$data[account] 插入成功<br/>";
            }else{
                $file_alert.="账户$data[account] 插入失败<br/>";
            }
        }else{
            $file_alert.="账户$data[account] 已经存在,已经自动跳过<br/>";
        }
    }
}
//删除数据
if(isset($_POST['delete'])){
    $key=htmlspecialchars($_POST['key']);
    $value =htmlspecialchars($_POST['value']);
    $operator=$_POST['operator'];
    $table='user';
    $where=$key.$operator.$value;
    $ret = $db1->deleteData($table,$where);
    if($ret){
        $delete_alert = '删除成功';
    }else{
        $delete_alert ='删除失败';
    }

}
//批量删除数据
if(isset($_POST['select_submit'])){
    // var_dump($_POST);
    $arr = $_POST;
    array_pop($arr);
    // var_dump($arr);
    $string ='';
    foreach($arr as $id){
        $where='id='.$id;
        $ret = $db1->deleteData('user',$where);  
        if($ret){
            $string .= $id.'删除成功'.'</br>';
            
        }else{
            $string .=$id.'删除失败'.'</br>';
        }
    }
    // echo $string;
}
//统计英雄
if(isset($_POST['hero_search'])){
    require_once 'common/filefunction.php';
    $hero_name = htmlspecialchars($_POST['hero_name']);
    $hero_count = sum_hero($hero_name);
}
//统计所有英雄
if(isset($_POST['all_hero'])){
    require_once 'common/filefunction.php';
    require_once 'config/herolist.php';
    $hero_sum=[];
    foreach($hero_list as $hero_name){
        $hero_count = sum_hero($hero_name);
        array_push($hero_sum,$hero_count);
    }
    $hero_table =array_combine($hero_list,$hero_sum);
}
// 查询数据
if(isset($_POST['search'])){
    $key=htmlspecialchars($_POST['key']);
    $value =htmlspecialchars($_POST['value']);
    $operator=$_POST['operator'];
    $table='user';
    $field =['*'];
    $where=$key.$operator.$value;
    $info = $db1->getAll($table,$field,$where);
    if(empty($info)){
        $s_message="没有查到数据";
    }
    // print_r($info);
    // echo empty($info);
}
if(isset($_POST['all_data'])){
  //查询所有数据并显示
  require_once "lib/pagealloc/Page.php";
  $field=["id"];
  $total = $db1->infoCount();
  $size = 30;//每页显示的条数
  $cur  = isset($_GET['page'])?$_GET['page']:1;
  $from = ($cur-1) * $size;
  $message = $db1->getAllInfo($cur,$size);
  $page = new Page($total,$size,$cur);
}
//查询所有数据并显示
require_once "lib/pagealloc/Page.php";
$field=["id"];
$total = $db1->infoCount();
$size = 30;//每页显示的条数
$cur  = isset($_GET['page'])?$_GET['page']:1;
$from = ($cur-1) * $size;
$message = $db1->getAllInfo($cur,$size);
$page = new Page($total,$size,$cur);
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>简易后台管理系统</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="mainDiv">
    <header>
        <img src="" alt="">
        <div id="title">简易后台管理系统</div>
        <nav id="leftnav">
            <ul>
                <li><a href="../index.php">网站首页</a></li>
                <li><a href="register.php">添加管理账号</a></li>
            </ul>
        </nav>
        <nav id="rightnav">
            <ul>
                <?php
                // 判断是否已经登录
                if(!isset($_SESSION['username2'])){
                    echo '<li><a href="login.php">登录</a></li>';
                    echo '<li><a href="register.php">注册</a></li>';
                }else{
                    echo "<li><a href='user.php'>".$_SESSION['username2']."</a></li>";
                    echo '<li><a href="changepwd.php">修改密码</a></li>';
                    echo '<li><a href="index.php?action=logout">退出</a></li>';
                }
                ?>
               
            </ul>
        </nav>
    </header>
    <main>
        <!-- <aside>
            <h2 id="searchTitle">Search</h2>
            <form action="">
                 <input type="search" id="search">
                 <input type="" id ="searchButton" name="search" value="搜 索">
            </form>
        </aside> -->
        
        <article>
            <section id="insert">
                <div>添加数据</div>
                <form action="index.php" method="post">
                <table  border='1 solid green'>
                    <thead>
                        <tr>
                            <th>账户</th>
                            <th>密码</th>
                            <th>邮箱</th>
                            <th>邮箱密码</th>
                            <th>英雄1</th>
                            <th>英雄2</th>
                            <th>英雄3</th>
                            <th>英雄4</th>
                            <th>英雄5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="account" required></td>
                            <td><input type="password" name ="pwd" required></td>
                            <td><input type="email" name="email" required></td>
                            <td><input type="password" name="emailpwd" required></td>
                            <td><input type="text" name="hero1" placeholder='请带上前缀 英雄:(英文符号)'></td>
                            <td><input type="text" name="hero2" placeholder='请带上前缀 英雄:(英文符号)'></td>
                            <td><input type="text" name="hero3" placeholder='请带上前缀 英雄:(英文符号)'></td>
                            <td><input type="text" name="hero4" placeholder='请带上前缀 英雄:(英文符号)'></td>
                            <td><input type="text" name="hero5" placeholder='请带上前缀 英雄:(英文符号)'></td>
                        </tr>
                    </tbody>
                    
                </table>
                <input type="submit" name="insert" value="提交">
                </form>
                <div style="color:red"><?php if(isset($alert)){
                    echo $alert;
                }?></div>
            </section>
            <section>
                <div>添加文本数据</div>
                <form action="index.php?id=<?php echo time();?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="file">
                    <input type="submit" name="file_submit" value="上传文本">
                </form>
            </section>
            <section id="delete">
                <div>删除数据</div>
                <form action="index.php" method="post">
                    <table  border='1 solid green'>
                        <thead>
                            <tr>
                                <th>字段名</th>
                                <th>关系</th>
                                <th>值</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="key" placeholder="eg:id account" required></td>
                                <td><input type="text" name="operator" placeholder="eg:= > <"required></td>
                                <td><input type="text" name ="value" placeholder="eg:1 2 3"required></td>
                            </tr>
                        </tbody>
                        
                    </table>
                    <input type="submit" name="delete" value="提交">
                    </form>
                    <div style="color:red"><?php if(isset($delete_alert)){
                        echo $delete_alert;
                    }?></div>
                </section>
                <section id="search">
                    <div>查询数据</div>
                    <form action="index.php?id=<?php echo time();?>" method="post">
                    <table  border='1 solid green'>
                        <thead>
                            <tr>
                                <th>字段名</th>
                                <th>关系</th>
                                <th>值</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="key" required></td>
                                <td><input type="text" name="operator" placeholder="eg:= > <"required></td>
                                <td><input type="text" name ="value" placeholder="字符串请用单引号引起来" required></td>
                            </tr>
                        </tbody>
                        
                    </table>
                    <input type="submit" name="search" value="提交">
                    </form>
                    <div style="color:red"><?php if(isset($s_message)){
                        echo $s_message;
                    }?></div>
                </section>
                <!-- 统计英雄 -->
                <section id="search">
                    <div>统计英雄</div>
                    <form action="index.php" method="post">
                           英雄名:<input type="text" name ="hero_name" placeholder="请输入英雄名" required>       
                            <input type="submit" name="hero_search" value="统计">
                    </form>
                    <div style="color:red"><?php if(isset($s_message)){
                        echo $s_message;
                    }?></div>
                    <div style="color:red">
                        <?php if(isset($hero_count)){echo "$hero_name 总数为:$hero_count";}?>
                    </div>
                </section>
                <!-- 显示所有数据 -->
                <section>
                    <form action="index.php" method="post">     
                            <input type="submit" name="all_data" value="显示所有数据" style="width:200px;height:30px;font-size:20px;">
                    </form>
                </section>
                <!-- 统计所有英雄 -->
                <section>
                    <form action="index.php" method="post">     
                            <input type="submit" name="all_hero" value="统计所有英雄" style="width:200px;height:30px;font-size:20px;margin-top:10px;">
                    </form>
                    <div>
                        <?php if(!empty($hero_table)){
                            echo '<div style="margin:10px 0;"><h1>英雄数目统计表</h1></div>';
                            echo '<table border ="1px solid green">';
                            echo '<thead>';
                            echo '<tr>';
                            foreach(array_keys($hero_table) as $key){
                                echo "<th>$key</th>";
                            }
                            echo "<th>总英雄数</th>";
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            echo '<tr>';
                            foreach(array_values($hero_table) as $value){
                                echo "<th>$value</th>";
                            }
                            $sum = array_sum(array_values($hero_table));
                            echo "<th>$sum</th>";
                            echo '</tr>';
                            echo '</tbody>';
                            echo '</table>';
                            
                        }
                        ?>
                    </div>
                </section>
                <!-- 显示表 -->
                <section>
                <h2 id="articleTitle" style="margin:10px 0;">Table</h2>
                <form action="index.php" method="post">
                <table border='1px solid green'>
                    <thead>
                        <tr>
                            <th>选择</th>
                            <th>id</th>
                            <th>账户account</th>
                            <th>密码pwd</th>
                            <th>邮箱email</th>
                            <th>邮箱密码emailpwd</th>
                            <th>英雄1(hero1)</th>
                            <th>英雄2(hero2)</th>
                            <th>英雄3(hero3)</th>
                            <th>英雄4(hero4)</th>
                            <th>英雄5(hero5)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(!empty($info)){
                    foreach($info as $v){ ?>
                        <tr>
                            <label>
                            <td style="text-align:center;"><input type="checkbox" name="<?php echo $v['account'];?>" value="<?php echo $v['id'];?>"></td>
                            <td><?php echo $v['id'];?></td>
                            <td><?php echo $v['account'];?></td>
                            <td><?php echo $v['pwd'];?></td>
                            <td><?php echo $v['email'];?></td>
                            <td><?php echo $v['emailpwd'];?></td>
                            <td><?php echo $v['hero1'];?></td>
                            <td><?php echo $v['hero2'];?></td>
                            <td><?php echo $v['hero3'];?></td>
                            <td><?php echo $v['hero4'];?></td>
                            <td><?php echo $v['hero5'];?></td>
                            </label>
                        </tr>
                    <?php } 
                    }else{
                        foreach($message as $v){ 
                    ?>
                            <tr>
                                <td style="text-align:center;"><input type="checkbox" id ="<?php echo $v['id']?>" name="<?php echo $v['account'];?>" value=<?php echo $v['id'];?>></td>
                                
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['id'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['account'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['pwd'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['email'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['emailpwd'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['hero1'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['hero2'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['hero3'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['hero4'];?></label></td>
                                <td><label for="<?php echo $v['id']?>"><?php echo $v['hero5'];?></label></td>
                            </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <input type="submit" name="select_submit" value="批量删除" style="margin-top:10px;">
            </form>
            <div style="color:red" ><?php if(!empty($string)){echo $string;}?></div>
            <?php 
            echo '<div id="page">'.$page->showPage()."</div>"; 
            if(!empty($file_alert)){
                echo '<div style="color:red">'.$file_alert.'</div>';
            }
            }?>
            </section>
        </article>
    </main>
    <!-- <footer>
        <p>Copyright ©</p>
    </footer> -->
    </div>
</body>
</html>