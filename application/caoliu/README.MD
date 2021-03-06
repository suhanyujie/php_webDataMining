###配置 config.php 
mysql的连接信息
采集的入口链接地址和起止页码，游客只能访问1-100页

`$list_url = 'http://t66y.com/thread0806.php?fid=16&search=&page=';`

`$start_page = 1;`

`$end_page = 100;`

###执行顺序
1. 执行 `get_thread.php`, 会采集指定列表页主题的标题、作者、发表时间、回复数量、链接地址存到 thread 表
2. 执行 `get_img_url.php`, 会从数据库中取出上一步采集到的主题链接进行遍历帖子内容获得图片的真实URL存到 img_url 表
3. 执行 `download.php`, 会取出上一步采集获得的图片真实链接进行多线程下载，默认10个线程，足以跑满 4M 宽带。

###关于多线程
* `get_thread.php` 没有使用多线程，这一步需要分析的页面数量不多，也足够简单，单线程速度足以接受
* `get_img_url.php` 和 `download.php` 均采用了多线程模式，默认10个线程。可以通过修改各自文件的 $workers 变量修改线程数

###格外的两个小工具
1. `sum.php` 新开一个窗口运行，每隔10秒遍历一次 downloads 文件夹，计算所需下载的文件总数和已经下载的文件数，输出到命令行，
    建议与download.php 同时运行，比较直观
2. `check_delete.php` 新开一个窗口运行，每隔10秒遍历一次已经下载的文件，删除符合条件的文件同样 建议与download.php 同时运行，
    比较直观，也可以等全部文件下载完成后运行，方便清理挑选文件。因为会有一些链接失效或者其他原因导致的 0byte 的文件存在，用法不局限于此，
    可以根据需要自己定制
3. 这两个工具可以随时开启和停止，不会与影响主采集任务

###我的运行方式
开4个 terminal ,一个运行抓取任务，一个运行 `sum.php` , 一个运行`check_delete.php`, 还有一个运行 `top` 命令监控系统状态如下图：
    

###Library
1. simplehtmldom: 一个解析html的库。
2. phpquery: 另外一个解析html的库，据说很强大，还没开始使用
3. d3: D3.js库
4. pscws4: 一个中文分词库，还没应用到项目中