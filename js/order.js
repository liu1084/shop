$(document).ready(function(){
    ChangeCustomerInfo();
    $('.customer-info-radio').on('change',function(){
        ChangeCustomerInfo();
    });
    $('.customer-info-radio').on('click',function(){
        $('.add-or-edit').hide();
        $('.add-customer-info').show();
    });

    $('.delete').on('click',function(){
        deleteCustomerInfo(this);
    });
    $('.edit').on('click',function(){
        editCustomerInfo(this);
    });
    $('.add-customer-info').on('click',function(){
        clearText();
        $('.add-or-edit').show();
        $('.save').off('click');
        $('.save').on('click',function(){
            addCustomerInfo();
        });
    });
    $('.change').on('click',function(){
        $('.modify-customer-info').show();
    });
    $('.confirm-change').on('click',function(){
        $('.modify-customer-info').hide();
    });
    $('.submit-order').on('click',function(){
        submitOrder();
    });
    $('div.add-or-edit > span > input').on('focusout',function(){
        if(!$(this).val()){
            $(this).parent('span').next('span').show();
        }
    });
    $('div.add-or-edit > span > input').on('focus',function(){
            $(this).parent('span').next('span').hide();
    });
    $('div.add-or-edit > span > input.customerPhone').on('focusout',function(){
        var phone = $('div.add-or-edit').find('input.customerPhone').val();
        if(!/^([1]){1}([0-9]){10}$/.test(phone)){
            $(this).parent('span').next('span').text('请输入有效的11位手机号码！');
            $(this).parent('span').next('span').show();
        }
    });
    $('.modify-mycart').on('click',function(){
        window.location = '/shop/controller/cart/MyCartController.php';
    });
});
function submitOrder(){
    var bills = [];
    var goodsId = '';
    var number = '';
    var customerInfoId = '';
    $('.customer-info-radio').each(function(index,element){
        if($(element).is(':checked') == true){
            customerInfoId = $(element).parent('span').parent('li').attr('id');
        }
    });
    $('.goodsBills').each(function(index,element){
        goodsId = $(element).attr('goods-id');
        number = $(element).find('span.number').text();
        bills[index] = {'goodsId':goodsId , 'number':number};
    });
    jQuery.ajax({
        type: 'POST',
        data: {
            action: 'submitOrder',
            customerInfoId: customerInfoId,
            bills: bills
        },
        dataType: 'json',
        url: '/shop/controller/cart/OrderController.php',
        success: function(json){
            alert(json.totalPrice);
        },
        error: function(error){
            console.log(error.responseText);
        }
    });
}
//显示选中的单选钮对应的顾客收货信息
function ChangeCustomerInfo(){
    var name = '';
    var phone = '';
    var address = '';
    $('.customer-info-radio').each(function(index,element){
        if($(element).is(':checked') == true){
            name = $(element).parent('span').next('span').text();
            phone = $(element).parent('span').next('span').next('span').text();
            address = $(element).parent('span').next('span').next('span').next('span').text();
        }
        $('div.customer-info').find('span.customerName').text(name);
        $('div.customer-info').find('span.customerPhone').text(phone);
        $('div.customer-info').find('span.customerAddr').text(address);
    });
}
function deleteCustomerInfo(_this){
    var id = $(_this).parent('li').attr('id');
    jQuery.ajax({
        type: 'POST',
        data: {
            action:'delete',
            id: id
        },
        dataType: 'json',
        url: '/shop/controller/cart/OrderController.php',
        success: function(json){
            $(_this).parent('li').remove();
            if($(_this).parent('li').find('input.customer-info-radio').is(':checked') == true){
                $('div.modify-customer-info > ul').find('li:nth-child(1)').find('input.customer-info-radio').prop('checked',true);
            }
        },
        error: function(error){
            console.log(error.responseText);
        }
    });
}
//添加顾客的收货信息
function addCustomerInfo(){
    var parentElement = $('.save').parent('div');
    var name = parentElement.find('.customerName').val();
    var phone = parentElement.find('.customerPhone').val();
    var address = parentElement.find('.customerAddr').val();
    jQuery.ajax({
        type: 'POST',
        data: {
            action:'add',
            name: name,
            phone:phone,
            address:address
        },
        dataType: 'json',
        url: '/shop/controller/cart/OrderController.php',
        //添加成功后,取得插入的数据表中新增的id（json.id）
        success: function(json){
            if(json.id){
                $('#add_customer_info').hide();
                var html = ' <li class="customer-list" id=" ' + json.id + ' "> ';
                html += '<span>';
                html += '<input type="radio" name="customer_info_radio" class="customer-info-radio" checked/>';
                html += '</span>';
                html += '<span class="customerName">' + name + '</span>&nbsp;&nbsp;&nbsp;';
                html += '<span class="customerPhone">' + phone + '</span>&nbsp;&nbsp;&nbsp;';
                html += '<span class="customerAddr">' + address + '</span>';
                html += '<span class="edit">编辑</span>';
                html += '<span class="delete">删除</span>';
                html += '</li>';
                $('div.modify-customer-info > ul').append(html);
                $('.add-or-edit').hide();
                clearText();
                $('.delete').on('click',function(){
                    deleteCustomerInfo(this);
                });
                $('.edit').on('click',function(){
                    editCustomerInfo(this);
                });
                ChangeCustomerInfo();
                $('.customer-info-radio').on('change',function(){
                    ChangeCustomerInfo();
                    $('.add-or-edit').hide();
                    $('.add-customer-info').show();
                });

            }else{                        //FIXME!!
                alert(json.message);
            }


        },
        error: function(error){
            console.log(error.responseText);
        }
    });

}
function editCustomerInfo(_this){
    $(_this).parent('li').find('input.customer-info-radio').prop('checked',true);
    //获取要编辑的数据
    var id = $(_this).parent('li').attr('id');
    var name = $(_this).parent('li').find('span.customerName').text();
    var phone = $(_this).parent('li').find('span.customerPhone').text();
    var address = $(_this).parent('li').find('span.customerAddr').text();
    ChangeCustomerInfo();
    $('.add-customer-info').hide();
    $('.add-or-edit').show();
    //将要编辑的数据显示在text中
    $('.add-or-edit').attr('customer-id',id);
    $('.add-or-edit').find('span > .customerName').val(name);
    $('.add-or-edit').find('span > .customerPhone').val(phone);
    $('.add-or-edit').find('span > .customerAddr').val(address);
    $('.save').off('click');
    $('.save').on('click',function(){
        //数据编辑后更新数据库
        updateCustomerInfo();
    });
}
function updateCustomerInfo(){
    var customerId = $('.add-or-edit').attr('customer-id');
    var changedName = $('.add-or-edit').find('span > .customerName').val();
    var changedPhone = $('.add-or-edit').find('span > .customerPhone').val();
    var changedAddr = $('.add-or-edit').find('span > .customerAddr').val();
    jQuery.ajax({
        type: 'POST',
        data: {
            action:'edit',
            id: customerId,
            changedName: changedName,
            changedPhone: changedPhone,
            changedAddr: changedAddr
        },
        dataType: 'json',
        url: '/shop/controller/cart/OrderController.php',
        success: function(json){
            //数据库更新成功后，通过不可变的id找到对应的顾客信息进行更新
            if(json.successful == true){
                $('.customer-list').each(function(index,element){
                    if(customerId == $(element).attr('id')){
                        $(element).find('span.customerName').text(changedName);
                        $(element).find('span.customerPhone').text(changedPhone);
                        $(element).find('span.customerAddr').text(changedAddr);
                    }
                });
                $('.add-or-edit').hide();
                clearText();
                $('.add-customer-info').show();
            }else{                                 //FIXME!!
                alert(json.message);
            }

        },
        error: function(error){
            console.log(error.responseText);
        }
    });
}
function clearText(){
    $('.add-or-edit').find('span > .customerName').val('');
    $('.add-or-edit').find('span > .customerPhone').val('');
    $('.add-or-edit').find('span > .customerAddr').val('');
}