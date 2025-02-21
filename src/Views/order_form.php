<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Оформление заказа</h1>
    <form action="/order" method="post">
        <label for="name">Имя:</label>
        <input type="text" id="name" name="name" required
            <?php if(isset($user['name'])):?>
                value=<?php echo $user['name'];?>
            <?php endif;?>>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required
            <?php if(isset($user['email'])):?>
                value=<?php echo $user['email'];?>
            <?php endif;?>>
        <label for="address">Адрес доставки:</label>
        <input type="text" id="address" name="address" required>
        <label for="phone">Номер телефона:</label>
        <input type="tel" id="phone" name="phone" placeholder="+7 (___) ___-__-__" required>
        <div class="container">
            <?php foreach ($newUserProducts as $newUserProduct): ?>
                <h2><?php echo $newUserProduct['name']?></h2>
                <label for="amount">Количество:</label>
                <input type="number" id="amount" name="amount" min="1" value=<?php echo $newUserProduct['amount']?> required>
                <label for="amount">Стоимость за 1 шт:</label>
                <div class="price">₽ <?php echo $newUserProduct['price']?></div>
                <label for="totalProduct">Итого:</label>
                <div class="price">₽ <?php echo $newUserProduct['amount']*$newUserProduct['price'];?></div>
            <? endforeach; ?>
            <h2><label for="totalOrder">Заказ на сумму:</label></h2>
            <div class="price">₽ <?php echo $total;?></div>
        </div>
        <button type="submit">Подтвердить заказ</button>
    </form>
</div>
</body>
</html>

<style>
    cssbody {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="amount"],
    select {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    button {
        background: #04AA6D;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background: #04AA6D;
    }
</style>
