<?php


#==============================================================
# Исполнение изменения Страница изменения какого то пункта в заказе
#==============================================================





session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}
require_once '../connect_to_database.php';

$id_pink_order = $_POST['id_pink_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
$now_state = $select['pink_state'];
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}




$id = $_POST['id'];
$id_pink_order = $_POST['id_pink_order'];
$id_paragraph = $_POST['id_paragraph'];


if(!empty( $_POST['size'])){
    $size = $_POST['size'];
}
else{
    $size = NULL;
}

$quantity = $_POST['quantity'];
if($quantity > 0 and $_POST['price'] >= 0 and is_numeric($quantity) and is_numeric($_POST['price'])){

    if($_SESSION['state'] == 'admin'){
        $price = $_POST['price'];
        if($now_state == 'Возврат дизайнеру' or $now_state == 'Перевыбор ткани в салоне' or $now_state == 'Возврат ткани'){
            mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `price` = '$price', `size` = '$size', `quantity` = '$quantity', `additional_info` = 'Заменено', `supplier_price` = 0 WHERE `id` = '$id'");
        }else {
            mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `price` = '$price', `size` = '$size', `quantity` = '$quantity' WHERE `id` = '$id'");
        }

        mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `price` = '$price', `size` = '$size', `quantity` = '$quantity' WHERE `id` = '$id'");

        header("Location: pink_all_elements_of_the_mutable.php?id_pink_order=$id_pink_order");

    }else{
        if($now_state == 'Возврат дизайнеру' or $now_state == 'Перевыбор ткани в салоне' or $now_state == 'Возврат ткани'){
            mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `size` = '$size', `quantity` = '$quantity', `additional_info` = 'Заменено', `supplier_price` = 0 WHERE `id` = '$id'");
        }else {
            mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `size` = '$size', `quantity` = '$quantity' WHERE `id` = '$id'");
        }

        mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `size` = '$size', `quantity` = '$quantity' WHERE `id` = '$id'");

        header("Location: pink_all_elements_of_the_mutable.php?id_pink_order=$id_pink_order");
    }


} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
        <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
        <title>Pink</title>

        <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
        <script src="../assets/script/app.js" defer></script>
    </head>

    <body>
        <div class="body_result">
            <h1>Ошибка</h1>
            <div class="body_result_title"> Количество и цена должны быть больше 0</div>
            <a href="pink_all_elements_of_the_mutable.php?id_pink_order=<?= $id_pink_order ?>" class="common_back_href">Вернуться</a>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    </body>
    </html>

    <?php
}

//$description = $_POST['description'];
//$sql = "SELECT * FROM `products` WHERE `id` = '$description'";
//$select = mysqli_query($connect, $sql);
//$select = mysqli_fetch_assoc($select);
//$price = $select['price'];
//$description = $select['title'] . " " . $select['descriptiom'];



