<?php
if(isset($_POST['submit'])){
    require_once 'admin/lib/db/Db.php';
    require_once 'admin/config/database.php';
    // var_dump($_POST);
    array_shift($_POST);
    $arr = $_POST;
    // var_dump($arr);
    $num=1;
    $db1 = Db::getInstance($config);
    $field = ['account','hero1','hero2','hero3'];
    $fiveMatch=[];
    $fourMatch=[];
    $threeMatch=[];
    $twoMatch=[];
    $oneMatch=[];
     // 查询5者完全匹配
     foreach($arr as $hero1){
        foreach($arr as $hero2){
            foreach($arr as $hero3){
                foreach($arr as $hero4){
                    foreach($arr as $hero5){
                        $where = "hero1='英雄:$hero1' and hero2='英雄:$hero2' and hero3='英雄:$hero3 and hero4='英雄:$hero4'and hero5='英雄:$hero5'";
                        $result = $db1->getAll('user',$field,$where);
                        if(!empty($result)){
                            // echo '<pre>';
                            // var_dump($result);
                            // echo '</pre>';
                            array_push($fiveMatch,$result);
                        }
                    }
            }
            }
        }
    }
     // 查询4者完全匹配
     foreach($arr as $hero1){
        foreach($arr as $hero2){
            foreach($arr as $hero3){
                foreach($arr as $hero4){
                $where = "hero1='英雄:$hero1' and hero2='英雄:$hero2' and hero3='英雄:$hero3 and hero4='英雄:$hero4'";
                $result = $db1->getAll('user',$field,$where);
                if(!empty($result)){
                    // echo '<pre>';
                    // var_dump($result);
                    // echo '</pre>';
                    array_push($fourMatch,$result);
                }
            }
            }
        }
    }
    // 查询3者完全匹配
    foreach($arr as $hero1){
        foreach($arr as $hero2){
            foreach($arr as $hero3){
                $where = "hero1='英雄:$hero1' and hero2='英雄:$hero2' and hero3='英雄:$hero3'";

                $result = $db1->getAll('user',$field,$where);
                if(!empty($result)){
                    // echo '<pre>';
                    // var_dump($result);
                    // echo '</pre>';
                    array_push($threeMatch,$result);
                }
                
            }
        }
    }
    // 查询2者完全匹配
    foreach($arr as $hero1){
        foreach($arr as $hero2){
            $where = "hero1='英雄:$hero1' and hero2='英雄:$hero2'";
            $result = $db1->getAll('user',$field,$where);
            if(!empty($result)){
                // echo '<pre>';
                // var_dump($result);
                // echo '</pre>';
                array_push($twoMatch,$result);
            }
        }
    }
    //查询1者完全匹配
    foreach($arr as $hero1){
        $where = "hero1='英雄:$hero1'";
        $result = $db1->getAll('user',$field,$where);
        if(!empty($result)){
            // echo '<pre>';
            // var_dump($result);
            // echo '</pre>';
            array_push($oneMatch,$result);
        }
    }
//     echo '<pre>';
//     print_r($oneMatch);
//    echo '</pre>';
    
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查询结果</title>
    <link rel="stylesheet" href="style.css">
    <style>
    h3{
    margin-top:10px;
    margin-bottom:10px;
    }
    #back{
        width:50%;
        margin:0 auto;
        background:#2bbc8a;
        color:white;
        border-radius:10px;
        text-align:center;      
    }
   
    section{
        width:50%;
        margin:0 auto;
        text-align:center;
    }
    table{
        width:50%;
        margin:0 auto;
        background:#435a6b;
    }

    thead{
        background:#2bbc8a;
        border:none;
    }
    thead th{
        color:white;
        border:none;
        outline:none;
    }
@media screen and (max-width: 800px) {
    section{
        width:100%;
    }
    table{
        width:80%;
    }
    td img{
        width:60px;
        height:80px;
    }
    td{
        font-size:10px;
    }
}

    </style>

</head>
<body>
<header>查询结果</header>
<nav>
<div id="back"><a style="text-decoration: none;color:white;"href="index.php">返回主页</a></div>
</nav>
<article>
<section>
    <div><h3 style="margin-bottom:10px;">五项匹配</h3></div>
    <table >
         <thead>
             <tr>
                 <th>编号</th>
                 <th colspan="5">角色卡</th>
             </tr>
         </thead>
         <tbody>
             <?php foreach($fiveMatch as $arr){
                 foreach($arr as $v){?>
             <tr>
                <td><?php echo $v['account'];?></td>
                <td><img src="img/<?php echo ltrim($v['hero1'],'英雄:');?>.jpg" alt="<?php echo $v['hero1'];?>"></td>
                <?php if(!empty($v['hero2'])){?>
                <td><img src="img/<?php echo ltrim($v['hero2'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero2'],'英雄:');?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero3'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero3'],'英雄:');?>.jpg" alt="<?php echo $v['hero3'];?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero4'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero4'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero4'],'英雄:');?>"></td>
                <?php } ?>

                <?php if(!empty($v['hero5'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero5'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero5'],'英雄:');?>"></td>
                <?php } ?>
             </tr>
             <?php }} ?>
         </tbody>
    </table>
