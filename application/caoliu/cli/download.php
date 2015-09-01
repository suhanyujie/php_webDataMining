<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/28
 * Time: 14:19
 * 根据上一步获得到的图片链接下载图片到本地
 */

include '../config.php';

$imgs = $pdo->query("select url from test_img_url");
$result = $imgs->fetchAll();
$img_quantity = count($result);

$workers = 10;
$pids = array();
for ($i = 0; $i < $workers; $i++) {
    $pids[$i] = pcntl_fork(); // 创建子进程

    switch ($pids[$i]) {
        case -1:
            $cmd->alert('创建子进程失败：' . $i);
            exit;

        case 0;

            $key_start = $img_quantity / $workers * $i;
            $key_end   = $img_quantity / $workers * ($i + 1);

            for ($j = $key_start; $j < $key_end; $j++){
                $img_name = basename($result[$j]['url']);
                $file = APP_PATH . "/downloads/" . $img_name;
                // 下载图片
                $runtime->start();
                $opts = [
                    'http' => [
                        'method' => 'GET',
                        'header' => "Accept-language: en\r\n" . "Referer: " . $result[$j]['url'] . "\r\n"
                    ]
                ];
                $context = stream_context_create($opts);
                $data = file_get_contents($result[$j]['url'], false, $context);

                file_put_contents($file, $data);
                $runtime->stop();

                if (file_exists($file)) {
                    $cmd->alert('[' . $img_name . '] ' . '下载成功，' . '耗时：' . $runtime->spent() . '秒');
                }
            }
            exit;

        default;
            break;
    }

}
