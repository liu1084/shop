<?php
include_once __DIR__ . '/../../model/Goods.php';

?>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>购物车</title>
    <?php include_once __DIR__ . '/../header.php';?>
    <link href="../../css/cart.css" rel="stylesheet" type="text/css" />
    <link href="../../css/goods.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../js/cart.js"></script>

</head>
<body>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="pull-right" style="position: relative;">
            <input id="cart_button" type="button" class="cart-button" value="购物车" />
            <div id="cart">

                <ul>
                    <?php
                    if(!empty($goodsInCart) && isset($goodsInCart)){
                        foreach($goodsInCart as $eachGoods){              //$goodsInCart为数据库当前用户cart表中每一行对象组成的数组
                            $id = $eachGoods->id;
                            $goodsId = $eachGoods->goodsId;
                            $goods = $goodsServiceImpl->getGoods($goodsId);
                    ?>
                            <li class="cart-goods" goods-id="<?php echo $goodsId; ?>" id="<?php echo $id; ?>">
                                <span><img class="img-small" src="<?php echo $goods->getSrc(); ?>" alt="" /></span>
                                <span class="common-bordr name"><?php echo $goods->getName(); ?></span>
                                <span class="common-bordr price"><?php echo $goods->getPrice(); ?></span>
                                <span class="common-bordr number"><?php echo $eachGoods->goodsNumber; ?></span>
                                <span class="delete-goods" style="color: red;background: #e38d13;">X</span>
                            </li>
                    <?php
                        }

                    }
                    ?>
                </ul>
                <?php
                    if(!empty($goodsInCart) && isset($goodsInCart)){
                ?>
                        <span class="go-to-myCart" style="color: red;">去购物车结算</span>
                <?php
                    }else{
                ?>
                        <span class="go-to-myCart" style="color: red;">空空如也~~~~~~</span>
                <?php
                    }
                ?>


            </div>
        </div>
        <div style="clear: both;" id="goods">
            <?php
            if(!empty($allGoods) && isset($allGoods)){
                foreach($allGoods as $oneGoods){
            ?>
                       <div class="col-lg-3 goods_item pull-left">
                           <div><img class="goods-img" src="<?php echo $oneGoods->goodsSrc; ?>" alt="" /></div>
                           <div goods-id="<?php echo $oneGoods->id; ?>">
                               <span class="common-bordr price"><?php echo $oneGoods->goodsPrice; ?></span>
                               <span class="common-bordr plus">+</span>
                               <span class="common-bordr number">1</span>
                               <span class="common-bordr minus">-</span>
                               <span class="common-bordr name"><?php echo $oneGoods->goodsName; ?></span>
                               <span><input class="add-cart-button" type="button" value="加入购物车"></span>
                           </div>
                        </div>
            <?php
                }
            }?>
        </div>
    </div>
</div>
<?php include_once __DIR__ . '/../footer.php' ?>
</body>
</html>