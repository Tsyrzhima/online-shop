<form action="/add-review" method="post">
    <div class="container">
        <a href="/catalog" class="back-link">Вернуться в каталог</a>

        <div class="product">
            <img width="200" height="200" src="<?php echo $product->getImageUrl(); ?>" alt="<?php echo $product->getName(); ?>" class="product-image">
            <h2 class="product-name"><?php echo $product->getName(); ?></h2>
            <p class="product-description"><?php echo $product->getDescription(); ?></p>
            <div class="price">₽ <?php echo $product->getPrice(); ?></div>
            <div class="rating-info">
                <?php if ($count > 0): ?>
                    <span class="rating">Оценка <?php echo $product->getRating(); ?></span>
                    <span class="rating-count">(<?php echo $product->getCount(); ?> оценок)</span>
                <?php else: ?>
                    Оценок нет
                <?php endif; ?>
            </div>
        </div>

        <?php if ($count > 0): ?>
            <div class="review-section">
                <h1 class="section-title">Отзывы о товаре</h1>
                <div class="reviews-list">
                    <?php foreach ($newReviews as $newReview): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <h2 class="review-author"><?php echo $newReview->getUser()->getName(); ?></h2>
                                <div class="review-rating">
                                    <?php if ($newReview->getRating() === 5): ?>
                                        5 звёзд
                                    <?php elseif ($newReview->getRating() === 1): ?>
                                        1 звезда
                                    <?php else: ?>
                                        <?php echo $newReview->getRating(); ?> звезды
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="review-date"><?php echo $newReview->getDate(); ?></p>
                            <p class="review-comment"><?php echo $newReview->getReviewComment(); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h1 class="section-title">Оставить отзыв о товаре</h1>
            <form id="reviewForm" action="/add-review" method="post" class="review-form">
                <div class="form-group">
                    <label for="rating" class="form-label">Оценка:</label>
                    <select id="rating" name="rating" class="form-select" required>
                        <option value="" disabled selected>Выберите оценку</option>
                        <option value="5">5 звёзд</option>
                        <option value="4">4 звезды</option>
                        <option value="3">3 звезды</option>
                        <option value="2">2 звезды</option>
                        <option value="1">1 звезда</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="review" class="form-label">Ваш отзыв:</label>
                    <textarea id="review" name="review" rows="5" class="form-textarea" required></textarea>
                    <input type="hidden" id="product_id" name="product_id" value = "<?php echo$product->getId();?>">
                </div>
                <button type="submit" class="button">Отправить отзыв</button>
            </form>
        </div>
    </div>
</form>

<style>
    /* Общие стили */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Ссылка "Вернуться в каталог" */
    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #04AA6D; /* Основной цвет */
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
    }

    .back-link:hover {
        text-decoration: underline;
        color: #038857; /* Темнее на hover */
    }

    /* Блок с информацией о товаре */
    .product {
        text-align: center;
        margin-bottom: 30px;
    }

    .product-image {
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .product-name {
        font-size: 24px;
        margin: 10px 0;
        color: #333;
    }

    .product-description {
        font-size: 16px;
        color: #666;
        margin: 10px 0;
    }

    .price {
        font-size: 22px;
        color: #04AA6D; /* Основной цвет */
        margin: 15px 0;
        font-weight: bold;
    }

    .rating-info {
        font-size: 18px;
        color: #555;
    }

    .rating {
        font-weight: bold;
    }

    .rating-count {
        color: #777;
    }

    /* Секция с отзывами */
    .review-section {
        margin-top: 30px;
    }

    .section-title {
        font-size: 22px;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #04AA6D; /* Основной цвет */
        padding-bottom: 10px;
    }

    .reviews-list {
        margin-bottom: 30px;
    }

    .review-item {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #eee;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .review-author {
        font-size: 18px;
        color: #333;
        margin: 0;
    }

    .review-rating {
        font-size: 16px;
        color: #04AA6D; /* Основной цвет */
        font-weight: bold;
    }

    .review-date {
        font-size: 14px;
        color: #777;
        margin: 5px 0;
    }

    .review-comment {
        font-size: 14px;
        color: #555;
        margin: 10px 0;
    }

    /* Форма для добавления отзыва */
    .review-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-label {
        font-size: 16px;
        color: #333;
        font-weight: bold;
    }

    .form-select, .form-textarea {
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
        transition: border-color 0.3s ease;
    }

    .form-select:focus, .form-textarea:focus {
        border-color: #04AA6D; /* Основной цвет */
        outline: none;
    }

    .form-textarea {
        resize: vertical;
    }

    .button {
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background-color: #04AA6D; /* Основной цвет */
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button:hover {
        background-color: #038857; /* Темнее на hover */
    }
</style>