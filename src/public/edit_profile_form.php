<form action="handle_edit_profile_form.php" method="POST">
    <div class="container">
        <h1>Изменение профиля</h1>
        <hr>
        <label for="name"><b>Имя:</b></label>
        <?php if(isset($errors['name'])):?>
            <label style="color: red"> <?php echo $errors['name'];?></label>
        <?php endif;?>
        <input type="text" id="name" name="name"
            <?php if(isset($dataNew['name'])):?>
                value=<?php echo $dataNew['name'];?>
            <?php endif;?>>

        <label for="email"><b>Email:</b></label>
        <?php if(isset($errors['email'])):?>
            <label style="color: red"> <?php echo $errors['email'];?></label>
        <?php endif;?>
        <input type="text" id="email" name="email"
            <?php if(isset($dataNew['email'])):?>
                value=<?php echo $dataNew['email'];?>
            <?php endif;?>>

        <label for="password">Пароль:</label>
        <?php if(isset($errors['password'])):?>
            <label style="color: red"><?php echo $errors['password'];?></label>
        <?php endif;?>
        <input type="password" id="password" name="password"

        <label for="repassword">Повтор пароля:</label>
        <?php if(isset($errors['repassword'])):?>
            <label style="color: red"><?php echo $errors['repassword'];?></label>
        <?php endif;?>
        <input type="password" id="repassword" name="repassword"
        <hr>

        <button type="submit" class="registerbtn">Сохранить изменения</button>
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
