<?php


$infiniti = 'yes';
$sleep = 10;

while ($infiniti = 'yes'){

    // 遍历文件夹下的文件
    if ($handle = opendir('.')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                // 把文件名存进数组
                $file_array[] = $file;
            }
        }
        closedir($handle);
    }

    // 当前已经下载的文件数量
    $now_quantity = count($file_array);

    // 输出文件数量
    echo '当前文件数量：' . $now_quantity;

    for ($i = $sleep; $i > 0 ; $i--){
        echo '.';
        sleep(1);
    }

    // 释放计数数组，下个循环重新计算
    unset($file_array);
    passthru('clear');
}


