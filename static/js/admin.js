function create_corver() {
    var div = document.createElement('div');

    if ($('#cover')) 
        $('#cover').remove();

    div.id = "cover";
    div.style.height = document.documentElement.clientHeight + "px";
    div.style.width = document.documentElement.clientWidth + "px";
    div.style.position = 'absolute';
    div.style.left = '0';
    div.style.top = '0';
    div.style.zIndex = 2;
    div.style.background = "rgba(0, 0, 0, .5)";
    //div.innerHTML = '<div class="clearfix" style="width:220px;position:absolute;left:50%;top:50%;margin-left:-110px;"><input type="text" class="form-controll" style="width:150px;margin-bottom:0;margin-right:5px;"><button type="button" class="btn btn-primary">保存</button></div>';
    document.body.appendChild(div);
}
function remove_cover() {
    $('#cover').remove();
    $('.save-container').hide();
}
$(function() {
    $(document).on('click', '#cover', remove_cover);
    $(document).on('click', '.btn-add', function() {
        create_corver();
        $('.save-container').show();
    });
    $(window).on('resize', create_corver);
});
