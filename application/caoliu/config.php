<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/28
 * Time: 14:22
 */
use webDataMining\RunTime;
use webDataMining\CommandLineTools;

// 针对浏览器输出设定字符编码
if (php_sapi_name() !== 'cli'){
    header("Content-type: text/html; charset=utf-8");
    //header('Content-Type: text/plain');
}

// 定义应用常量和变量
error_reporting(E_ERROR);
define('APP_PATH', dirname(__FILE__));
define('ROOT_PATH', dirname(dirname(APP_PATH)));

// 载入配置文件、类库
include ROOT_PATH . '/vendor/autoload.php';
include ROOT_PATH . '/configs/user_agent.php';

// 初始化接下来要用到的一些类
$runtime = new runtime();
$cmd = new CommandLineTools();

// mysql 初始化
try{
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=caoliu;charset=utf8', 'root', '881224');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error ){
    $cmd->alert("PDO连接出错：" . $error->getMessage());
    exit;
}

// 重点，设置采集的入口链接和起止页码
$list_url = 'http://t66y.com/thread0806.php?fid=16&search=&page=';
$start_page = 1;
$end_page = 1;