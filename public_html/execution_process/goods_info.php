<?php


#==================================================================
# Страница отображения таблицы коофициентов переход на order_full_info_coefficient_
#==================================================================



session_start();

if(empty($_SESSION['user'])){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';

if(!empty($_GET['id'])) {
    $id = $_GET['id'];
} elseif(!empty($_POST['id'])){
    $id = $_POST['id'];
}



function convert($name){
    $rusMonthNames = [
        'cloth' => 'Ткань',
        'cornices' => 'Карниз',
        'blinds' => 'Жалюзи',
        'furniture' => 'Фурнитура',
        'services' => 'Услуга',
        'sewing' => 'Пошив',
        'modification' => 'Модификация'
    ];

    return $rusMonthNames[$name];
}

?>



<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Пример веб-страницы</title>

    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/other_features_from_status_page/style_for_with_defective.css">


<!--    <link rel="stylesheet" href="../assets/css/style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->
<!--    <link rel="stylesheet" href="../assets/css/updata_accountant.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_messag.css">-->
<!--    <link rel="stylesheet" href="../assets/css/full_info_css.css">-->



    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../assets/script/paint_update.js" defer></script>
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
        <a href="order_full_info_new.php?id=<?= $id ?>" class="common_back_href">Вернуться</a>
    </div>



    <div class="main_field_div">
        <div class="main_title">Информация по заказу №<?= $id ?></div>



        <div id="room_list" class="room_list">
            <?php
            $info_pink_order_room = mysqli_query($connect, "SELECT distinct `room` FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id' AND `room` != '--' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'");
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
                $info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id' AND `room` = '$room' and `category` != 'sewing' and `category` != 'modification'");
                $i = 0;


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
                <table class="table_title_info">
                    <h3>Комната: <?= $room ?></h3>
                    <tr>
                        <th class="th_title_info">Номер параграфа</th>
                        <th class="th_title_info">Тип</th>
                        <th class="th_title_info">Наименование</th>
                        <th class="th_title_info">Размер</th>
                        <th class="th_title_info">Количество</th>
                        <th class="th_title_info">Статус</th>
                    </tr>
                    <tbody>
                    <?php
                    while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
                        ?>
                        <tr>
                            <input type="hidden" name="id" value="<?= $info_pink_order_while['id'] ?>">
                            <input type="hidden" name="id_pink_order" value="<?= $info_pink_order_while['id_pink_order'] ?>">
                            <td class="tb_title_info"><?= $info_pink_order_while['id_paragraph'] ?></td>
                            <td class="tb_title_info"><?= convert($info_pink_order_while['category']) ?></td>
                            <td class="tb_title_info"><?= $info_pink_order_while['description'] ?></td>
                            <td class="tb_title_info"><?= $info_pink_order_while['size'] ?></td>
                            <td class="tb_title_info"><?= $info_pink_order_while['quantity'] ?></td>
                            <?php
                            if(!empty($info_pink_order_while['additional_info'])){
                                ?>
                                <td style="color:red" class="tb_title_info"><?= $info_pink_order_while['additional_info'] ?></td>
                                <?php
                            }else{
                                if($info_pink_order_while['supplier_price'] == 0){
                                    ?>
                                    <td class="tb_title_info">Ожидание доставки</td>
                                    <?php
                                }else{
                                    ?>
                                    <td style="color:green" class="tb_title_info">Доставлено</td>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                </div>
                <?php
            }
            ?>

    </div>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>