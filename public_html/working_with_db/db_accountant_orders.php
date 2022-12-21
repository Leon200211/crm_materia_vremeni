<?php


#==============================================================
# Страница для вывода информации по заказам
#==============================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}



if ($_SESSION['state'] == 'admin'){
    $mas_1 = ['№', 'Имя<br>заказчика', 'Телефон<br>заказчика', 'Почта<br>заказчика', 'Адрес<br>заказчика', 'Дизайнер', 'Салон', 'Розовая<br>страница', 'Статус'];
    $mas_2 = ['id_pink_order', 'customer_name', 'customer_phone', 'email', 'address_additional', 'executor_id', 'salon'];
} else {
    $mas_1 = ['№', 'Имя<br>заказчика', 'Телефон<br>заказчика', 'Почта<br>заказчика', 'Адрес<br>заказчика', 'Дизайнер', 'Салон', 'Розовая<br>страница', 'Статус'];
    $mas_2 = ['id_pink_order', 'customer_name', 'customer_phone', 'email', 'address_additional', 'executor_id', 'salon'];
}

require_once "../global_functions.php";

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">





    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/common_life_search.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_create_order.css">
    <link rel="stylesheet" href="../assets/css/style_for_work_with_db/common_style_for_work_with_db.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">


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
    <?php
    if(($_SESSION['state'] == 'admin' OR $_SESSION['state'] == 'designer' OR $_SESSION['state'] == 'workshop') AND !empty($_SESSION['user'])) {
        include('header_for_db.php');
    }
    ?>


    <div class="db_main_body">
        <h1>Поиск заказа:</h1>
        <form method="post">
            <input type="search" name="search" placeholder="Поиск..." class="input_style">
            <input type="submit" class="common_button">
        </form>

        <?php
        require_once '../connect_to_database.php';
        if(!empty($_POST['search'])) {
            ?>


        <br>
        <br>
        <br>
        <table class="sortable">
            <thead>
            <tr>
                <?php
                foreach ($mas_1 as $value) {
                    ?>
                    <th class="th_title_info"> <?= $value ?> </th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>

                <?php
                $search_get = $_POST['search'];
                if($search_get == 'all_all' and $_SESSION["state"] == 'admin'){
                    $sql = "SELECT * FROM `orders_main_info`";
                }
                else if ($search_get == 'all' and $_SESSION["state"] == 'admin'){
                    $sql = "SELECT * FROM `orders_main_info` WHERE `pink_state` != 'Завершен'";
                }
                else if ($_SESSION["state"] == 'admin' and !empty($search_get)){
                    $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'";
                }
                else if ($search_get == 'all' and $_SESSION["state"] == 'designer'){
                    $name = $_SESSION["id_user"];
                    $sql = "SELECT * FROM `orders_main_info` WHERE `executor_id` = '$name'";
                }
                else if (!empty($search_get) and $_SESSION["state"] == 'designer'){
                    $name = $_SESSION["id_user"];
                    $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get' AND `executor_id` = '$name'";
                }
                else if ($search_get == 'all' and $_SESSION["state"] == 'workshop'){
                    $id_executor = $_SESSION['id_user'];
                    $sql = "SELECT * FROM `orders_main_info` WHERE `executor_id` = '$id_executor' OR `pink_state` = 'Поступил в цех' OR `pink_state` = 'Пошив' OR `pink_state` = 'Ожидание отправки'";
                }
                else if (!empty($search_get) and $_SESSION["state"] == 'workshop'){
                    $id_executor = $_SESSION['id_user'];
                    $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get' AND (`executor_id` = '$id_executor' OR `pink_state` = 'Поступил в цех' OR `pink_state` = 'Пошив' OR `pink_state` = 'Ожидание отправки')";
                }


                $select_1 = mysqli_query($connect, $sql);
                $select_while_1 = mysqli_fetch_assoc($select_1);



                if (!empty($search_get) and $select_while_1 != NULL) {
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                                ?>
                            <tr>
                                <?php
                                foreach ($mas_2 as $value) {
                                    if($value == 'executor_id'){
                                        ?>
                                        <td class="tb_title_info"><?= get_designer_name($connect, $select_while[$value]) ?></td>
                                        <?php
                                    }else{
                                        ?>
                                        <td class="tb_title_info"><?= $select_while[$value] ?></td>
                                        <?php
                                    }
                                }
                                ?>
                                <td class="tb_title_info"><a class="pdf_href" href="<?="../assets/pdf_file/"  . $select_while['pink_image']?>?buster=<?= time() ?>"  target='_blank' > <?= $select_while['pink_image'] ?> </a></td>
                                <td class="tb_title_info"><?= $select_while['pink_state'] ?></td>
                            </tr>
                                <?php
                            }
                } else {
                    die("Такого заказа не существует");
                }
            }

                ?>
            </tbody>
        </table>
        <link href="../assets/for_sorted_table/sortable.css" rel="stylesheet" />
        <script src="../assets/for_sorted_table/sortable.js"></script>

    </div>


</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</body>
</html>

