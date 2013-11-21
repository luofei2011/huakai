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

#### 2013-11-21日更新

1.判断当前车辆是否已经更新过文件添加了数据库支持

    # 数据库名字
    huakai
    # 表名
    update_info
    # 需要修改`db_config.php`文件下的数据库连接信息
    vi huakai/script/server/db_config.php

2.优化服务器脚本log的显示方式
