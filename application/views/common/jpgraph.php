<?php
require_once ('jpgraph.php');
require_once ('jpgraph_line.php');
 
//Y轴数据
$ydata = array(11,3,8,12,5,1,9,13,5,7);
 
//设置图像大小
$width=900;
$height=400;
 
//初始化jpgraph并创建画布
$graph = new Graph($width,$height);
$graph->SetScale('intlin');
 
//设置左右上下距离
$graph->SetMargin(40,20,20,40);
//设置大标题
$graph->title->SetFont(FF_SIMSUN,FS_BOLD);
$title = "电池显示";
$title = iconv("UTF-8", "gb2312", $title);
$graph->title->Set($title);
//设置小标题
//$graph->subtitle->Set('(March 12, 2008)');
//设置x轴title
$graph->xaxis->title->setFont(FF_SIMSUN,FS_BOLD);
$xTitle = "时间";
$xTitle = iconv("UTF-8", "gb2312", $xTitle);
$graph->xaxis->title->Set($xTitle);
//设置y轴title
$graph->yaxis->title->setFont(FF_SIMSUN,FS_BOLD);
$yTitle = "电压";
$yTitle = iconv("UTF-8", "gb2312", $yTitle);
$graph->yaxis->title->Set($yTitle);
//设置x轴的值
$label_x  = array('0','1','2','3','4','5','6','7','8','9','10','11','12');
$graph->xaxis->SetTickLabels($label_x);
 
 
//实例化一个折线图的类并放入数据
$lineplot=new LinePlot($ydata);
//将折线图放入jpgraph
$graph->Add($lineplot);
 
//显示到浏览器
$graph->Stroke("jpgraph/1.png");
?>
