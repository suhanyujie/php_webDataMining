<?php
/**
 * Created by IntelliJ IDEA.
 * User: lihuanpeng
 * Date: 15/8/27
 * Time: 19:15
 * 程序运行时间计算类，精确到毫秒
 */
namespace webDataMining;

class RunTime
{
    private $StartTime = 0;
    private $StopTime = 0;

    /**
     * 取程序开始时候的系统时间
     *
     */
    function start()
    {
        $this->StartTime = microtime(true);
    }
    /**
     * 取程序结束时候的系统时间
     *
     */
    function stop()
    {
        $this->StopTime = microtime(true);
    }
    /**
     * 计算出程序运行所用时间
     *
     * @return unknown
     */
    function spent()
    {
        // 开始时间减去结束时间得到运行的时间
        // return round(($this->StopTime - $this->StartTime) * 1000, 1); 毫秒
        return round(($this->StopTime - $this->StartTime), 2); // 秒
    }
}