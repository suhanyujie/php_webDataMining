<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/25
 * Time: 22:48
 */
include '../config.php';

for ($i = $start_page; $i <= $end_page; $i++) {

    // 初始化curl
    $list_handle = curl_init();

    // curl配置参数
    $options = [
        CURLOPT_URL => $list_url . $i,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => $cmd->rand_user_agent(), // 每次运行随机生成user_agent
    ];
    curl_setopt_array($list_handle, $options);

    // 执行curl
    $pageContent = curl_exec($list_handle);

    if (curl_errno($list_handle)) {
        $cmd->beep();
        $cmd->alert('curl出错:' . curl_error($list_handle));
        $cmd->alert('出错的URL是：' . $list_url . $i);
    } else {
        curl_close($list_handle);
        $cmd->alert("第 $i 列表页html内容获取成功！");
    }

    $html = SimpleHtmlDom\str_get_html($pageContent);
    $thread_lists = $html->find('tr[class=tr3 t_one]');
    foreach ($thread_lists as $thread_tr) {

        // 标题和链接地址
        foreach ($thread_tr->find('td h3 a') as $url) {
            $title = iconv('gb2312', 'utf-8//IGNORE', $url->innertext);
            $url = "http://t66y.com/" . $url->href;
        }
        // 回复数量
        foreach ($thread_tr->find('td[class=tal f10 y-style]') as $comment) {
            $comments = $comment->innertext;
        }
        // 作者
        foreach ($thread_tr->find('a[class=bl]') as $author) {
            $author = iconv('gb2312', 'utf-8', $author->innertext);
        }
        // 发表时间
        foreach ($thread_tr->find('a[class=f10]') as $create_time) {
            $create_time = $create_time->innertext;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO test_thread (title, author, comments, create_time, url) VALUES (:title, :author, :comments, :create_time, :url)");
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":author", $author);
            $stmt->bindParam(":comments", $comments);
            $stmt->bindParam(":create_time", $create_time);
            $stmt->bindParam(":url", $url);
            $stmt->execute();
        } catch (PDOException $error) {
            $cmd->alert($error->getMessage());
        }

    }

    if (is_array($thread_urls) && count($thread_urls) > 0) {
        $cmd->alert("第 $i 列表页主题帖链接获取完成!");
    }

    // 任务提示, 最后1页运行完成时提示
    if ($i == $end_page){
        $cmd->alert('主题列表采集完成！');
        //$next_step = 'get_img_url';
    }

    $html->clear();
    unset($html);
}








