<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<a href="/catalog">Каталог продуктов</a>
    <h1>Мои Заказы</h1>
    <div class="order-container">
        <?php foreach ($newUserOrders as $newUserOrder): ?>
            <div class="order-card">
                <h2>Заказ № <?php echo $newUserOrder->getId()?></h2>
                <p><?php echo $newUserOrder->getContactName()?></p>
                <p><?php echo $newUserOrder->getContactPhone()?></p>
                <p><?php echo $newUserOrder->getComment()?></p>
                <p><?php echo $newUserOrder->getAddress();?></p>
                <table>
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Количество</th>
                        <th>Стоимость</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($newUserOrder->getOrderProducts() as $orderProduct): ?>
                            <tr>
                                <td><?php echo $orderProduct->getProduct()->getName()?></td>
                                <td><?php echo $orderProduct->getAmount()?></td>
                                <td><?php echo $orderProduct->getProduct()->getPrice()?></td>
                                <td><?php echo $orderProduct->getProduct()->getTotalSum()?></td>
                            </tr>
                        <? endforeach; ?>
                    </tbody>
                </table>
                <p>Сумма заказа <?php echo $newUserOrder->getTotal()?></p>
            </div>
        <? endforeach; ?>
    </div>

</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .order-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
    }

    .order-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 600px;
        padding: 15px;
        text-align: left;
    }

    .order-card h2 {
        font-size: 18px;
        color: #333;
        margin: 0 0 10px;
    }

    .order-card p {
        margin: 5px 0;
        color: #555;
    }

    .order-card .order-status {
        font-weight: bold;
        color: #4caf50; /* Зеленый цвет для статусов "Выполнен" */
    }

    .order-card .order-status.pending {
        color: #ff9800; /* Оранжевый цвет для статусов "В ожидании" */
    }

    .order-card .order-status.cancelled {
        color: #f44336; /* Красный цвет для статусов "Отменен" */
    }


    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #dddddd;
    }

    th {
        background-color: #04AA6D; /* Цвет фона заголовка */
        color: white; /* Цвет текста заголовка */
    }

    tr:hover {
        background-color: #f1f1f1; /* Цвет фона строки при наведении */
    }

    tbody tr:nth-child(even) {
        background-color: #f9f9f9; /* Цвет фона четных строк */
    }

</style>