$(document).ready(function(){
    $('#cart_button, #cart').on('mouseover', function(){
        showCart();
    });

    $('#cart_button, #cart').on('mouseout', function(){
        //hideCart();
    });

    $('.add-cart-button').on('click', function () {
        var current = getCurrentGoods(this);
        addCart(current);
    });

     $('.delete-goods').on('click',function(){
         deleteGoodsFromCart(this);
    });
    $('.plus').on('click',function(){
         var number = $(this).next('.number').text();
         $(this).next('.number').text(++number);
    });
    $('.minus').on('click',function(){
        var number = $(this).prev('.number').text();
        if(number > 0){
            $(this).prev('.number').text(--number);
        }
    });
    $('.go-to-myCart').on('click',function(){
        if($('.go-to-myCart').text() == '去购物车结算'){
            goToMyCart();
        }

    });

});
function goToMyCart(){
    window.location = '/shop/controller/cart/MyCartController.php';
}

function getMyCartGoods(){
    //FIXME!
}

function deleteGoodsFromCart(_this){
    var parentElement = $(_this).parent('li');
    parentElement.remove();
    if($('#cart > ul > li').length == 0){
        $('.go-to-myCart').text('空空如也~~~~~~');
        $('.go-to-myCart').off('click');
    }
    var cartGoodsId = parentElement.attr('id');
    var goodsId = parentElement.attr('goods-id');
    jQuery.ajax({
        type: 'POST',
        data: {
            action: 'DeleteGoods',
            cartGoodsId: cartGoodsId,
            goodsId: goodsId
        },
        dataType: 'json',
        url: '/shop/controller/cart/CartController.php',
        success: function(json){
            console.log(json);
        },
        error: function(error){
            console.log(error.responseText);
        }
    });


}


function showCart(){
    $('#cart').show();
}

function hideCart(){
    $('#cart').hide();
}

function getCurrentGoods(_this){
    var currentGoods = {};
    var button = _this;
    var myParent = $(button).parent().parent().parent();
    currentGoods.img = $(myParent).find('div:nth-child(1)').find('img').attr('src');
    var myLitParent = $(myParent).find('div:nth-child(2)');
    currentGoods.id = $(myLitParent).attr('goods-id');
    currentGoods.name = $(myLitParent).find('span.name').text();
    currentGoods.number = $(myLitParent).find('span.number').text();
    currentGoods.price = $(myLitParent).find('span.price').text();
    return currentGoods;
}

function addNew(goodsAddId,goodsAddImg,goodsAddName,goodsAddNumber,goodsAddPrice){
    var html = '<li class="cart-goods" goods-id="' + goodsAddId + '" id = "">';
    html += '<span><img class="img-small" src="' + goodsAddImg + '" alt="" /></span>';
    html += '<span class="common-bordr name">'+ goodsAddName +'</span>';
    html += '<span class="common-bordr price">'+ goodsAddPrice +'</span>';
    html += '<span class="common-bordr number">'+ goodsAddNumber +'</span>';
    html += '<span class="delete-goods" style="color: red;background: #e38d13;">X</span>';
    html += '</li>';
    $('#cart > ul').append(html);
    $('.go-to-myCart').text('去购物车结算');
    $('.go-to-myCart').on('click',function(){
        goToMyCart();
    });
    $('.delete-goods').on('click',function(){
        deleteGoodsFromCart(this);
    });

}
function addCart(goods) {
    var goodsAddId = goods.id;
    var goodsAddImg = goods.img;
    var goodsAddName = goods.name;
    var goodsAddNumber = goods.number;
    var goodsAddPrice = goods.price;
    //更新到购物车
    //已经存在的商品和当前要加入的商品的ID如果重复，应该更新购物车里面的数量，而不是继续添加新的列表到购物车
    //取得当前加入购物车商品的ID -- goodsId
    var cartAllGoodsList = $('#cart > ul > li');
    var flag = 0;
    if (cartAllGoodsList.length > 0){
        cartAllGoodsList.each(function(index, element){
            var goodsId = $(element).attr('goods-id');
            var goodsCartNumber = 0;

            if (goodsAddId === goodsId){
                //id值相同
                goodsCartNumber = $(element).find('span.number').text();
                var currentGoodsNumber = parseInt(goodsAddNumber) + parseInt(goodsCartNumber);
                $(element).find('span.number').text(currentGoodsNumber);
                flag ++;
            }
        });
        if(flag == 0){
            addNew(goodsAddId,goodsAddImg,goodsAddName,goodsAddNumber,goodsAddPrice);
        }
    }else{
        addNew(goodsAddId,goodsAddImg,goodsAddName,goodsAddNumber,goodsAddPrice);
    }

    jQuery.ajax({
        type: 'POST',
        data: {
            action: 'AddGoods',
            goodsAddId: goodsAddId,
            goodsAddNumber: goodsAddNumber
        },
        dataType: 'json',
        url: '/shop/controller/cart/CartController.php',
        success: function(json, textStatus){
            console.log(textStatus);
            if (json.code === '300010'){
                //商品跳到购物车的动画
                return false;
            }
            if (json.code === '100016'){
                //用户没有登录
                var redirectUrl = '/shop/controller/cart/CartController.php';
                window.location = '/shop/controller/user/LoginController.php?redirectUrl=' + redirectUrl;
                return false;
            }
        },
        error: function(error, textStatus){
            console.log(textStatus);
        }
    });


}
