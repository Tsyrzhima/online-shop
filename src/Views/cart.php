<form action="/cart" method="post">
    <a href="/catalog">Каталог продуктов</a>
    <h1 style="color: #04AA6D">Моя корзина</h1>
    <div class="container">
        <?php foreach ($newUserProducts as $newUserProduct): ?>
            <div class="product">
                <img width="200" height="200" src="<?php echo $newUserProduct['image_url']?>">
                <h2><?php echo $newUserProduct['name']?></h2>
                <p><?php echo $newUserProduct['description']?></p>
                <p><?php echo $newUserProduct['amount']?> штук </p>
                <div class="price">₽ <?php echo $newUserProduct['price']?></div>
                <a href="#" class="button">Удалить</a>
            </div>
        <? endforeach; ?>
    </div>
    <a href="/order" methods="GET" class="button">Оформить заказ</a>
</form>

<style>
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
        padding: 20px;
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
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }

    .button:hover {
        background: #04AA6D;
    }
</style>

