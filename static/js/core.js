/*
 * 网站js框架核心
 * @author luofei
 * @created at: 2013-11-29
 * */
var hk = {
    /*
     * 收集表单中的所有值域
     * @param {String} formId 表单元素的id值
     * @return {Object} obj 返回收集的表单值对象
     * */
    formCollect: function(formId) {
        var form = $('#' + formId),
            vals = $('select, input', form),
            obj = {};

        vals.each(function() {
            obj[$(this).attr('name')] = this.value;
        });

        return obj;
    }
};

// 首页图片自动切换
$(function() {
    $('ul.header-ul').children('li').hover(function() {
        $('ul', $(this)).show();
    }, function() {
        $('ul', $(this)).hide();
    });
});
