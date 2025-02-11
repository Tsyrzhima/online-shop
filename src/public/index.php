<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if($requestUri === '/registration'){
    if($requestMethod === 'GET'){
        require_once './registration/registration_form.php';
    }elseif($requestMethod === 'POST'){
        require_once './registration/handle_registration_form.php';
    }else{
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}elseif($requestUri === '/login'){
    if($requestMethod === 'GET'){
        require_once './login/login_form.php';
    }elseif($requestMethod === 'POST'){
        require_once './login/handle_login_form.php';
    }else{
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}elseif ($requestUri === '/catalog') {
   if($requestMethod === 'GET'){
        require_once './catalog/catalog.php';
    }elseif($requestMethod === 'POST'){
        require_once './catalog/catalog.php';
    }else{
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}elseif ($requestUri === '/edit-profile') {
    if ($requestMethod === 'GET') {
        require_once './editProfile/handle_edit_profile_form.php';
    } elseif ($requestMethod === 'POST') {
        require_once './editProfile/handle_edit_profile_form.php';
    }else{
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}elseif ($requestUri === '/profile') {
    if($requestMethod === 'GET'){
        require_once './profile/handle_profile_form.php';
    }elseif($requestMethod === 'POST'){
        require_once './profile/handle_profile_form.php';
    }else{
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}elseif ($requestUri === '/add-product') {
    if($requestMethod === 'GET'){
        require_once './addProduct/handle_add_product_form.php';
    }elseif($requestMethod === 'POST'){
        require_once './addProduct/handle_add_product_form.php';
    }else{
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}elseif ($requestUri === '/cart') {
    if($requestMethod === 'GET'){
        require_once './cart/cart.php';
    }elseif($requestMethod === 'POST'){
        require_once './cart/cart.php';
    }else{
        echo "$requestMethod для адреса $requestUri не поддерживается";
    }
}else{
    http_response_code(404);
    require_once './404.php';
}