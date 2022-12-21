<?php#=========================================================# страница вывода списка всех заказов для работы с эскизами#=========================================================session_start();if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){    echo "Доступ запрещен";    die;}require_once "../global_functions.php";?><!DOCTYPE html><html><head>    <meta name="viewport" content="width=device-width, initial-scale=1">    <meta charset="utf-8">    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_create_order.css">    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">    <link rel="stylesheet" href="../assets/css/style_sketches/select_order_sketches.css">    <title>Пример веб-страницы</title>    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>    <script src="assets/script/paint.js" defer></script>    <script src="../assets/script/app.js" defer></script></head><body style="background-color: #F3F3F3"><header class="header">    <?php    include('../header.php');    ?></header><div class="common_div_body">    <div class="block_select_order">        <h1>Поиск заказа:</h1>        <form method="post">            <input type="search" name="search" placeholder="Поиск..." class="input_style">            <input type="submit" class="common_button" value="Показать">        </form>        <?php        require_once '../connect_to_database.php';        if(!empty($_POST['search'])) {            ?>    <table class="table_title_info">        <tr>            <th class="th_title_info">id</th>            <th class="th_title_info">Номер заказа</th>            <th class="th_title_info">Дата</th>            <th class="th_title_info">ФИО заказчика</th>            <th class="th_title_info">Телефон</th>            <th class="th_title_info">Адрес</th>            <th class="th_title_info">Исполнитель</th>            <th class="th_title_info">Салон</th>            <th class="th_title_info">Файл</th>            <th class="th_title_info">Статус</th>        </tr>        <tbody>        <?php            $search_get = $_POST['search'];            if($search_get == 'all_all' and $_SESSION["state"] == 'admin'){                $sql = "SELECT * FROM `orders_main_info`";            }            else if ($search_get == 'all' and $_SESSION["state"] == 'admin'){                $sql = "SELECT * FROM `orders_main_info` WHERE `pink_state` = 'Создание эскизов'";            }            else if ($_SESSION["state"] == 'admin' and !empty($search_get)){                $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'";            }            else if ($search_get == 'all' and ($_SESSION["state"] == 'designer' or $_SESSION['state'] == 'workshop')){                $name = $_SESSION["id_user"];                $sql = "SELECT * FROM `orders_main_info` WHERE `executor_id` = '$name' AND (`pink_state` = 'Создание эскизов' OR `pink_state` = 'Перевыбор ткани в салоне' OR `pink_state` = 'Возврат дизайнеру' OR `pink_state` = 'Возврат ткани')";            }            else if (!empty($search_get) and ($_SESSION["state"] == 'designer' or $_SESSION['state'] == 'workshop')){                $name = $_SESSION["id_user"];                $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get' AND `executor_id` = '$name' AND (`pink_state` = 'Создание эскизов' OR `pink_state` = 'Перевыбор ткани в салоне' OR `pink_state` = 'Возврат дизайнеру' OR `pink_state` = 'Возврат ткани')";            }            $select_1 = mysqli_query($connect, $sql);            $select_while_1 = mysqli_fetch_assoc($select_1);            if (!empty($search_get) and $select_while_1 != NULL) {                $select = mysqli_query($connect, $sql);                while ($select_while = mysqli_fetch_assoc($select)) {                    // работа с датой                    $id_order = $select_while['id_pink_order'];                    $sql_date = "SELECT `date_create` FROM `orders_date` WHERE `id_order` = '$id_order'";                    $select_date = mysqli_query($connect, $sql_date);                    $select_date = mysqli_fetch_assoc($select_date);                    $data = DateTime::createFromFormat('Y-m-d', $select_date['date_create']);                    $data = $data->format('d-m-Y');                        ?>                    <tr>                        <td class="tb_title_info"><?= $select_while['id'] ?></td>                        <td class="tb_title_info"><?= $select_while['id_pink_order'] ?></td>                        <td class="tb_title_info"><?= $data ?></td>                        <td class="tb_title_info"><?= $select_while['customer_name'] ?></td>                        <td class="tb_title_info"><?= $select_while['customer_phone'] ?><br><?= $select_while['email'] ?></td>                        <td class="tb_title_info"><?= $select_while['address_additional'] ?></td>                        <td class="tb_title_info"><?= get_designer_name($connect, $select_while['executor_id']) ?></td>                        <td class="tb_title_info"><?= $select_while['salon'] ?></td>                        <td class="tb_title_info"><a class="pdf_href" href="<?="assets/pdf_file/"  . $select_while['pink_image']?>?buster=<?= time() ?>"  target='_blank' > <?= $select_while['pink_image'] ?> </a></td>                        <?php                        if($select_while['pink_state'] == 'Создание эскизов'){                            ?>                            <td class="tb_title_info"><?= $select_while['pink_state'] ?></td>                            <?php                        }else {                            ?>                            <td class="tb_title_info" style="color: red"><?= $select_while['pink_state'] ?></td>                            <?php                        }                        ?>                        <td class="tb_title_info"><a href="sketches_room.php?id_pink_order=<?= $select_while['id_pink_order'] ?>" class="common_back_href">Изменить</a></td>                        <!-- Убрал функцию удаления <td class="tb_title_info"><a href="#" class="edit_db_accountant">Удалить</a></td> -->                    </tr>                        <?php                }            }            else{                die("Заказ не найден");            }        }        ?>        </tbody>    </table>    </div></div><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script></body></html>