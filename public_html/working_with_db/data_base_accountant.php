<?php

#==============================================================
# Страница для вывода информации по типам товаров и информации о них и поиску
#==============================================================



session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


require_once '../connect_to_database.php';

// отслеживаем через какую таблицу работаем
if(isset($_GET['tb'])){
    $tb = $_GET['tb'];


    if($tb == 1) {
        $search = "Ткани";
        $sql = "SELECT * FROM `cloth`";
        $ft_select_1 = "title";
        $ft_select_2 = "collection";
        $name = 'cloth';
        $mas_1 = ['id', 'Артикул', 'Размер', 'Вертикальный', 'Горизонтальный', 'Состав', 'Утяжелитель', 'Цена за рулон', 'Коллекция', 'Поставщик', 'Валюта'];
        $mas_2 = ['id', 'title', 'width', 'vertical', 'horizontal', 'compound', 'weighter', 'price', 'collection', 'provider', 'currency'];
    } else if($tb == 2) {
        $search = "Карниз";
        $sql = "SELECT * FROM `cornices`";
        $ft_select_1 = "title";
        $ft_select_2 = "type";
        $name = 'cornices';
        $mas_1 = ['id', 'Артикул', 'Цена', 'Тип', 'Валюта', 'Поставщик'];
        $mas_2 = ['id', 'title', 'price', 'type', 'currency', 'provider'];
    }else if($tb == 3) {
        $search = "Жалюзи";
        $sql = "SELECT * FROM `blinds`";
        $ft_select_1 = "title";
        $ft_select_2 = "type";
        $name = 'blinds';
        $mas_1 = ['id', 'Артикул', 'Высота', 'Ширина', 'Тип', 'Цвет', 'Цена', 'Валюта', 'Поставщик'];
        $mas_2 = ['id', 'title', 'height', 'width', 'type', 'color', 'price', 'currency', 'provider'];
    }else if($tb == 4) {
        $search = "Фурнитура";
        $sql = "SELECT * FROM `furniture`";
        $ft_select_1 = "title";
        $ft_select_2 = "collection";
        $name = 'furniture';
        $mas_1 = ['id', 'Артикул', 'Коллекция', 'Цена', 'Цена_опт', 'Поставщик', 'Валюта'];
        $mas_2 = ['id', 'title', 'collection', 'price', 'price_opt', 'provider', 'currency'];
    }else if($tb == 'services') {
        $search = "Услуги";
        $sql = "SELECT * FROM `services`";
        $ft_select_1 = "title";
        $ft_select_2 = "type";
        $name = 'services';
        $mas_1 = ['id', 'Артикул', 'Цена', 'Тип', 'Валюта'];
        $mas_2 = ['id', 'title', 'price', 'type', 'currency'];
    }
}

require_once "../global_functions.php";

?>


<!DOCTYPE html>
<html>
<head>




<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">

<!--    <link rel="stylesheet" href="../assets/css/style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->
<!--    <link rel="stylesheet" href="../assets/css/updata_accountant.css">-->
<!--    <link rel="stylesheet" href="../assets/css/life_search.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_messag.css">-->
<!--    <link rel="stylesheet" href="../assets/css/full_info_css.css">-->


    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/common_life_search.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_create_order.css">
    <link rel="stylesheet" href="../assets/css/style_for_work_with_db/common_style_for_work_with_db.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">



    <title>Пример веб-страницы</title>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
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


        <?php
        if(($_SESSION['state'] == 'admin' OR $_SESSION['state'] == 'designer' OR $_SESSION['state'] == 'workshop') AND !empty($_SESSION['user'])) {
            include('header_for_db.php');
        }
        ?>


        <div class="db_main_body">


        <form method="post" class="room_list_choice_db">
            <div><a href="data_base_accountant.php?tb=1" class="common_button_room_list_db">База данных ткани</a></div>
            <div><a href="data_base_accountant.php?tb=2" class="common_button_room_list_db">База данных карнизов</a></div>
            <div><a href="data_base_accountant.php?tb=3" class="common_button_room_list_db">База данных жалюзи</a></div>
            <div><a href="data_base_accountant.php?tb=4" class="common_button_room_list_db">База данных фурнитур</a></div>
            <div><a href="data_base_accountant.php?tb=services" class="common_button_room_list_db">База данных услуг</a></div>
        </form>



        <?php
            if(isset($tb) and $tb != 0){
        ?>
        <div>
            <h1>Поиск <?=$search?>:</h1>
            <br>
            <!-- Для живого поиска -->
            <script src="https://code.jquery.com/jquery-2.1.0.js"></script>
            <div
                    class='hidden'
                    data-lat='<?= $name ?>'
            ></div>
            <script>
                var tbname = $('div.hidden').data('lat');
                $(document).ready(function(){
                    $("#search").keyup(function(){
                        $.ajax({
                            url: "../life_search.php",
                            type: "POST",
                            data: {
                                value:  $('#search').val(),
                                name: tbname
                            },
                            success: function(result){
                                $("#result").html(result);
                            }});
                    });
                });
            </script>
            <form method="post">
            <div class="test_life">
                <input type="text" id="search" class="test_life_1_common">
                <input type="submit" value="Найти" class="common_button">
                <br>
                <div class="bloc">
                    <select id="result" size="10" name="city" class="test_life_2_common">
                    </select>
                </div>
            </div>
            </form>




            <table class="table_title_info">
                <?php
                if(!empty($_POST['city'])) {
                    ?>
                    <tr>
                    <?php
                        foreach ($mas_1 as $value) {
                    ?>
                        <th class="th_title_info"> <?= $value ?> </th>
                        <?php
                            }
                        ?>
                    </tr>
                    <tbody>
                    <?php
                    $search_get = $_POST['city'];
                    $sql_2 = "SELECT * FROM `$name` WHERE `id` = '$search_get'";
                    $select = mysqli_query($connect, $sql_2);
                    if (!empty($search_get)) {
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <tr>
                                <?php
                            foreach ($mas_2 as $value) {
                                if($value == 'price'){
                                    ?>
                                    <td class="tb_title_info" style="background-color: #bdbcbc">
                                        <?= show_normal_price($connect, $select_while['price'],
                                                      $select_while['provider'], $select_while['currency'], $name) ?>
                                    </td>
                                    <?php
                                }else{
                                    ?>
                                    <td class="tb_title_info" style="background-color: #bdbcbc"><?= $select_while[$value] ?></td>
                                    <?php
                                }
                            }
                                ?>
                            </tr>

                            <?php
                        }
                    }
                    else{
                        die('error');
                    }
                }
                ?>
                <tbody>
            </table>

        </div>

        <?php
            }
        ?>
        </div>



    </div>

</body>
</html>

