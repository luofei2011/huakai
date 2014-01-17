<style type="text/css">
        ul, div, img, p, span{
                font-family: Times, Georgia, serif, sans-serif;
                margin: 0;
                padding: 0;
                border: 0;
        }
        .content ul li{
                list-style: none;
        }
        .vw_news{
                width: 1002px;
                margin: 15px auto;
        }
        .vw_loc{
                margin-top: 12px;
                height: 18px;
        }
        .vw_loc p{
                float: left;
                list-style:none;
                height: 20px;
        }
        .now_loc{
                text-indent: 1em;
                font-size: 14px;
                font-weight: bold;
                line-height: 18px;
        }
        .content{
                margin: 0;
                min-height: 100%;
                border: 1px solid #ccc;
        }
        .a_title{
                padding: 18px;
                text-align: center;
                font-size: 18px;
                font-weight: bold;
                line-height: 2em;
                color: #ff77ff;
        }
        .mes_nav{
                text-align: right;
                margin: 3px 10px 8px 10px;
                font-size: 12px;
                color: #7c7c7c;
        }
        .mes_nav a{
                text-decoration: none;
                color: #000;
        }
        .a_content{
                padding: 15px 20px;
                margin: 20px 0;
        }
        .a_content p{
                font-size: 14px;
                color: #555555;
                line-height: 1.8em;
        }
        .totop{
                position: fixed;
                display: none;
                bottom: 120px;
                right: 100px;
                cursor: pointer;
                color: #fff;
        }
</style>
<div class="vw_news">
<div class="vw_loc">
</div>
<div class="content">
        <ul>
          <li class="a_title">
          <?php echo $news["title"];?>
          </li>
          <li class="mes_nav">
          【发布时间:&nbsp;&nbsp;<?php echo $news["pub_time"];?>】&nbsp;【访问次数:&nbsp;<?php echo $news["readers"]+1;?>】&nbsp;【<a href="javascript:window.close();">关闭</a>】
          </li>
          <li class="a_content">
          <p><?php echo $news["content"];?></p>
          </li> 
          <li class="mes_nav">
          【发布时间:&nbsp;&nbsp;<?php echo $news["pub_time"];?>】&nbsp;【访问次数:&nbsp;<?php echo $news["readers"]+1;?>】&nbsp;【<a href="javascript:window.close();">关闭</a>】
          </li>
        </ul>
</div>
</div>
