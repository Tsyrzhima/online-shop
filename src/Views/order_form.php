<body>
<div class="container">
    <a href="/cart">Корзина</a>
    <h1>Оформление заказа</h1>
    <form action="/create-order" method="POST">
        <label for="name">Имя:</label>
        <?php if(isset($errors['name'])):?>
            <label style="color: red"> <?php echo $errors['name'];?></label>
        <?php endif;?>
        <input type="text" id="name" name="name" required
            <?php if(isset($request)):?>
                value="<?php echo $request->getName();?>"
            <?php endif;?>>
        <label for="address">Адрес доставки:</label>
        <?php if(isset($errors['address'])):?>
            <label style="color: red"> <?php echo $errors['address'];?></label>
        <?php endif;?>
        <input type="text" id="address" name="address" required
            <?php if(isset($request)):?>
                value="<?php echo $request->getAddress();?>"
            <?php endif;?>>
        <label for="phone">Номер телефона:</label>
        <?php if(isset($errors['phone'])):?>
            <label style="color: red"> <?php echo $errors['phone'];?></label>
        <?php endif;?>
        <input type="tel" id="phone" name="phone" placeholder="+7 (___) ___-__-__" required
            <?php if(isset($request)):?>
                value="<?php echo $request->getPhone();?>"
            <?php endif;?>>
        <label for="phone">Комментарий:</label>
        <input type="comment" id="comment" name="comment"
            <?php if(isset($request)):?>
                value="<?php echo $request->getComment();?>"
            <?php endif;?>>
        <div class="container">
            <?php foreach ($userProducts as $userProduct): ?>
                <h2><?php echo $userProduct->getProduct()->getName()?></h2>
                <label for="amount">Количество:</label>
                <input type="number" id="amount" name="amount" min="1" value=<?php echo $userProduct->getAmount()?> required>
                <label for="amount">Стоимость за 1 шт:</label>
                <div class="price">₽ <?php echo $userProduct->getProduct()->getPrice()?></div>
                <label for="totalProduct">Итого:</label>
                <div class="price">₽ <?php echo $userProduct->getTotalSum();?></div>
            <? endforeach; ?>
            <h2><label for="totalOrder">Заказ на сумму:</label></h2>
            <div class="price">₽ <?php echo $total;?></div>
        </div>
        <button type="submit">Оформить заказ</button>
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
    input[type="comment"],
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
