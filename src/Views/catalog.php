<form action="/add-product" method="POST">
    <a href="/profile">Мой профиль</a>
    <a href="/cart">Корзина продуктов</a>
    <a href="/user-orders">Мои заказы</a>
    <h1 style="color: #04AA6D">Товары нашего магазина</h1>
    <div class="container">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <form action="/add-product" method="POST">
                    <img width="200" height="200" src="<?php echo $product->getImageUrl()?>">
                    <h2><?php echo $product->getName()?></h2>
                    <p><?php echo $product->getDescription()?></p>
                    <div class="price">₽ <?php echo $product->getPrice()?></div>
                    <input type="hidden" id="product_id" name="product_id" value = "<?php echo$product->getId();?>"
                    <?php if(isset($errors['amount'])):?>
                        <label style="color: red"> <?php echo $errors['amount'];?></label>
                    <?php endif;?>
                    <label for="amount"><b>кол-во</b></label>
                    <input type="text" id="amount" name="amount"?>
                    <button type = "submit" class="button">Добавить в корзину</button>
                </form>
            </div>
        <? endforeach; ?>
        <a href="/profile.php"Мой профиль</a>
    </div>
</form>

<style>
    input[type=text], input[type=password] {
        width: 50%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .product {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin: 10px;
        width: calc(33% - 40px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .product:hover {
        transform: translateY(-5px);
    }

    .product img {
        max-width: 100%;
        border-radius: 5px;
    }

    .product h2 {
        font-size: 18px;
        margin: 10px 0;
    }

    .product p {
        color: #666;
    }

    .product .price {
        font-size: 20px;
        color: #04AA6D;
        margin: 10px 0;
    }

    .button {
        background: #04AA6D;
        color: #fff;
        border: none;
        padding: 15px 30px;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }

    .button:hover {
        background: #04AA6D;
    }

</style>

