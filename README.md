#### JpGraph安装

1.去官网下载最新版本的源码[jpgraph-3.5.0b1.tar.gz](http://jpgraph.net/download/)

2.解压缩到任意目录

    tar -zxvf jpgraph-3.5.0b1.tar.gz  # 我的安装在/home/luofei02/(即～/)目录下
    mv jpgraph-3.5.0b1 jpgraph

3.修改apache配置文件`php.ini`

    1. 启动GD库支持

    2. 修改include_path为以上jpgraph的解压缩目录, 如：/home/luofei02/jpgraph/

4.重启Apache服务器

    # 我的是lampp
    /opt/lampp/lampp restart

#### 使用

1.在`www`或者`htdocs`目录夏新建一个jpgraph.php文件，内容如下：

    <?php // content="text/plain; charset=utf-8"
    require_once ('src/jpgraph.php');
    require_once ('src/jpgraph_line.php');
     
    //Y轴数据
    $ydata = array(11,3,8,12,5,1,9,13,5,7);
     
    //设置图像大小
    $width=450;
    $height=250;
     
    //初始化jpgraph并创建画布
    $graph = new Graph($width,$height);
    $graph->SetScale('intlin');
     
    //设置左右上下距离
    $graph->SetMargin(40,20,20,40);
    //设置大标题
    $graph->title->Set('Calls per operator');
    //设置小标题
    $graph->subtitle->Set('(March 12, 2008)');
    //设置x轴title
    $graph->xaxis->title->Set('Operator');
    //设置y轴title
    $graph->yaxis->title->Set('# of calls');
    //设置x轴的值
    $label_x  = array('a','b','c','d','e','f','g','h','i','j');
    $graph->xaxis->SetTickLabels($label_x);
     
     
    //实例化一个折线图的类并放入数据
    $lineplot=new LinePlot($ydata);
    //将折线图放入jpgraph
    $graph->Add($lineplot);
     
    //显示到浏览器
    $graph->Stroke();
    ?>

2.访问查看效果：[localhost/jpgraph.php](http://localhost/jpgraph.php)

#### 解决中文乱码

1.网上下载`simhei.ttf`以及`simsun.ttc`字体

2.把以上字体放到`/usr/share/fonts/truetype/`下即可！

#### 2013-12-21日更新

1.添加管理员注册功能

    # 请手动执行根目录下的insert_admin_account.php脚本
    # 需要带三个参数
    /*
     * $argv[1] 用户名
     * $argv[2] 密码
     * $argv[3] 邮箱
     */
    $ php insert_admin_account.php luofei 000000 luofei@huakaienergy.com

2.添加管理员登录功能:

暂时未开放接口，需要从url跳转: [localhost/huakai/welcome/login](http://localhost/huakai/welcome/login)

需完善地方：登录状态下再访问登录地址时跳转到指定页面

3.管理员登录功能有充分的安全保证

1. cookie中存放了用户名和加密的token串(用于后面每次请求的身份验证)

2. 当通过管理员界面请求数据时，每次都会判断token是否有效(通过数据库实现，不易破解)

3. 当同一帐号在不同地方登录时，一方会被T下线并且有相应提示（待完善）

4.添加新闻发布平台

管理员能发布新闻，相应的数据表见news表和分类表category

5.完善每个页面title部分的动态显示，以及某些页面是否出顶部logo的功能

6.待完善地方：

1. 缺少管理员界面

2. 缺少新闻管理界面（编辑，修改，删除，下线）

3. 新闻目前只有发布功能，无单独的保存功能

#### 2013-11-21日更新

1.创建数据库及表

    # 数据库名字
    huakai
    # 表名
    update_info
    # 表的字段
    id # 记录id,无特别含义,自动增加!
    cid # 车辆SIM号, 特有的id, 设置为VARCHAR(20), 非空.
    version # 当前车辆的更新文件信息, 设置为VARCHAR(50), 非空.

2.使用数据库

    # 需要修改`db_config.php`文件下的数据库连接信息
    vi huakai/script/server/db_config.php

3.优化服务器脚本log的显示方式

    [LOG] ...
    [INFO] ...
    [WARN] ...
    [ERROR] ...
