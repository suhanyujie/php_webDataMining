<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/27
 * Time: 21:59
 * 多进程下载测试
 */

include '../function/function.php';
include '../library/simplehtmldom/simple_html_dom.php';
include '../library/runtime.class.php';

// 计算某段代码耗时
$runtime = new runtime();

$img_urls = [
    "http://s4.ihostimg.com/s4/20150823202814fuq4t.jpeg",
    "http://s4.ihostimg.com/s4/20150823202819rb8zv.jpeg",
    "http://s4.ihostimg.com/s4/20150823202823ghyic.jpeg",
    "http://s4.ihostimg.com/s4/20150823202828dj9dq.jpeg",
    "http://s4.ihostimg.com/s4/201508232028320u5sd.jpeg",
    "http://s4.ihostimg.com/s4/20150823202841aga9k.jpeg",
    "http://s4.ihostimg.com/s4/20150823202849ol12p.jpeg",
    "http://s4.ihostimg.com/s4/20150823202858wahlm.jpeg",
    "http://s4.ihostimg.com/s4/20150823202906gf5gp.jpeg",
    "http://s4.ihostimg.com/s4/201508232029105qa8c.jpeg",
    "http://s4.ihostimg.com/s4/20150823202923vnn71.jpeg",
    "http://s4.ihostimg.com/s4/20150823202930n446h.jpeg",
    "http://s4.ihostimg.com/s4/20150823202939pi11f.jpeg",
    "http://s4.ihostimg.com/s4/20150823202951o5bp5.jpeg",
    "http://s4.ihostimg.com/s4/20150823203003yq3ek.jpeg"
];


$url_quantity = count($img_urls);
$workers = 3;

$pids = array();
for ($i = 0; $i < $workers; $i++) {
    $pids[$i] = pcntl_fork(); // 创建子进程

    switch ($pids[$i]) {
        case -1:
            alert('创建子进程失败：' . $i);
            exit;

        case 0;
            $key_start = $url_quantity / $workers * $i;
            $key_end   = $url_quantity / $workers * ($i + 1);

            for ($j = $key_start; $j < $key_end; $j++){
                $img_name = basename($img_urls[$j]);
                $file = "../caoliu/download/" . $img_name;
                // 下载图片
                $runtime->start();
                $opts = [
                    'http' => [
                        'method' => 'GET',
                        'header' => "Accept-language: en\r\n" . "Referer: " . $img_urls[$j] . "\r\n"
                    ]
                ];
                $context = stream_context_create($opts);
                $data = file_get_contents($img_urls[$j], false, $context);

                file_put_contents($file, $data);
                $runtime->stop();

                if (file_exists($file)) {
                    alert('[' . $img_name . '] ' . '下载成功，' . '耗时：' . $runtime->spent() . '秒');
                }
            }
            exit;

        default;
            break;
    }

}
