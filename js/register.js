//校验password
$(document).ready(function () {
    var passwordElement = $('#password');
    var strengthElement = passwordElement.parent('span').next('span.strength');
    var password = '';
    passwordElement.on('focusout', function () {
        //是否为空
        password = passwordElement.val();
        if (!password) {
            passwordElement.parent('span').next('span').next('span.has-error').show();
            strengthElement.hide();
        }
    });
    passwordElement.on('focus', function () {
        passwordElement.parent('span').next('span').next('span.has-error').hide();
    });
    var number = 0;
    passwordElement.on('keyup', function(){
        password = passwordElement.val();
        /**
         * 密码强度的判断
         * 1：强 密码长度在6位以上，而且是由数字、字母和特殊符号组成
         * 2：中 密码长度大于6位，而且是有数字和字母组成
         * 3：弱 密码长度小于等于6位 或者 只有数字或者 只有字母组成
         * ([^\w]+)|([a-z]+)|(\d+)|([A-Z]+)|([\s]+)
        */
        var passwordStrength = ['strong', 'middle', 'weak'];
        var strength = '';
        if (/[^\w]+/.test(password)){
            number += 20;
        }
        if (/[a-z]+/.test(password)){
            number += 20;
        }
        if (/[\d]+/.test(password)){
            number += 20;
        }
        if (/[A-Z]+/.test(password)){
            number += 20;
        }
        if (/[\s]+/.test(password)){
            number += 20;
        }
        //strong
        if (password.length > 6 && number >=200 ){
            strength = passwordStrength[0];
        }
        //middle
        if (password.length > 6 && number >= 120 && number < 200){
            strength = passwordStrength[1];
        }
        //weak
        if (password.length <= 6 || number < 120){
            strength = passwordStrength[2];
        }
        if (password.length < 3 || password.length > 12) {
           strength = "密码由3-12位字母数字特殊字符组成";
            strengthElement.css({color: 'red'});
        }
        strengthElement.text(strength);
        strengthElement.show();
        switch (strength){
            case 'weak':
                strengthElement.css({color: 'red'});
                break;
            case 'middle':
                strengthElement.css({color: 'yellow'});
                break;
            case 'strong':
                strengthElement.css({color: 'green'});
                break;
        }
    });
});
//校验email
$(document).ready(function(){
    var emailElement = $('#email');
    var errorMsg = ['邮箱不能为空','不是有效的email'];
    var hasErrorElement = emailElement.parent('span').next('span');
    emailElement.on('focusout',function(){
       var email = emailElement.val();
       if(!email){
           hasErrorElement.text(errorMsg[0]);
           hasErrorElement.show();
       }else if(!/^([0-9]|[a-zA-Z]|_)+\@([a-zA-Z]|_|[0-9])+\.([a-zA-Z]){2,3}(\.)*([a-zA-Z]){0,3}$/.test(email)){
           hasErrorElement.text(errorMsg[1]);
           hasErrorElement.show();
       }
    });
    emailElement.on('focus',function(){
       hasErrorElement.hide();
    });
});
//校验re-password
$(document).ready(function(){
    var rePasswordElement = $('#re-password');
    var errorMsg = ['确认密码不能为空','确认密码与密码不一致'];
    var hasErrorElement = rePasswordElement.parent('span').next('span');
    rePasswordElement.on('focusout',function(){
        var rePassword = rePasswordElement.val();
        var password = $('#password').val();
        if(!rePassword){
            hasErrorElement.text(errorMsg[0]);
            hasErrorElement.show();
        }else if(password != rePassword){
            hasErrorElement.text(errorMsg[1]);
            hasErrorElement.show();
        }
    });
    rePasswordElement.on('focus',function(){
        hasErrorElement.hide();
    });
});
//校验昵称
$(document).ready(function(){
    var nicknameElement = $('#nickname');
    nicknameElement.on('focusout',function(){
        var nickname = nicknameElement.val();
        if(!nickname){
            nicknameElement.parent('span').next('span').show();
        }
    });
    nicknameElement.on('focus',function(){
        nicknameElement.parent('span').next('span').hide();
    });
});
