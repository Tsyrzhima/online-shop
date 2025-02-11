<form action="/add-product" method="POST">
    <div class="container">
        <h1>Добавить продукт</h1>
        <p>Для добавления продукта заполните форму</p>
        <hr>
        <label for="product_id"><b>id продукта:</b></label>
        <?php if(isset($errors['product_id'])):?>
        <label style="color: red"> <?php echo $errors['product_id'];?></label>
        <?php endif;?>
        <input type="text" id="product_id" name="product_id" required

        <label for="amount"><b>количество</b></label>
        <?php if(isset($errors['amount'])):?>
        <label style="color: red"> <?php echo $errors['amount'];?></label>
        <?php endif;?>
        <input type="text" id="amount" name="amount" required
        <hr>

        <button type="submit" class="registerbtn">Добавить продукт</button>
    </div>

</form>

<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>
