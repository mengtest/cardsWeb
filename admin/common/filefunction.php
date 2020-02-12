<?php
function read($path){
    $file = fopen($path, "r");
    $user=array();
    $i=0;
//输出文本中所有的行，直到文件结束为止。
    while(! feof($file))
    {
        $user[$i]= trim(fgets($file));//fgets()函数从文件指针中读取一行
        $i++;
    }
    fclose($file);
    $user=array_filter($user);
    return $user;
}
function sum_hero($hero_name){
    require_once "lib/db/Db.php";
    require  "config/database.php";
    
    $db1 = Db::getInstance($config);
    $hero_name=trim($hero_name);
    $hero_name='英雄:'.$hero_name;
    $where="hero1='$hero_name' or hero2='$hero_name' or hero3='$hero_name' OR hero4='$hero_name' OR hero5='$hero_name'";
    $hero_count=$db1->getCount('user',['*'],$where);
    return $hero_count;
}
?>