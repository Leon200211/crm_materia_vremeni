<?php


#===============================================================
# Страница отображения всех эскизов по определенном заказу
#===============================================================




session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';

//  -------------------------------
//  для проверки на создание заказа
$id_pink_order = $_GET['id_pink_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------



?>


<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">


<!--    <link rel="stylesheet" href="../assets/css/style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->


    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">
    <link rel="stylesheet" href="../assets/css/style_sketches/style_for_sketches.css">


    <title>Пример веб-страницы</title>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="assets/script/paint.js" defer></script>
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
            Заказы / Заказ № <?= $id_pink_order ?>
        </div>
        <a href="price_new.php" class="common_back_href">Назад</a>
    </div>



    <div class="main_field_div_sketches">



        <h3 class="update_title">Заказ № <?= $_GET['id_pink_order'] ?></h3>

        <div class="function_for_pdf">
            <div class="function_for_pdf_elem">
                <form method="post">
                    <a href="AAA_CREATE_SKETCHES.php?id=<?= $_GET['id_pink_order'] ?>" class="common_back_href">Сгенерировать PDF</a>
                </form>
            </div>
            <div class="function_for_pdf_elem">
                <a href="../assets/price_pdf/price_pdf_<?= $_GET['id_pink_order'] ?>.pdf?buster=<?= time() ?>"  target='_blank' class="common_back_href">Показать PDF</a>
            </div>
            <div class="function_for_pdf_elem_dow">
                <a class="doing_link" href="export_pdf_sketches.php?id=<?=$id_pink_order?>">
                    <img src="../assets/img_for_style/download.png" width="25" height="25">
                </a>
            </div>
        </div>





        <div id="room_list" class="room_list">
            <?php
            $info_pink_order_room = mysqli_query($connect, "SELECT distinct `room` FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `room` != '--'");
            $room_arr = [];
            while ($info_pink_order_room_while = mysqli_fetch_assoc($info_pink_order_room)) {
                $room_arr[] = $info_pink_order_room_while['room'];
                if(count($room_arr) == 1){
                    ?>
                    <button class="common_button_room_list show"><?=$info_pink_order_room_while['room']?></button>
                    <?php
                }else{
                    ?>
                    <button class="common_button_room_list"><?=$info_pink_order_room_while['room']?></button>
                    <?php
                }
            }
            ?>
        </div>




        <div class="main_field_div_for_room">
            <?php
            foreach ($room_arr as $room) {
                $info_pink_order = mysqli_query($connect, "SELECT * FROM `sketches_main` WHERE `id_order` = '$id_pink_order' AND `room` = '$room'");
                if ($room_arr[0] === $room) {
                    // это первая запись
                    ?>
                    <div class="room_main_info show">
                    <?php
                }else{
                    ?>
                    <div class="room_main_info">
                    <?php
                }
                ?>
                <br>
                <form method="post">
                    <table class="table_title_info">
                        <div class="table_room_name">
                            <div class="table_room_name title">
                                Комната:
                            </div>
                            <div class="table_room_name room">
                                <?= $room ?>
                            </div>
                        </div>

                <table class="table_title_info">
                    <tr>
        <!--                <th class="th_title_info">id</th>-->
        <!--                <th class="th_title_info">Номер заказа</th>-->
                        <th class="th_title_info">№ Страницы</th>
                        <th class="th_title_info">Спецификация</th>
                    </tr>
                    <tbody>
                    <?php
                    while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
                        $id_in_sketches = $info_pink_order_while['id'];
                    ?>
                        <tr>
                            <td class="tb_title_info"><?= $info_pink_order_while['page'] ?></td>
                            <td class="tb_title_info"><?= $info_pink_order_while['specification'] ?></td>
                            <td class="tb_title_info"><a href="sketches_room_update.php?id_in_sketches=<?= $id_in_sketches ?>" class="common_back_href">Изменить</a></td>
                            <td class="tb_title_info"><a href="sketches_room_delete.php?id_in_sketches=<?= $id_in_sketches ?>" class="common_back_href" onclick="return confirm('Удалить?');">Удалить</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>


                <div class="add_product">
                    <form method="post">
                        <a href="sketches_room_add.php?id_order=<?= $id_pink_order ?>&room=<?= $room ?>" class="common_button">Добавить</a>
                    </form>
                </div>
            </div>

            <?php
            }
            ?>

        <script>
            menu = document.getElementById("room_list");
            blocks = Array.from(document.getElementsByClassName("room_main_info"));
            lists = Array.from(menu.getElementsByClassName("common_button_room_list"));
            lists.forEach(element => element.onclick = function() {
                index = lists.indexOf(element);
                blocks.forEach(block => block.classList.remove("show"));
                blocks[index].classList.add("show");

                lists.forEach(block => block.classList.remove("show"));
                lists[index].classList.add("show");
            });
        </script>






    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</body>