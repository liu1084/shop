$(document).ready(function(){
    $('.plus').on('click',function(){
        var number = $(this).next('span').find('.number').val();
        $(this).next('span').find('.number').val(++number) ;
        updateNumber(this,number);
        totalChecked();
    });
    $('.minus').on('click',function(){
        var number = $(this).prev('span').find('.number').val();
        if(number > 0){
            $(this).prev('span').find('.number').val(--number);
        }
        updateNumber(this,number);
        totalChecked();
    });
    $('.goods-checked').on('click',function(){
        totalChecked();
    });
    $('.checkAll').on('click',function(){
        if($('.checkAll').is(':checked') == true){
            $('.goods-checked').prop('checked',true);
            totalChecked();
        }else{
            $('.goods-checked').prop('checked',false);
            totalChecked();
            //$('.goods-checked').removeAttr('checked');
        }
    });
    $('.number').on('keyup',function(){

        if($(this).val() > 0){
            var number = $(this).val();
            updateNumber($(this).parent('span'),number);
            totalChecked();
        }else{
            alert('商品数量不能小于0哦 ！！');
        }

    });
    $('.go-to-pay').on('click',function(){
        goToPay();
    });
    totalChecked();

});
function goToPay(){
    var goodsId = '';
    var number = '';
    var checkedGoods = [];
    var key = 0;
    $('.goods-checked').each(function(index,element){
        if($(element).is(':checked') == true) {
            var parentElement = $(element).parent('span').parent('li');
            goodsId = parentElement.attr('goods-id');
            number = parentElement.find('span > .number').val();
            checkedGoods[key++] =  {'goodsId' : goodsId , 'number' : number};
        }
    });
    jQuery.ajax({
        type: 'POST',
        data: {
            action: 'goToPay',
            checkedGoods: checkedGoods
        },
        dataType: 'json',
        url: '/shop/controller/cart/OrderController.php',
        success: function(json){
            if(json.checkedNothing){
                alert('没有商品被选中！');
            }else{
                window.location = '/shop/controller/cart/OrderController.php';
            }

        },
        error: function(error){
            //console.log(error.responseText);
        }

    });



}
//显示选中商品的总价
function totalChecked(){
    var goodsId = '';
    var number = '';
    var checkedGoods = [{'goodsId' : goodsId , 'number' : number}];
    var key = 0;
    var isAllChecked = true;
    $('.goods-checked').each(function(index,element){
        if($(element).is(':checked') == true) {
            var parentElement = $(element).parent('span').parent('li');
            goodsId = parentElement.attr('goods-id');
            number = parentElement.find('span > .number').val();
            checkedGoods[key++] =  {'goodsId' : goodsId , 'number' : number};
        }else{
            $('.checkAll').prop('checked',false);
            isAllChecked = false;
        }
    });
    if(isAllChecked === true){
        $('.checkAll').prop('checked',true);
    }
    jQuery.ajax({
       type: 'POST',
        data: {
            action: 'totalPrice',
            checkedGoods: checkedGoods
        },
        dataType: 'json',
        url: '/shop/controller/cart/MyCartController.php',
        success: function(json){
            $('.total').text(json.j);
        },
        error: function(error){
            console.log(error.responseText);
        }

    });
}
//改变数据库里商品的数量
function updateNumber(_this,number){
    var goodsId = $(_this).parent('li').attr('goods-id');
    jQuery.ajax({
        type: 'POST',
        data: {
            action: 'updateNumber',
            number: number,
            goodsId: goodsId
        },
        dataType: 'json',
        url: '/shop/controller/cart/MyCartController.php',
        success: function(json){
            console.log(json);
        },
        error: function(error){
            console.log(error.responseText);
        }
    });

}