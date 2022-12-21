<?php


#==================================================================
# Страница отображения таблицы коофициентов переход на order_full_info_coefficient_
#==================================================================




session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin')){
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


<!--    <link rel="stylesheet" href="../assets/css/style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->
<!--    <link rel="stylesheet" href="../assets/css/updata_accountant.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_messag.css">-->
<!--    <link rel="stylesheet" href="../assets/css/full_info_css.css">-->

    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/other_features_from_status_page/style_for_with_defective.css">



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

<?php
if(@!isset($_POST['supplier_price_0'])){
    ?>


<div class="common_div_body">

    <div>
        <a href="order_full_info_new.php?id=<?= $id ?>" class="common_back_href">Вернуться</a>
    </div>

<!--==================================================-->
<!--Вариант с изменением коофициента на этой странице -->
<!--==================================================-->
    <div class="main_field_div">

        <div class="main_title">Коэффициент по заказу №<?= $id ?></div>


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

//        $info_pink_order_room = mysqli_query($connect, "SELECT distinct `room` FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id' AND `room` != '--' and `category` != 'sewing' and `category` != 'modification'");
//        while ($info_pink_order_room_while = mysqli_fetch_assoc($info_pink_order_room)) {
//            $i = 0;
//            $room = $info_pink_order_room_while['room'];
//            $info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id' AND `room` = '$room' and `category` != 'sewing' and `category` != 'modification'");
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
            <tr>
                <th class="th_title_info">Номер параграфа</th>
                <th class="th_title_info">Тип</th>
                <th class="th_title_info">Наименование</th>
                <th class="th_title_info">Размер</th>
                <th class="th_title_info">Количество</th>
                <th class="th_title_info">Цена</th>
                <th class="th_title_info">Сумма</th>
                <th class="th_title_info">Цена<br>поставщика</th>
                <th class="th_title_info">Коэффициент</th>
            </tr>
            <tbody>
            <?php
            while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
                ?>
                <tr>
                    <td class="tb_title_info"><?= $info_pink_order_while['id_paragraph'] ?></td>
                    <td class="tb_title_info"><?= convert($info_pink_order_while['category']) ?></td>
                    <td class="tb_title_info"><?= $info_pink_order_while['description'] ?></td>
                    <td class="tb_title_info"><?= $info_pink_order_while['size'] ?></td>
                    <td class="tb_title_info"><?= $info_pink_order_while['quantity'] ?></td>
                    <td class="tb_title_info"><?= $info_pink_order_while['price'] ?></td>
                    <td class="tb_title_info"><?= $info_pink_order_while['quantity'] * $info_pink_order_while['price'] ?></td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="supplier_price_<?=$i?>" size="20" value="<?= $info_pink_order_while['supplier_price']?>"></p></td>



                    <?php
                    if($info_pink_order_while['supplier_price'] != 0){

                        // получили текущий коэффициент
                        $coff = round(($info_pink_order_while['quantity'] * $info_pink_order_while['price']) / $info_pink_order_while['supplier_price'], 3);

                        // получение табличного коэффициента
                        $sql_coff = "SELECT `coefficient` FROM `coefficients_table` WHERE `type` = '{$info_pink_order_while['category']}' 
                                     AND `provider` = '{$info_pink_order_while['provider']}'";
                        $table_coff = $connect->query($sql_coff);
                        $table_coff = $table_coff->fetch_all();
                        if(count($table_coff) == 1){
                            $table_coff = $table_coff[0][0];
                        }else if(count($table_coff) == 0){
                            $sql_coff = "SELECT `coefficient` FROM `coefficients_table` WHERE `type` = '{$info_pink_order_while['category']}' 
                                     AND `provider` = 'all_the_rest'";
                            $table_coff = $connect->query($sql_coff);
                            $table_coff = $table_coff->fetch_all();
                            if(count($table_coff) == 1){
                                $table_coff = $table_coff[0][0];
                            }else{
                                $table_coff = -1;
                            }
                        }else{
                            $table_coff = -1;
                        }



                        if($table_coff == -1){
                            ?>
                            <td style="color:black; background-color: #cd2323" align="center"><?= $table_coff ?></td>
                            <?php
                        } else if($coff >= $table_coff){
                            ?>
                            <td style="color:green" align="center"><?= $coff?></td>
                            <?php
                        } else {
                            ?>
                            <td style="color:black; background-color: #cd2323" align="center"><?= $coff?></td>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <td class="tb_title_info">-</td>
                        <?php
                    }
                    ?>





                    <input type="hidden" name="id_<?=$i?>" value="<?= $info_pink_order_while['id'] ?>">

                </tr>
                <?php
                $i++;
            }
            ?>
            </tbody>
        </table>
            <input type="hidden" name="id_pink_order" value="<?= $id ?>">
            <input type="hidden" name="i" value="<?= $i ?>">
            <td class="tb_title_info">
                <p><input class="common_button" type="submit" value="Изменить" onclick="return confirm('Подтверждаю');"></p>
            </td>
        </form>
        </div>
        <?php
        }
        ?>
        </div>


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

<?php
}
else{
    var_dump($_POST['id_pink_order']);
    for($i = 0; $i < $_POST['i']; $i++){
        if(!empty($_POST['id_' . $i]) and !empty($_POST['id_pink_order']) ){
            if(!isset($_POST['supplier_price_' . $i]) or ($_POST['supplier_price_' . $i]) < 0 or (!is_numeric($_POST['supplier_price_' . $i]))) {
                ?>
                <div class="body_result">
                    <div class="body_result_title">Ошибка ввода данных</div>
                    <a href="order_full_info_coefficient.php?id=<?= $_POST['id_pink_order'] ?>" class="common_back_href">Вернуться</a>
                </div>
                <?php
                die();
            }
        }
    }

    for($i = 0; $i < $_POST['i']; $i++) {
        $supplier_price = $_POST['supplier_price_' . $i];
        $id_in_db = $_POST['id_' . $i];
        mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `supplier_price` = '$supplier_price' WHERE `description_of_pink_pages`.`id` = '$id_in_db'");
    }

    ?>
    <div class="body_result">
        <div class="body_result_title">Цена поставщика успешно изменена</div>
        <a href="order_full_info_coefficient.php?id=<?= $_POST['id_pink_order'] ?>" class="common_back_href">Вернуться</a>
    </div>
    <?php
}
?>



</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>