</section>
<section>
    <div><h3 style="margin-bottom:10px;">四项匹配</h3></div>
    <table >
         <thead>
             <tr>
                 <th>编号</th>
                 <th colspan="5">角色卡</th>
             </tr>
         </thead>
         <tbody>
             <?php foreach($fourMatch as $arr){
                 foreach($arr as $v){?>
             <tr>
                <td><?php echo $v['account'];?></td>
                <td><img src="img/<?php echo ltrim($v['hero1'],'英雄:');?>.jpg" alt="<?php echo $v['hero1'];?>"></td>
                <?php if(!empty($v['hero2'])){?>
                <td><img src="img/<?php echo ltrim($v['hero2'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero2'],'英雄:');?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero3'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero3'],'英雄:');?>.jpg" alt="<?php echo $v['hero3'];?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero4'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero4'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero4'],'英雄:');?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero5'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero5'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero5'],'英雄:');?>"></td>
                <?php } ?>
             </tr>
             <?php }} ?>
         </tbody>
    </table>
</section>
<section>
    <div><h3 style="margin-bottom:10px;">三项匹配</h3></div>
    <table >
         <thead>
             <tr>
                 <th>编号</th>
                 <th colspan="5">角色卡</th>
             </tr>
         </thead>
         <tbody>
             <?php foreach($threeMatch as $arr){
                 foreach($arr as $v){?>
             <tr>
                <td><?php echo $v['account'];?></td>
                <td><img src="img/<?php echo ltrim($v['hero1'],'英雄:');?>.jpg" alt="<?php echo $v['hero1'];?>"></td>
                <?php if(!empty($v['hero2'])){?>
                <td><img src="img/<?php echo ltrim($v['hero2'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero2'],'英雄:');?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero3'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero3'],'英雄:');?>.jpg" alt="<?php echo $v['hero3'];?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero4'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero4'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero4'],'英雄:');?>"></td>
                <?php } ?>

                <?php if(!empty($v['hero5'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero5'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero5'],'英雄:');?>"></td>
                <?php } ?>
             </tr>
             <?php }} ?>
         </tbody>
    </table>
    </section>
    <section>
    <div><h3>两项匹配</h3></div>
    <table>
         <thead>
             <tr>
                 <th>编号</th>
                 <th colspan="5">角色卡</th>
             </tr>
         </thead>
         <tbody>
             <?php foreach($twoMatch as $arr){
                 foreach($arr as $v){?>
             <tr>
                <td><?php echo $v['account'];?></td>
                <td><img src="img/<?php echo ltrim($v['hero1'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero1'],'英雄:');?>"></td>
                <?php if(!empty($v['hero2'])){?>
                <td><img src="img/<?php echo ltrim($v['hero2'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero2'],'英雄:');?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero3'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero3'],'英雄:');?>.jpg" alt="<?php echo $v['hero3'];?>"></td>
                <?php } ?>
                <?php if(!empty($v['hero4'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero4'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero4'],'英雄:');?>"></td>
                <?php } ?>

                <?php if(!empty($v['hero5'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero5'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero5'],'英雄:');?>"></td>
                <?php } ?>
             </tr>
             <?php }} ?>
         </tbody>
    </table>
    </section>
    <section>
    <div><h3>一项匹配</h3></div>
    <table>
         <thead>
             <tr>
                 <th>编号</th>
                 <th colspan="5">角色卡</th>
             </tr>
         </thead>
         <tbody>
             <?php foreach($oneMatch as $arr){
                 foreach($arr as $v ){?>
             <tr>
                <td><?php echo $v['account'];?></td>
                <td><img src="img/<?php echo ltrim($v['hero1'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero1'],'英雄:');?>"></td>
                
                <?php if(!empty($v['hero2'])){?>
                <td><img src="img/<?php echo ltrim($v['hero2'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero2'],'英雄:');?>"></td>
                <?php } ?>

                <?php if(!empty($v['hero3'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero3'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero3'],'英雄:');?>"></td>
                <?php } ?>

                <?php if(!empty($v['hero4'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero4'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero4'],'英雄:');?>"></td>
                <?php } ?>

                <?php if(!empty($v['hero5'])){ ?>
                    <td><img src="img/<?php echo ltrim($v['hero5'],'英雄:');?>.jpg" alt="<?php echo ltrim($v['hero5'],'英雄:');?>"></td>
                <?php } ?>
             </tr>
             <?php }} ?>
         </tbody>
    </table>
    </section>
   
</article>
</body>
</html>