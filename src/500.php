<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка 500</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="error-container">
    <div class="error-content">
        <h1 class="error-code">500</h1>
        <h2 class="error-message">Ой! Что-то пошло не так.</h2>
        <p class="error-description">
            На сервере произошла внутренняя ошибка. Пожалуйста, попробуйте обновить страницу позже.
        </p>
        <a href="/" class="home-link">Вернуться на главную</a>
    </div>
</div>
</body>
</html>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
    }

    .error-container {
        background-color: #fff;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
    }

    .error-code {
        font-size: 72px;
        margin: 0;
        color: #04AA6D;
    }

    .error-message {
        font-size: 24px;
        margin: 10px 0;
        color: #333;
    }

    .error-description {
        font-size: 16px;
        color: #666;
        margin: 20px 0;
    }

    .home-link {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background-color: #04AA6D;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .home-link:hover {
        background-color: #038857;
    }
</style>