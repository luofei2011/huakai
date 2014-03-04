#!/bin/bash

# 一分钟监控一次数据更新文件的改动
while : ; do
    date=`date +%Y"-"%m"-"%d`
    # 监控当天的数据文件
    file_dir="server/$date"
    for file in ${file_dir}/*; do
        target=`basename $file`
        break
    done
    server_time=`date +%Y"-"%m"-"%d" "%H":"%M":"%S`
    # 得到当前系统时间的时间戳
    server_time_stat=`date -d "$server_time" +%s`

    # 获取文件的最近更改时间
    file_modify=`stat $file_dir/$target | grep -i 最近更改 | awk -F '：' '{print $2}' | awk -F '.' '{print $1}'`
    file_modify_stat=`date -d "$file_modify" +%s`
    time_diff=`expr $server_time_stat - $file_modify_stat`
    if [ $time_diff -gt 120 ]; then
        echo "服务器已停止响应，需要重启!"
        # 杀掉挂掉的进程
        pid=`ps -ef | grep server.php | grep -v 'grep' | awk '{print $2}'`
        kill $pid
        echo "正在重启服务器..."
        echo "服务器将在1min后重启完毕!"
        sleep 60
        php ./server/server.php &
        echo "服务器重启完毕！"
    else
        sleep 60
    fi
done
