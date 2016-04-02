/**
 * Created by Administrator on 2014/12/11.
 */
//页面所有dom节点加载完成后，执行function里面的方法
$(document).ready(function(){
    //方法
    var oldPasswordElement = $('#old-password');
    oldPasswordElement.on('focusout', function () {
        var oldPassword = oldPasswordElement.val();
        if (!oldPassword){
            //没有值
            oldPasswordElement.parent('span').next('span').show();
        }
    });

    oldPasswordElement.on('focus', function () {
        oldPasswordElement.parent('span').next('span').hide();
    });
});
$(document).ready(function(){
    var newPasswordElement = $('#new-password');
    newPasswordElement.on('focusout',function(){
        var newPassword = newPasswordElement.val();
        if(!newPassword){
            newPasswordElement.parent('span').next('span').show();
        }
    });
    newPasswordElement.on('focus',function(){
        newPasswordElement.parent('span').next('span').hide();
    });
});
$(document).ready(function(){
    var reNewPasswordElement = $('#re-new-password');
    reNewPasswordElement.on('focusout',function(){
        var reNewPassword = reNewPasswordElement.val();
        if(!reNewPassword){
            reNewPasswordElement.parent('span').next('span').show();
        }
    });
    reNewPasswordElement.on('focus',function(){
        reNewPasswordElement.parent('span').next('span').hide();
    });
});