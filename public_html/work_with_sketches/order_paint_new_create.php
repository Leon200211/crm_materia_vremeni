<?php



#========================================================
# Страница с рисовкой (создание) эскиза к странице заказа
#========================================================






session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';



$id = $_GET['id'];

$sketches_main_info = mysqli_query($connect, "SELECT * FROM `sketches_main` WHERE `id` = '$id'");
$sketches_main_info = mysqli_fetch_assoc($sketches_main_info);

$page = $sketches_main_info['page'];
$room = $sketches_main_info['room'];
$specification = $sketches_main_info['specification'];


?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Пример веб-страницы</title>


    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/style_sketches/style_for_sketches.css">
    <link rel="stylesheet" href="../assets/css/style_sketches/style_for_paint.css">


    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../assets/script/paint_update.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="../assets/script/app.js" defer></script>
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
            Заказы № <?= $sketches_main_info['id_order'] ?>  / Эскиз
        </div>
        <a href="sketches_room_update.php?id_in_sketches=<?= $id ?>" class="common_back_href">Назад</a>
    </div>

    <div class="main_field_div_sketches">


    <h2 class="update_title">Изменение Эскиза</h2>
    <h3 class="update_title">Изменение страницы № <?= $page ?></h3>
    <h3 class="update_title">В заказе № <?= $sketches_main_info['id_order'] ?></h3>
    <h3 class="update_title">Комната <?= $room ?></h3>
    <?php
    if(@fopen("../assets/img/img" . $sketches_main_info['image'], "r")) {
        ?>
        <div class="image_for_designer_old">
            <img src="../assets/img/img<?=$sketches_main_info['image']?>?<?= filemtime('../assets/img/img' . $sketches_main_info['image']) ?>" class="old_sketch">
        </div>
        <?php
    }?>


        <div class="paint_title">


            <div class="instruments_for_paint">
                <div class="main_btn_paint">
                    <button onclick="Clear()" class="common_back_href">Очистить</button>
                    <button onclick="undo_last()" class="common_back_href">Назад</button>
                </div>
                <div class="main_color_choice">
                    <input type="color" id="color">
                </div>
                <div>
                    <div class="switch-btn" onclick="change_color(this)" style="background:white"></div>
                    <div class="switch-btn" onclick="change_color(this)" style="background:black"></div>
                </div>
                <div class="choice_bold">
                    <input type="range" min="1" max="100" value="3" oninput="stroke_width = this.value">
                </div>
                <div class="shape_selection">
                    <button onclick="d_1()" class="common_back_href">Линиия</button>
                    <button onclick="d_2()" class="common_back_href">Овал</button>
                    <button onclick="d_3()" class="common_back_href">Квадрат</button>
                </div>
            </div>


            <div class="image_for_designer">
                <canvas id="c1_new" width="1000" height="500"></canvas>
            </div>
            <img hidden id="img"/>

        </div>


    <div class="place_find">
        <input type="hidden" name="id_bd" id="id_bd" value="<?= $sketches_main_info['id'] ?>">
        <input type="hidden" name="id_order" id="id_order" value="<?= $sketches_main_info['id_order'] ?>">
        <input type="hidden" name="room" id="room" value="<?= $sketches_main_info['room'] ?>">
        <input type="hidden" name="page_id" id="page_id" value="<?= $sketches_main_info['page'] ?>">
        <input type="hidden" name="specification" id="specification" value="<?= $sketches_main_info['specification'] ?>">
        <button class="common_button" id="save_img">поменять</button>
    </div>


    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
  
</html>
