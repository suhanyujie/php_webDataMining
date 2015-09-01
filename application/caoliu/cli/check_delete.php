<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/30
 * Time: 12:20
 * 检测文件有效性
 */

include '../config.php';

// 小于等于此尺寸的文件都会被删除，1kb = 1024byte
$ex_size = 20480;


$infiniti = 'yes';
$sleep = 10;

while ($infiniti = 'yes') {

    // 遍历文件夹下的文件
    if ($handle = opendir(APP_PATH . '/downloads')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                // 把文件名存进数组
                $file_array[] = $file;
            }
        }
        closedir($handle);
    }

    // 根据上面得到的文件数组遍历单个文件，尺寸符合要求的就删除文件
    $need_delete = array();
    $delete_files = array();

    foreach ($file_array as $file) {
        $real_file = APP_PATH . '/downloads/' . $file;

        $file_size = filesize($real_file);
        if ($file_size <= $ex_size) {
            $cmd->alert($file . ' -> ' . $file_size . 'byte ' . '符合要求！');
            $need_delete[] = $file;
            unlink($real_file);

            if (!file_exists($real_file)) {
                $delete_files[] = $file;
                $cmd->alert($file . '删除成功！');
            }
        }
    }

    // 输出结果
    $cmd->alert('符合要求的文件数量：' . count($need_delete));
    $cmd->alert('删除成功的文件数量：' . count($delete_files));

    // 暂停
    for ($i = $sleep; $i > 0 ; $i--){
        echo '.';
        sleep(1);
    }

    unset($file_array);
    unset($need_delete);
    unset($delete_files);
    passthru('clear');
}

