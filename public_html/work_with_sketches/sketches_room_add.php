<?php


#===========================================================
# Добавление новой комнаты в заказ для работы с эсказами переход в sketches_roo_add_main
#===========================================================




session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


require_once '../connect_to_database.php';


//  -------------------------------
//  для проверки на создание заказа
$id_pink_order = $_GET['id_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------

$id_order = $_GET['id_order'];
$room = $_GET['room'];

?>



<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">


<!--    <link rel="stylesheet" href="../assets/css/style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->
<!--    <link rel="stylesheet" href="../assets/css/create_pink_style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->



    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">
    <link rel="stylesheet" href="../assets/css/style_sketches/style_for_sketches.css">


    <title>Пример веб-страницы</title>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>

    <script src="../assets/script/app.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
</head>


<body style="background-color: #F3F3F3">
<header class="header">
    <?php
    include('../header.php');
    ?>
</header>


<div class="common_div_body">

    <div>
        <div class="back_title">
            Заказы / Заказ № <?= $id_pink_order ?>
        </div>
        <a href="sketches_room.php?id_pink_order=<?= $id_order ?>" class="common_back_href">Вернуться</a>
    </div>

    <div class="main_field_div_sketches">


    <form method="post" action="sketches_room_add_main.php" >
        <div class="find_info">

            <input type="hidden" name="id_order" value="<?= $id_order ?>">
            <input type="hidden" name="room" value="<?= $room ?>">

            <div class="container_for_info">
                <h2>Добавление страницы в заказ №<?= $id_order ?></h2>
                <h3>Выбор спецификации</h3>
                <div>
                    <select class="common_select" name="specification" style="width: 250px;">
                        <option value="портьеры|тюли|подхваты|тп"> портьеры|тюли|подхваты|тп </option>
                        <option value="римские|франц|австрийск|тп"> римские|франц|австрийск|тп </option>
                        <option value="покрывала"> покрывала </option>
                        <option value="подушки|наволочки|валики"> подушки|наволочки|валики </option>
                        <option value="сваги|джаботы|ламбрикены"> сваги|джаботы|ламбрикены </option>
                        <option value="скатерти|салфетки"> скатерти|салфетки </option>
                    </select>
                </div>

                <div>
                    <button class="common_button" type="submit" id="create_pink_order" style="width: 250px; margin-top: 20px;">Создать страницу</button>
                </div>

            </div>
        </div>
    </form>


    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>