<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/28
 * Time: 14:19
 * 页面功能：查询已经采集入库的主题，遍历主题获得图片真实url存进数据库
 */

include '../config.php';

// 查询出已经采集到的所有主题链接
$threads = $pdo->query("SELECT id, url from test_thread");
$threads = $threads->fetchAll();
$thread_quantity = count($threads);

// 多进程遍历主题
$workers = 10;
$pids = array();
for ($i = 0; $i < $workers; $i++) {
    $pids[$i] = pcntl_fork(); // 创建子进程

    switch ($pids[$i]) {
        case -1:
            $cmd->alert('创建子进程失败：' . $i);
            exit;

        case 0;

            $key_start = $thread_quantity / $workers * $i;
            $key_end   = $thread_quantity / $workers * ($i + 1);

            for ($j = $key_start; $j < $key_end; $j++){

                // 初始化curl
                $thread_handle = curl_init();

                // curl配置参数
                $options = [
                    CURLOPT_URL => $threads[$j]['url'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERAGENT => $cmd->rand_user_agent(),
                ];
                curl_setopt_array($thread_handle, $options);

                // 执行curl
                $thread_content = curl_exec($thread_handle);

                if (curl_errno($thread_handle)) {
                    $cmd->beep();
                    $cmd->alert('curl出错:' . curl_error($thread_handle));
                    $cmd->alert('出错的URL是：' . $threads[$j]['url']);
                } else {
                    curl_close($thread_handle);
                }

                $html = SimpleHtmlDom\str_get_html($thread_content);
                // 查找正文中所有的图片url并写入数据库
                if (is_object($html)) {
                    foreach ($html->find('input') as $element) {
                        if ($element->src != '') {
                            $cmd->alert($element->src);
                            // 写入数据库
                            try{
                                $pdo = new PDO('mysql:host=127.0.0.1;dbname=caoliu;charset=utf8', 'root', '881224');
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->prepare("insert into test_img_url (thread_id, url) VALUES (:thread_id, :url)");
                                $stmt->bindParam(':thread_id', $threads[$j]['id']);
                                $stmt->bindParam(':url', $element->src);
                                $stmt->execute();
                            }catch (PDOException $error){
                                $cmd->alert($error->getMessage());
                            }
                        }

                    }
                }

                // 释放系统资源
                $html->clear();
                unset($html);
            }
            exit;

        default;
            break;
    }

}
