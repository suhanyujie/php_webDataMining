<?php
namespace webDataMining;

/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/27
 * Time: 10:02
 * 若干帮助函数
 */
class CommandLineTools
{

    /**
     * @param $message
     * 一个方便的终端输出状态信息函数
     *
     */
    function alert($message)
    {
        echo "[" . date('Y-m-d H:i:s', time()) . "] " . $message . "\n";
    }

    /**
     * 终端发声音提醒
     */
    function beep()
    {
        echo "\x07";
    }

    /**
     * @return mixed
     * 用于在 curl 运行时随机生成 user agent
     */
    function rand_user_agent()
    {
        global $user_agent;
        $count = count($user_agent);
        $random = rand(0, $count);
        return $user_agent[$random];
    }

}


