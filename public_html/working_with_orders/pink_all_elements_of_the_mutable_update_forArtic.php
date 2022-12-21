<?php


#=======================================================
# Выбор изменения именно товара(а не информации о нем) в заказе переход в pink_all_elements_of_the_mutable_update_forArtic_main.php
#=======================================================



session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';


//  -------------------------------
//  для проверки на создание заказа
$get_id = $_GET['id'];
$orders_test = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id` = '$get_id'");
$select_test_1 = mysqli_fetch_assoc($orders_test);
$id_order = $select_test_1['id_pink_order'];

$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_order'";
$select_test_2 = mysqli_query($connect, $sql);
$select_test_2 = mysqli_fetch_assoc($select_test_2);

if($select_test_2['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------



$get_id = $_GET['id'];
$orders = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id` = '$get_id'");
$orders = mysqli_fetch_assoc($orders);


// в зависимости от типа ткани
// будет вывод из определенной базы данных
$table = $orders['category'];
if($table == 'cloth') {
    $sql = "SELECT * FROM `cloth`";
    $from = 'cloth';
    $ft_select_1 = "title";
    $ft_select_2 = "collection";
    $name = 'cloth';
    $mas_1 = ['id', 'Артикул', 'Размер', 'Вертикальный', 'Горизонтальный', 'Состав', 'Утяжелитель', 'Цена за рулон', 'Коллекция', 'Поставщик'];
    $mas_2 = ['id', 'title', 'width', 'vertical', 'horizontal', 'compound', 'weighter', 'price', 'collection', 'provider'];
}else if($table == 'cornices') {
    $search = "Карниз";
    $sql = "SELECT * FROM `cornices`";
    $from = 'cornices';
    $ft_select_1 = "title";
    $ft_select_2 = "type";
    $name = 'cornices';
    $mas_1 = ['id', 'Артикул', 'Цена', 'Тип', 'Валюта', 'Поставщик'];
    $mas_2 = ['id', 'title', 'price', 'type', 'currency', 'provider'];
}else if($table == 'blinds') {
    $search = "Жалюзи";
    $sql = "SELECT * FROM `blinds`";
    $from = 'blinds';
    $ft_select_1 = "title";
    $ft_select_2 = "type";
    $name = 'blinds';
    $mas_1 = ['id', 'Артикул', 'Высота', 'Ширина', 'Тип', 'Цвет', 'Цена', 'Валюта', 'Поставщик'];
    $mas_2 = ['id', 'title', 'height', 'width', 'type', 'color', 'price', 'currency', 'provider'];
}else if($table == 'furniture') {
    $search = "Фурнитура";
    $sql = "SELECT * FROM `furniture`";
    $ft_select_1 = "title";
    $ft_select_2 = "collection";
    $name = 'furniture';
    $from = 'furniture';
    $mas_1 = ['id', 'Артикул', 'Коллекция', 'Цена', 'Цена_опт', 'Поставщик', 'Валюта'];
    $mas_2 = ['id', 'title', 'collection', 'price', 'price_opt', 'provider', 'currency'];
}else if ($table == 'services') {
    $search = "услуг";
    $sql = "SELECT * FROM `services`";
    $ft_select_1 = "title";
    $ft_select_2 = "type";
    $from = 'services';
    $name = 'services';
    $mas_1 = ['id', 'Название', 'Цена', 'Тип', 'Валюта'];
    $mas_2 = ['id', 'title', 'price', 'type', 'currency'];
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
    <link rel="stylesheet" href="../assets/css/style_pink_page/update_pink_page.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_work_with_pink_page.css">
    <link rel="stylesheet" href="../assets/css/common_styles/common_life_search.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_create_order.css">
    <link rel="stylesheet" href="../assets/css/style_for_work_with_db/common_style_for_work_with_db.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">


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
            Заказы / Заказ № <?= $id_order ?>
        </div>
        <a href="pink_all_elements_of_the_mutable_update.php?id=<?= $get_id ?>" class="common_back_href">Назад</a>
    </div>


    <div class="main_field_div">
        <h1>Поиск ткани:</h1>
        <!-- Для живого поиска -->
        <script src="https://code.jquery.com/jquery-2.1.0.js"></script>
        <div
                class='hidden'
                data-lat='<?= $from ?>'
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
                <input type="text" id="search" class="input_style">
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
                <?php
                $search_get = $_POST['city'];
                $sql_2 = "SELECT * FROM `$from` WHERE `id` = '$search_get'";
                $select = mysqli_query($connect, $sql_2);
                if (!empty($search_get)) {
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        ?>
                        <tr>
                            <?php
                            foreach ($mas_2 as $value) {
                                ?>
                                <td class="tb_title_info"><?= $select_while[$value] ?></td>
                                <?php
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
        </table>
    </div>
</div>






<div class="common_div_body">
    <div class="main_field_div">
        <form action="pink_all_elements_of_the_mutable_update_forArtic_main.php" method="post" >
            <input type="hidden" name="id" value="<?= $get_id ?>">
            <div class="live_search_new">


                <!-- Для живого поиска -->
                <script src="https://code.jquery.com/jquery-2.1.0.js"></script>
                <div
                        class='hidden'
                        data-lat='<?= $from ?>'
                ></div>
                <script>
                    var tbname = $('div.hidden').data('lat');
                    $(document).ready(function(){
                        $("#search_2").keyup(function(){
                            $.ajax({
                                url: "../life_search.php",
                                type: "POST",
                                data: {
                                    value:  $('#search_2').val(),
                                    name: tbname
                                },
                                success: function(result){
                                    $("#result_2").html(result);
                                }});
                        });
                    });
                </script>
                <div class="test_life">
                    <input type="text" id="search_2" class="input_style">
                    <br>
                    <div class="bloc">
                        <select id="result_2" size="10" name="description" class="test_life_2_common">
                        </select>
                    </div>
                </div>



            </div>
            <button type="submit" class="common_button">Поменять</button>
        </form>
    </div>
</div>


</body>