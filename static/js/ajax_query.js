/*
 * 自动选择插件
 * @author luofei
 * @created at: 2013-11-29
 * @param {String} cName 公司名称
 * @param {String} carNumber 电动汽车名字
 * @param {String} batterArr 电池组
 * @param {String} battery 单体电池
 * */
hk.autoQueryInfo = function(cName, carNumber, batterArr, battery) {
    var cName = $('#' + cName),
        carNumber = $('#' + carNumber),
        batterArr = $('#' + batterArr),
        battery = $('#' + battery),
        _t = $('#col-date'),

        // 数据存放地址
        cars = "/huakai/static/js/ajax/cars.js",
        batteries = "/huakai/static/js/ajax/batteryArr.js";

    // 公司名称已经初始化完成
    // 为每个选择框绑定事件
    cName.on('change', function() {
        var val = this.value, 
            i, len;

        carNumber.html("<option value=''>请选择</option>");
        batterArr.html("<option value=''>请选择</option>");
        battery.html("<option value=''>请选择</option>");
        if (val) {
            $.get(cars, function(data) {
                var arr = data[val];
                if (arr)
                    for (i = 0; len = arr.length, i < len; i++) 
                        carNumber.append("<option value='"+ arr[i] +"'>"+ arr[i] +"</option>");
            }, 'json');
        }
    });
    carNumber.on('change', function() {
        var val = this.value, 
            i, len;

        batterArr.html("<option value=''>请选择</option>");
        battery.html("<option value=''>请选择</option>");
        if (val) {
            $.get(batteries, function(data) {
                //var arr = data[cName.val()];
                if (val) {
                    for (i = 1; i <=8; i++) 
                        batterArr.append("<option value='"+ i +"'>"+ i +"</option>");
                }

                // 更新日期
                _t.html("<option value=''>请选择</option>");
                query_date(1);
            }, 'json');
        }
    });
    batterArr.on('change', function() {
        var val = this.value,
            i, len,
            idx = (this.value * 1 - 1) * 22 + 1;

        battery.html("<option value=''>请选择</option>");
        if (val) {
            for (i = 0; i < 22; i++) {
                battery.append("<option value='"+ (idx + i) +"'>"+ (idx + i) +"</option>");
            }
        }

        // 更新日期
        _t.html("<option value=''>请选择</option>");
        query_date(2);
    });

    $('#signal-battery').on('change', function() {
        // 更新日期
        _t.html("<option value=''>请选择</option>");
        query_date(3);
    });

    // 查询当前条件下的日期
    function query_date(con) {
        $.ajax({
            url: '/huakai/battery/query_date',
            method: 'post',
            data: {'con': con},
            success: function(msg) {
                var i, len;
                msg = JSON.parse(msg);

                if (msg.data)
                    for (i = 0; len = msg.data.length, i < len; i++) {
                        _t.append("<option value='"+ msg.data[i].Day +"'>" + msg.data[i].Day + "</option>");
                    }
            }
        })
    }
}
