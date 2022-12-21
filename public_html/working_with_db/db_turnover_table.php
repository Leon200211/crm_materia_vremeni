<?php


#==============================================================
# Для отображения текущего состояния заказов
#==============================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}




if ($_SESSION['state'] == 'admin'){
    $mas_1 = ['№', 'Создание', 'Поступление в цех р.с.', 'Поступление эскиза в цех', 'Пошить до', 'Наличие тканей %',
        'Стоимость пошива салон', 'Стоимость пошива цех', 'стоимость цех нов пр', 'Дата выдачи закройщику', 'Исполнитель',
        'Дата запуска заказа в работу', 'Примечание к заказу', 'Дата заметки', 'Фактическая дата готовности',
        'Дата отправки', 'Курьер'];
    $mas_2 = ['id_pink_order', 'date_create', 'pink_page_arrival', 'sketches_arrival', 'date_end_designer', '%',
        'total_cost', 'workshop_cost_sewing', 'workshop_cost', 'date_delivery_cutter', 'performer',
        'date_start_work', 'note', 'date_note', 'date_end',
        'departure_date', 'courier'];
} else if($_SESSION['state'] == 'designer'){
    $mas_1 = ['№', 'Наличие тканей %', 'Дата выдачи закройщику', 'Исполнитель',
        'Дата запуска заказа в работу', 'Примечание к заказу', 'Дата заметки', 'Фактическая дата готовности',
        'Дата отправки', 'Курьер'];
    $mas_2 = ['id_pink_order', '%', 'date_delivery_cutter', 'performer',
        'date_start_work', 'note', 'date_note', 'date_end',
        'departure_date', 'courier'];
} else if($_SESSION['state'] == 'workshop') {
    $mas_1 = ['№', 'Создание', 'Поступление в цех р.с.', 'Поступление эскиза в цех', 'Пошить до', 'Наличие тканей %',
        'Стоимость пошива салон', 'Стоимость пошива цех', 'стоимость цех нов пр', 'Дата выдачи закройщику', 'Исполнитель',
        'Дата запуска заказа в работу', 'Примечание к заказу', 'Дата заметки', 'Фактическая дата готовности',
        'Дата отправки', 'Курьер'];
    $mas_2 = ['id_pink_order', 'date_create', 'pink_page_arrival', 'sketches_arrival', 'date_end_designer', '%',
        'total_cost', 'workshop_cost_sewing', 'workshop_cost', 'date_delivery_cutter', 'performer',
        'date_start_work', 'note', 'date_note', 'date_end',
        'departure_date', 'courier'];
}

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/global_functions.php';

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">





    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_create_order.css">
    <link rel="stylesheet" href="../assets/css/style_for_work_with_db/common_style_for_work_with_db.css">


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
        <h1>Текучка по заказам:</h1>
        <form method="post">
            <input type="search" name="search" placeholder="Номер заказа" class="input_style">
            <input type="submit" class="common_button">
        </form>

        <?php
        require_once '../connect_to_database.php';


        if(!empty($_POST['search'])) {
        ?>


            <br>
            <br>
            <br>
        <div class="big-table">
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
                    <th class="th_title_info">Документ</th>
                    <th class="th_title_info">Статус</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $search_get = $_POST['search'];
                if($search_get == 'all_all' and $_SESSION["state"] == 'admin'){
                    $sql = "SELECT *
                            FROM orders_main_info
                            INNER JOIN orders_date
                            ON orders_main_info.id_pink_order = orders_date.id_order
                            INNER JOIN turnover_table
                            ON orders_date.id_order = turnover_table.id_order";
                }
                else if ($search_get == 'all' and $_SESSION["state"] == 'admin'){
                    $sql = "SELECT *
                            FROM orders_main_info
                            INNER JOIN orders_date
                            ON orders_main_info.id_pink_order = orders_date.id_order
                            INNER JOIN turnover_table
                            ON orders_date.id_order = turnover_table.id_order
                            WHERE orders_main_info.pink_state != 'Завершен'";
                }
                else if ($_SESSION["state"] == 'admin' and !empty($search_get)){
                    $sql = "SELECT *
                            FROM orders_main_info
                            INNER JOIN orders_date
                            ON orders_main_info.id_pink_order = orders_date.id_order
                            INNER JOIN turnover_table
                            ON orders_date.id_order = turnover_table.id_order
                            WHERE id_pink_order = '$search_get'";
                }
                else if ($search_get == 'all' and $_SESSION["state"] == 'designer'){
                    $name = $_SESSION["id_user"];
                    $sql = "SELECT *
                            FROM orders_main_info
                            INNER JOIN orders_date
                            ON orders_main_info.id_pink_order = orders_date.id_order
                            INNER JOIN turnover_table
                            ON orders_date.id_order = turnover_table.id_order
                            WHERE `executor_id` = '$name'";
                }
                else if (!empty($search_get) and $_SESSION["state"] == 'designer'){
                    $name = $_SESSION["id_user"];
                    $sql = "SELECT *
                            FROM orders_main_info
                            INNER JOIN orders_date
                            ON orders_main_info.id_pink_order = orders_date.id_order
                            INNER JOIN turnover_table
                            ON orders_date.id_order = turnover_table.id_order
                            WHERE `id_pink_order` = '$search_get' AND `executor_id` = '$name'";
                }
                else if ($search_get == 'all' and $_SESSION["state"] == 'workshop'){
                    //$sql = "SELECT * FROM `orders_main_info` WHERE `pink_state` = 'Поступил в цех' OR `pink_state` = 'Пошив' OR `pink_state` = 'Ожидание отправки'";
                    $sql = "SELECT *
                            FROM orders_main_info
                            INNER JOIN orders_date
                            ON orders_main_info.id_pink_order = orders_date.id_order
                            INNER JOIN turnover_table
                            ON orders_date.id_order = turnover_table.id_order
                            WHERE orders_main_info.pink_state != 'Завершен'";
                }
                else if (!empty($search_get) and $_SESSION["state"] == 'workshop'){
                    //$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get' AND (`pink_state` = 'Поступил в цех' OR `pink_state` = 'Пошив' OR `pink_state` = 'Ожидание отправки')";
                    $sql = "SELECT *
                            FROM orders_main_info
                            INNER JOIN orders_date
                            ON orders_main_info.id_pink_order = orders_date.id_order
                            INNER JOIN turnover_table
                            ON orders_date.id_order = turnover_table.id_order
                            WHERE id_pink_order = '$search_get'";
                }


                // нужно для проверки наличия информации
                $select_1 = mysqli_query($connect, $sql);
                $select_while_1 = mysqli_fetch_assoc($select_1);



                if (!empty($search_get) and $select_while_1 != NULL) {
                    $select = mysqli_query($connect, $sql);
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        ?>
                        <tr>
                            <?php
                            foreach ($mas_2 as $value) {
                                if($value == '%'){

                                    // По возможности обьединить это в один подзапрос

                                    $id_order = $select_while['id_pink_order'];
                                    $sql_pr_1 = "SELECT COUNT(*) FROM description_of_pink_pages WHERE id_pink_order = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                                    $sql_pr_1 = mysqli_query($connect, $sql_pr_1);
                                    $sql_pr_1 = mysqli_fetch_assoc($sql_pr_1);
                                    $sql_pr_1 = $sql_pr_1['COUNT(*)'];
                                    $sql_pr_2 = "SELECT COUNT(*) FROM description_of_pink_pages WHERE id_pink_order = '$id_order' AND supplier_price != 0 AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                                    $sql_pr_2 = mysqli_query($connect, $sql_pr_2);
                                    $sql_pr_2 = mysqli_fetch_assoc($sql_pr_2);
                                    $sql_pr_2 = $sql_pr_2['COUNT(*)'];

                                    if($sql_pr_1 != 0){
                                        $res_pro = ($sql_pr_2/$sql_pr_1)*100;
                                    } else{
                                        $res_pro = 0;
                                    }

                                    ?>
                                    <td class="tb_title_info"><?= $res_pro ?>%</td>
                                    <?php
                                }else if($value == 'performer'){
                                    ?>
                                    <td class="tb_title_info"><?= get_performer_name($connect, $select_while['performer']) ?></td>
                                    <?php
                                }else if($value == 'total_cost'){
                                    ?>
                                    <td class="tb_title_info"><?= calculate_the_cost_of_sewing($connect, $select_while['id_pink_order']) ?></td>
                                        <?php
                                }else {
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
        </div>
        <link href="../assets/for_sorted_table/sortable.css" rel="stylesheet" />
        <script src="../assets/for_sorted_table/sortable.js"></script>

    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</body>


