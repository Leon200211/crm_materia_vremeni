<?php


#==============================================================
# Страница для вывода информации по заказам
#==============================================================





session_start();

if(empty($_SESSION['user']) OR $_SESSION['state'] != 'admin'){
    echo "Доступ запрещен";
    die;
}




?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">




    <link rel="stylesheet" href="../../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../../assets/css/common_styles/common_life_search.css">
    <link rel="stylesheet" href="../../assets/css/style_pink_page/style_for_create_order.css">
    <link rel="stylesheet" href="../../assets/css/style_for_work_with_db/common_style_for_work_with_db.css">
    <link rel="stylesheet" href="../../assets/css/common_styles/table_room_style.css">


    <title>Пример веб-страницы</title>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>

    <script src="../../assets/script/app.js" defer></script>
</head>

<body style="background-color: #F3F3F3">
<header class="header">
    <?php
    include('../../header.php');
    ?>
</header>


<div class="common_div_body">
    <?php
    if(($_SESSION['state'] == 'admin') AND !empty($_SESSION['user'])) {
        include('../header_for_db.php');
    }
    ?>


    <div class="db_main_body">
        <h1>Поиск писем по заказам:</h1>
        <form method="post">
            <input type="search" name="search" placeholder="Поиск..." class="input_style">
            <input type="submit" class="common_button">
        </form>

        <?php
        require_once '../../connect_to_database.php';
        if(!empty($_POST['search'])) {
        ?>


        <br>
        <br>
        <br>
        <table class="sortable"">
            <thead>
            <tr>
                <th class="th_title_info">№</th>
                <th class="th_title_info">Заказ №</th>
                <th class="th_title_info">Дата</th>
                <th class="th_title_info">Получатель</th>
                <th class="th_title_info">Тип</th>
                <th class="th_title_info">Содержимое</th>
                <th class="th_title_info">Файл</th>
            </tr>
            </thead>

            <?php
            $search_get = $_POST['search'];
            if($search_get == 'all' and $_SESSION["state"] == 'admin'){
                $sql = "SELECT * FROM `mail_messages`";
            }
            else if ($_SESSION["state"] == 'admin' and !empty($search_get)){
                $sql = "SELECT * FROM `mail_messages` WHERE `id_order` = '$search_get'";
            }



            $select_1 = mysqli_query($connect, $sql);
            $select_while_1 = mysqli_fetch_assoc($select_1);



            if (!empty($search_get) and $select_while_1 != NULL) {
                $select = mysqli_query($connect, $sql);
                while ($select_while = mysqli_fetch_assoc($select)) {
                    ?>
                    <tr>
                        <td class="tb_title_info"><?= $select_while['id'] ?></td>
                        <td class="tb_title_info"><?= $select_while['id_order'] ?></td>
                        <td class="tb_title_info"><?= $select_while['data'] ?></td>
                        <td class="tb_title_info"><?= $select_while['recipient'] ?></td>
                        <td class="tb_title_info"><?= $select_while['type'] ?></td>
                        <td class="tb_title_info"><?= $select_while['text'] ?></td>
                        <?php
                        if($select_while['file'] != '-'){
                            ?>
                            <td class="tb_title_info"><a class="pdf_href" href="<?="../" . $select_while['file']?>?buster=<?= time() ?>"  target='_blank'> <?= substr($select_while['file'], strrpos($select_while['file'], '/')+1) ?> </a></td>
                            <?php
                        }else{
                            ?>
                            <td class="tb_title_info"><?= $select_while['file'] ?></td>
                            <?php
                        }
                        ?>

                    </tr>
                    <?php
                }
            } else {
                die("Такого заказа не существует");
            }
            }

            ?>
        </table>
        <link href="../../assets/for_sorted_table/sortable.css" rel="stylesheet" />
        <script src="../../assets/for_sorted_table/sortable.js"></script>

    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</body>



