<form action="/edit-profile" method="POST">
    <a href="/logout">Выйти</a>
    <div class="container">
        <h1>Ваш профиль</h1>
        <?php if(isset($data['avatar_url'])):?>
            <img width="300" height="300" src="<?php echo $data['avatar_url'];?>">
        <?php endif;?>
        <hr>
        <label for="name"><b>Имя:</b></label>
        <input type="text" id="name" name="name"
            <?php if(isset($data['name'])):?>
                value=<?php echo $data['name'];?>
            <?php endif;?>>

        <label for="email"><b>Email:</b></label>
        <input type="text" id="email" name="email"
            <?php if(isset($data['email'])):?>
                value=<?php echo $data['email'];?>
            <?php endif;?>>
        <hr>

        <button type="submit" class="registerbtn">Изменить данные профиля</button>
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
