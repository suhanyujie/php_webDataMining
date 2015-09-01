<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/9/1
 * Time: 15:30
 */

include './config.php';

if(isset($_GET['search']) && $_GET['search'] !== ''){
    $search_word = $_GET['search'];
}else{
    echo "搜索关键词不能为空！";
    exit;
}


try {
    $stmt = $pdo->query("select * from thread where title like '%$search_word%' ");
    $result = $stmt->fetchAll();
}catch (PDOException $error){
    alert($error->getMessage());
}

$i = 1 ;
foreach ($result as $value){
    echo "<a href=\"$value[url]\">" . $i++ . "$value[title]</a><br>";
}





