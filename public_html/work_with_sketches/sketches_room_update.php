<?php

#=====================================================
# Вся информация по одной страницы в эсказах
#=====================================================






session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';



if(!empty($_GET['id_in_sketches'])){
    $id_in_sketches = $_GET['id_in_sketches'];
} else if(!empty($_POST['id_in_sketches'])){
    $id_in_sketches = $_POST['id_in_sketches'];
}

// достаем инфу из sketches_main по ключу его id
$sql_sketches_main = "SELECT * FROM `sketches_main` WHERE `id` = '$id_in_sketches'";
$sketches_main = mysqli_query($connect, $sql_sketches_main);
$sketches_main = mysqli_fetch_assoc($sketches_main);



$id_order = $sketches_main['id_order'];
$room = $sketches_main['room'];
$page = $sketches_main['page'];
$specification = $sketches_main['specification'];

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
<!--    <link rel="stylesheet" href="../assets/css/style_messag.css">-->
<!--    <link rel="stylesheet" href="../assets/css/full_info_css.css">-->

    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">
    <link rel="stylesheet" href="../assets/css/style_sketches/style_for_sketches.css">



    <title>Пример веб-страницы</title>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="assets/script/paint.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="../assets/script/app.js" defer></script>
</head>

<body style="background-color: #F3F3F3">

<header class="header">
    <?php
    include('../header.php');
    ?>
</header>



<!--информация о разных товарах-->
<div class="common_div_body">

    <div>
        <div class="back_title">
            Заказы / Заказ № <?= $id_order ?>
        </div>
        <a href="sketches_room.php?id_pink_order=<?=$id_order?>" class="common_back_href">Назад</a>
    </div>

    <div class="main_field_div_sketches">
        <h1>Информация о позиции:</h1>
        <form method="post">
            <div class="live_search_all">
                <div class="live_search_new">
                    <select class="js-select2" name="city" placeholder="Выберите город">
                        <?php
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' and `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <option value="<?= $select_while['id'] . "!!!" . $select_while['category'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
<!--                    Для красивого отображения живого поиска-->
                    <link rel="stylesheet" href="../assets/plagin_2/select2-develop/dist/css/select2.min.css">
                    <!--<script src="/jquery.min.js"></script> -->
                    <script src="../assets/plagin_2/select2-develop/dist/js/select2.min.js"></script>
                    <script src="../assets/plagin_2/select2-develop/dist/js/i18n/ru.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('.js-select2').select2({
                                placeholder: "Выберите город",
                                maximumSelectionLength: 2,
                                language: "ru"
                            });
                        });
                    </script>
                </div>
                <input type="submit" class="common_button" value="Показать">
            </div>
        </form>
        <table class="table_title_info">
            <?php
            if(!empty($_POST['city'])) {
                // разбиваем содержимое поиска на id и тип данных
                $mas_i = explode("!!!", $_POST['city']);
                $main_info_from_search = $mas_i[0];
                $table = $mas_i[1];
                // ==============================
                // в зависимости от типа ткани
                // будет вывод из определенной базы данных
                // проверяем из какой таблицы будет вывод
                if($table == 'cloth') {
                    $from = 'cloth';
                    $ft_select_1 = "title";
                    $ft_select_2 = "collection";
                    $name = 'cloth';
                    $mas_test_1 = ['id', 'Артикул', 'Размер', 'Вертикальный', 'Горизонтальный', 'Состав', 'Утяжелитель', 'Цена за рулон', 'Коллекция', 'Поставщик'];
                    $mas_test_2 = ['id', 'title', 'width', 'vertical', 'horizontal', 'compound', 'weighter', 'price', 'collection', 'provider'];
                } else if($table == 'cornices') {
                    $from = 'cornices';
                    $ft_select_1 = "title";
                    $ft_select_2 = "type";
                    $name = 'cornices';
                    $mas_test_1 = ['id', 'Артикул', 'Цена', 'Тип', 'Валюта', 'Поставщик'];
                    $mas_test_2 = ['id', 'title', 'price', 'type', 'currency', 'provider'];
                }else if($table == 'blinds') {
                    $from = 'blinds';
                    $ft_select_1 = "title";
                    $ft_select_2 = "type";
                    $name = 'blinds';
                    $mas_test_1 = ['id', 'Артикул', 'Высота', 'Ширина', 'Тип', 'Цвет', 'Цена', 'Валюта', 'Поставщик'];
                    $mas_test_2 = ['id', 'title', 'height', 'width', 'type', 'color', 'price', 'currency', 'provider'];
                }else if($table == 'furniture') {
                    $from = 'furniture';
                    $ft_select_1 = "title";
                    $ft_select_2 = "collection";
                    $name = 'furniture';
                    $mas_test_1 = ['id', 'Артикул', 'Коллекция', 'Цена', 'Цена_опт', 'Поставщик', 'Валюта'];
                    $mas_test_2 = ['id', 'title', 'collection', 'price', 'price_opt', 'provider', 'currency'];
                }
                // ==============================
                ?>
                <tr>
                    <?php
                    foreach ($mas_test_1 as $value) {
                        ?>
                        <th class="th_title_info"> <?= $value ?> </th>
                        <?php
                    }
                    ?>
                </tr>
                <?php

                // по id из поиска достаем описание
                $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id` = '$main_info_from_search'";
                $select_table = mysqli_fetch_assoc(mysqli_query($connect, $sql));

                $mas_2 = [];
                $mas_i = explode("|", $select_table['description']);
                if (count($mas_i) > 1) {
                    for ($i = 0; $i < count($mas_i); $i++) {
                        array_push($mas_2, $mas_i[$i]);
                    }
                } else {
                    array_push($mas_2, $select_table['description']);
                }
                // проверка на пустые значения
                if($mas_2[0] == ''){
                    $sql = "SELECT * FROM `$from` WHERE `$ft_select_1`  IS NULL AND `$ft_select_2` = '$mas_2[1]'";
                } else if($mas_2[1] == '') {
                    $sql = "SELECT * FROM `$from` WHERE `$ft_select_1` = '$mas_2[0]' AND `$ft_select_2` IS NULL";
                }
                else {
                    $sql = "SELECT * FROM `$from` WHERE `$ft_select_1` = '$mas_2[0]' AND `$ft_select_2` = '$mas_2[1]'";
                }
                $select = mysqli_query($connect, $sql);
                while ($select_while = mysqli_fetch_assoc($select)) {
                    ?>
                    <tr>
                        <?php
                        foreach ($mas_test_2 as $value) {
                            if($value == 'price'){
                                ?>
                                <td class="tb_title_info">
                                    <?= show_normal_price($connect, $select_while['price'],
                                        $select_while['provider'], $select_while['currency'], $from) ?>
                                </td>
                                <?php
                            }else{
                                ?>
                                <td class="tb_title_info"><?= $select_while[$value] ?></td>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

    </div>


</div>














<?php



if($specification == 'портьеры|тюли|подхваты|тп') {
    $from = 'sketches_1';
    $mas_test_1 = ['№', 'Изделия', 'Количество шт.', 'Ширина сбор. Виде (см.)', 'Коэф. сборки', 'Ширина в разверн. виде (крой) в см', 'Высота в см.', 'Гребешок', 'Основная ткань', 'Кол-во', 'Подкладка', 'Кол-во', 'Отделка', 'Кол-во', 'Низ в см.', 'Бока в см.', 'Тех-загиб бок. в см.', 'шт. лента'];
    $mas_test_2 = ['id_paragraph', 'vendor_code', 'count', 'assembled_width', 'coefficient', 'unfolded_width', 'height', 'scallop', 'main_cloth', 'm_count', 'lining', 'l_count', 'finishing', 'f_count', 'bottom', 'sides', 'bend', 'ribbon'];
} else if ($specification == 'римские|франц|австрийск|тп') {
    $from = 'sketches_2';
    $mas_test_1 = ['№', 'Изделия', 'Количество шт.', 'Ширина по карнизу (см)', 'Высота изделия (см)', 'Гребешок', 'Основная ткань', 'Кол-во', 'Подкладка', 'Кол-во', 'Отделка', 'Кол-во', 'Кол-во. фибергласовых (шт)', 'Кол-во барабанов (шт)'];
    $mas_test_2 = ['id_paragraph', 'vendor_code', 'count', 'eaves_width', 'height', 'scallop', 'main_cloth', 'm_count', 'lining', 'l_count', 'finishing', 'f_count', 'count_fib', 'count_drums'];
}else if ($specification == 'покрывала') {
    $from = 'sketches_3';
    $mas_test_1 = ['№', 'Изделия', 'Количество шт.', 'Ширина габаритная (см)', 'Длина габаритная (см)', 'Стежка (цех или анита)', 'Наименование стежки', 'Шаг стежки', 'Основная ткань', 'Кол-во', 'Подкладка', 'Кол-во', 'Отделка', 'Кол-во', 'Края (подборт или мешком)', 'Синтепон (100/200г)', 'Радиус закругления или прям. Угол'];
    $mas_test_2 = ['id_paragraph', 'vendor_code', 'count', 'width', 'length', 'stitch', 'stitch_name', 'stitch_step', 'main_cloth', 'm_count', 'lining', 'l_count', 'finishing', 'f_count', 'edges', 'centipone', 'corner'];
}else if ($specification == 'подушки|наволочки|валики') {
    $from = 'sketches_4';
    $mas_test_1 = ['№', 'Изделия', 'Количество шт.', 'Ширина габаритная / диаметр валика (см)', 'Длина габаритная (см)', 'Основная ткань', 'Кол-во', 'Внутрення подушка/чехол', 'Кол-во', 'Отделка', 'Кол-во'];
    $mas_test_2 = ['id_paragraph', 'vendor_code', 'count', 'width', 'length', 'main_cloth', 'm_count', 'pillow', 'p_count', 'finishing', 'f_count'];
}else if ($specification == 'сваги|джаботы|ламбрикены') {
    $from = 'sketches_5';
    $mas_test_1 = ['№', 'Изделия', 'Количество шт.', 'Ширина по карнизу (см)', 'Высота в см', 'Обработка низа', 'Липучка', 'Термобандо', 'Основная ткань', 'Кол-во', 'Подкладка', 'Кол-во', 'Отделка', 'Кол-во', 'Шт. лента'];
    $mas_test_2 = ['id_paragraph', 'vendor_code', 'count', 'eaves_width', 'height', 'bottom_processing', 'velcro', 'thermobando', 'main_cloth', 'm_count', 'lining', 'l_count', 'finishing', 'f_count', 'ribbon'];
}else if ($specification == 'скатерти|салфетки') {
    $from = 'sketches_6';
    $mas_test_1 = ['№', 'Изделия', 'Количество шт.', 'Ширина габаритная / диаметр валика (см)', 'Длина габаритная (см)', 'Основная ткань', 'Кол-во', 'Подкладка', 'Кол-во', 'Отделка', 'Кол-во', 'Обработка края'];
    $mas_test_2 = ['id_paragraph', 'vendor_code', 'count', 'width', 'length', 'main_cloth', 'm_count', 'lining', 'l_count', 'finishing', 'f_count', 'edge'];
}

?>




<div class="common_div_body">
    <div class="main_field_div_sketches">


        <h2>Данные к заказу № <?= $id_order ?></h2>
        <h3>Комната: <?= $room ?></h3>
        <h3>Страница №<?= $page ?>  Спецификация: <?= $specification ?></h3>

        <div class="big-table">
        <table class="table_title_info">
            <tr>
                <?php
                foreach ($mas_test_1 as $value) {
                    ?>
                    <th class="th_title_info"> <?= $value ?> </th>
                    <?php
                }
                ?>
            </tr>

            <?php
            $sql_req = "SELECT * FROM `$from` WHERE `id_sketches_main` = '$id_in_sketches'";
            $info_pink_order = mysqli_query($connect, $sql_req);
            while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
                ?>
                <tr>
                    <?php
                    foreach ($mas_test_2 as $value) {
                        ?>
                        <td class="tb_title_info"><?= $info_pink_order_while[$value] ?></td>
                        <?php
                    }
                    ?>
                    <td class="tb_title_info"><a href="sketches_room_update_pr_update.php?id=<?= $info_pink_order_while['id'] ?>&id_order=<?=$id_order?>&specification=<?= $specification ?>" class="common_back_href">Изменить</a></td>
                    <td class="tb_title_info"><a href="sketches_room_update_pr_delete.php?id=<?= $info_pink_order_while['id'] ?>&specification=<?= $specification ?>" class="common_back_href" onclick="return confirm('Удалить?');">Удалить</a></td>
                </tr>
                <?php
            }
            ?>






            <?php
                if($specification == 'портьеры|тюли|подхваты|тп'){
            ?>
        <form action="sketches_room_update_pr_add.php" method="post">
            <input type="hidden" name="id_in_sketches" value="<?= $id_in_sketches ?>">
                <td></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="vendor_code" size="10" value="<?=@$_POST['vendor_code']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="count" size="10" value="<?=@$_POST['count']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="assembled_width" size="10" value="<?=@$_POST['assembled_width']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="coefficient" size="10" value="<?=@$_POST['coefficient']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="unfolded_width" size="10" value="<?=@$_POST['unfolded_width']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="height" size="10" value="<?=@$_POST['height']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="scallop" size="10" value="<?=@$_POST['scallop']?>"></td>
            <td class="tb_title_info">
<!--                <select class="js-select2" id="main_cloth" name="main_cloth">-->
                <select id="main_cloth" class="common_select" name="main_cloth">
                    <?php
                    if(!empty($_POST['main_cloth'])){
                        ?>
                        <option value="<?= @$_POST['main_cloth']?>"><?="Выбрано: " . @$_POST['main_cloth']?></option>
                        <?php
                    }else{
                        ?>
                        <option value=""></option>
                        <?php
                    }

                    // в зависимости от типа ткани
                    // будет вывод из определенной базы данных
                    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room'  AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                    $select = mysqli_query($connect, $sql);
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        ?>
                        <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td class="tb_title_info"><input type="text" class="input_style" name="m_count" size="10" value="<?=@$_POST['m_count']?>"></td>
            <td class="tb_title_info">
                <select id="lining" name="lining" class="common_select" placeholder="Выберите город">
                    <?php
                    if(!empty($_POST['lining'])){
                        ?>
                        <option value="<?= @$_POST['lining']?>"><?="Выбрано: " . @$_POST['lining']?></option>
                        <?php
                    }else{
                        ?>
                        <option value=""></option>
                    <?php
                    }

                    // в зависимости от типа ткани
                    // будет вывод из определенной базы данных
                    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                    $select = mysqli_query($connect, $sql);
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        ?>
                        <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td class="tb_title_info"><input type="text" class="input_style" name="l_count" size="10" value="<?=@$_POST['l_count']?>"></td>
            <td class="tb_title_info">
                <select id="finishing" name="finishing" class="common_select" placeholder="Выберите город">
                    <?php
                    if(!empty($_POST['finishing'])){
                        ?>
                        <option value="<?= @$_POST['finishing']?>"><?="Выбрано: " . @$_POST['finishing']?></option>
                        <?php
                    }else{
                        ?>
                        <option value=""></option>
                        <?php
                    }

                    // в зависимости от типа ткани
                    // будет вывод из определенной базы данных
                    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room'  AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                    $select = mysqli_query($connect, $sql);
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        ?>
                        <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td class="tb_title_info"><input type="text" class="input_style" name="f_count" size="10" value="<?=@$_POST['f_count']?>"></td>
            <td class="tb_title_info"><input type="text" class="input_style" name="bottom" size="10" value="<?=@$_POST['bottom']?>"></td>
            <td class="tb_title_info"><input type="text" class="input_style" name="sides" size="10" value="<?=@$_POST['sides']?>"></td>
            <td class="tb_title_info"><input type="text" class="input_style" name="bend" size="10" value="<?=@$_POST['bend']?>"></td>
            <td class="tb_title_info">
                <select name="ribbon" class="common_select" style="width: 150px;">
                    <?php
                    if(!empty($_POST['ribbon'])){
                        ?>
                        <option value="<?=$_POST['ribbon']?>">Выбрано: <?=$_POST['ribbon']?></option>
                        <?php
                    }
                    ?>
                    <option value="Матовая 5 см"> Матовая 5 см </option>
                    <option value="Матовая 8 см"> Матовая 8 см </option>
                    <option value="Матовая 10 см"> Матовая 10 см </option>
                    <option value="Матовая бокалы"> Матовая бокалы </option>
                    <option value="Матовая бантовая"> Матовая бантовая </option>
                    <option value="Прозрачная 5см."> Прозрачная 5см. </option>
                    <option value="Прозрачная 9см."> Прозрачная 9 см. </option>
                    <option value="Прозрачная 10см."> Люверсная 10 см. </option>
                    <option value="Прозрачная 12см."> Люверсная 12 см. </option>
                </select>
            </td>
            <td class="tb_title_info"><input type="submit" class="common_back_href" value="Добавить"></td>

        </form>
        <?php
        } else if($specification == 'римские|франц|австрийск|тп'){
            ?>
            <form action="sketches_room_update_pr_add.php" method="post">
                <input type="hidden" name="id_in_sketches" value="<?= $id_in_sketches ?>">
                <td></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="vendor_code" size="10" value="<?=@$_POST['vendor_code']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="count" size="10" value="<?=@$_POST['count']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="eaves_width" size="10" value="<?=@$_POST['eaves_width']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="height" size="10" value="<?=@$_POST['height']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="scallop" size="10" value="<?=@$_POST['scallop']?>"></td>
                <td class="tb_title_info">
                    <select id="main_cloth" name="main_cloth" class="common_select" placeholder="Выберите город">
                        <?php
                        if(!empty($_POST['main_cloth'])){
                            ?>
                            <option value="<?= @$_POST['main_cloth']?>"><?="Выбрано: " . @$_POST['main_cloth']?></option>
                            <?php
                        }else{
                            ?>
                            <option value=""></option>
                            <?php
                        }

                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="tb_title_info"><input type="text" class="input_style" name="m_count" size="10" value="<?=@$_POST['m_count']?>"></td>
                <td class="tb_title_info">
                    <select id="lining" name="lining" class="common_select" placeholder="Выберите город">
                        <?php
                        if(!empty($_POST['lining'])){
                            ?>
                            <option value="<?= @$_POST['lining']?>"><?="Выбрано: " . @$_POST['lining']?></option>
                            <?php
                        }else{
                            ?>
                            <option value=""></option>
                            <?php
                        }

                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="tb_title_info"><input type="text" class="input_style" name="l_count" size="10" value="<?=@$_POST['l_count']?>"></td>
                <td class="tb_title_info">
                    <select id="finishing" name="finishing" class="common_select" placeholder="Выберите город">
                        <?php
                        if(!empty($_POST['finishing'])){
                            ?>
                            <option value="<?= @$_POST['finishing']?>"><?="Выбрано: " . @$_POST['finishing']?></option>
                            <?php
                        }else{
                            ?>
                            <option value=""></option>
                            <?php
                        }

                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="tb_title_info"><input type="text" class="input_style" name="f_count" size="10" value="<?=@$_POST['f_count']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="count_fib" size="10" value="<?=@$_POST['count_fib']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="count_drums" size="10" value="<?=@$_POST['count_drums']?>"></td>

                <td class="tb_title_info"><input type="submit" class="common_back_href" value="Добавить"></td>

            </form>
            <?php
            }
                else if($specification == 'покрывала'){
            ?>
            <form action="sketches_room_update_pr_add.php" method="post">
                <input type="hidden" name="id_in_sketches" value="<?= $id_in_sketches ?>">
                <td></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="vendor_code" size="10" value="<?=@$_POST['vendor_code']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="count" size="10" value="<?=@$_POST['count']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="width" size="10" value="<?=@$_POST['width']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="length" size="10" value="<?=@$_POST['length']?>"></td>


                <td class="tb_title_info">
                    <select class="common_select" name="stitch" style="width: 150px;">
                        <?php
                        if(!empty($_POST['stitch'])){
                            ?>
                            <option value="<?=$_POST['stitch']?>">Выбрано: <?=$_POST['stitch']?></option>
                            <?php
                        }
                        ?>
                        <option value="Квадраты"> Квадраты </option>
                        <option value="Ромбы"> Ромбы </option>
                        <option value="Прямые линии"> Прямые линии </option>
                        <option value="Точечные закрепки"> Точечные закрепки </option>
                    </select>
                </td>
                <td class="tb_title_info">
                    <select class="common_select" name="stitch_name" style="width: 150px;">
                        <?php
                        if(!empty($_POST['stitch_name'])){
                            ?>
                            <option value="<?=$_POST['stitch_name']?>">Выбрано: <?=$_POST['stitch_name']?></option>
                            <?php
                        }
                        ?>
                        <option value="Квадраты"> Квадраты </option>
                        <option value="Ромбы"> Ромбы </option>
                        <option value="Прямые линии"> Прямые линии </option>
                        <option value="Точечные закрепки"> Точечные закрепки </option>
                    </select>
                </td>

                <td class="tb_title_info"><input type="text" class="input_style" name="stitch_step" size="10" value="<?=@$_POST['stitch_step']?>"></td>


                <td class="tb_title_info">
                    <select id="main_cloth" name="main_cloth" class="common_select" placeholder="Выберите город">
                        <?php
                        if(!empty($_POST['main_cloth'])){
                            ?>
                            <option value="<?= @$_POST['main_cloth']?>"><?="Выбрано: " . @$_POST['main_cloth']?></option>
                            <?php
                        }else{
                            ?>
                            <option value=""></option>
                            <?php
                        }


                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="tb_title_info"><input type="text" class="input_style" name="m_count" size="10" value="<?=@$_POST['m_count']?>"></td>
                <td class="tb_title_info">
                    <select id="lining" name="lining" class="common_select" placeholder="Выберите город">
                        <?php
                        if(!empty($_POST['lining'])){
                            ?>
                            <option value="<?= @$_POST['lining']?>"><?="Выбрано: " . @$_POST['lining']?></option>
                            <?php
                        }else{
                            ?>
                            <option value=""></option>
                            <?php
                        }

                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="tb_title_info"><input type="text" class="input_style" name="l_count" size="10" value="<?=@$_POST['l_count']?>"></td>
                <td class="tb_title_info">
                    <select id="finishing" name="finishing" class="common_select" placeholder="Выберите город">
                        <?php
                        if(!empty($_POST['finishing'])){
                            ?>
                            <option value="<?= @$_POST['finishing']?>"><?="Выбрано: " . @$_POST['finishing']?></option>
                            <?php
                        }else{
                            ?>
                            <option value=""></option>
                            <?php
                        }
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="tb_title_info"><input type="text" class="input_style" name="f_count" size="10" value="<?=@$_POST['f_count']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="edges" size="10" value="<?=@$_POST['edges']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="centipone" size="10" value="<?=@$_POST['centipone']?>"></td>
                <td class="tb_title_info"><input type="text" class="input_style" name="corner" size="10" value="<?=@$_POST['corner']?>"></td>

                <td class="tb_title_info"><input type="submit" class="common_back_href" value="Добавить"></td>

            </form>
            <?php
            }
                else if($specification == 'подушки|наволочки|валики'){
                    ?>
                    <form action="sketches_room_update_pr_add.php" method="post">
                        <input type="hidden" name="id_in_sketches" value="<?= $id_in_sketches ?>">
                        <td></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="vendor_code" size="10" value="<?=@$_POST['vendor_code']?>"></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="count" size="10" value="<?=@$_POST['count']?>"></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="width" size="10" value="<?=@$_POST['width']?>"></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="length" size="10" value="<?=@$_POST['length']?>"></td>

                        <td class="tb_title_info">
                            <select id="main_cloth" class="common_select" name="main_cloth" placeholder="Выберите город">
                                <?php
                                if(!empty($_POST['main_cloth'])){
                                    ?>
                                    <option value="<?= @$_POST['main_cloth']?>"><?="Выбрано: " . @$_POST['main_cloth']?></option>
                                    <?php
                                }else{
                                    ?>
                                    <option value=""></option>
                                    <?php
                                }
                                // в зависимости от типа ткани
                                // будет вывод из определенной базы данных
                                $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                                $select = mysqli_query($connect, $sql);
                                while ($select_while = mysqli_fetch_assoc($select)) {
                                    ?>
                                    <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td class="tb_title_info"><input class="input_style" type="text" name="m_count" size="10" value="<?=@$_POST['m_count']?>"></td>


                        <td class="tb_title_info">
                            <select class="common_select" name="pillow" style="width: 150px;">
                                <?php
                                if(!empty($_POST['pillow'])){
                                    ?>
                                    <option value="<?=$_POST['pillow']?>">Выбрано: <?=$_POST['pillow']?></option>
                                    <?php
                                }
                                ?>
                                <option value="Да"> Да </option>
                                <option value="Нет"> Нет </option>
                            </select>
                        </td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="p_count" size="10" value="<?=@$_POST['p_count']?>"></td>


                        <td class="tb_title_info">
                            <select id="finishing" class="common_select" name="finishing" placeholder="Выберите город">
                                <?php
                                if(!empty($_POST['finishing'])){
                                    ?>
                                    <option value="<?= @$_POST['finishing']?>"><?="Выбрано: " . @$_POST['finishing']?></option>
                                    <?php
                                }else{
                                    ?>
                                    <option value=""></option>
                                    <?php
                                }
                                // в зависимости от типа ткани
                                // будет вывод из определенной базы данных
                                $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                                $select = mysqli_query($connect, $sql);
                                while ($select_while = mysqli_fetch_assoc($select)) {
                                    ?>
                                    <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="f_count" size="10" value="<?=@$_POST['f_count']?>"></td>


                        <td class="tb_title_info"><input type="submit" class="common_back_href" value="Добавить"></td>
                    </form>
                    <?php
                }
                else if($specification == 'сваги|джаботы|ламбрикены'){
                    ?>
                    <form action="sketches_room_update_pr_add.php" method="post">
                        <input type="hidden" name="id_in_sketches" value="<?= $id_in_sketches ?>">
                        <td></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="vendor_code" size="10" value="<?=@$_POST['vendor_code']?>"></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="count" size="10" value="<?=@$_POST['count']?>"></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="eaves_width" size="10" value="<?=@$_POST['eaves_width']?>"></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="height" size="10" value="<?=@$_POST['height']?>"></td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="bottom_processing" size="10" value="<?=@$_POST['bottom_processing']?>"></td>

                        <td class="tb_title_info">
                            <select id="vendor_code" class="common_select" name="velcro" placeholder="Выберите город">
                                <?php
                                if(!empty($_POST['velcro'])){
                                    ?>
                                    <option value="<?=$_POST['velcro']?>">Выбрано: <?=$_POST['velcro']?></option>
                                    <?php
                                }
                                ?>
                                <option value="Да"> Да </option>
                                <option value="Нет"> Нет </option>
                            </select>
                        </td>
                        <td class="tb_title_info">
                            <select id="vendor_code" class="common_select" name="thermobando" placeholder="Выберите город">
                                <?php
                                if(!empty($_POST['thermobando'])){
                                    ?>
                                    <option value="<?=$_POST['thermobando']?>">Выбрано: <?=$_POST['thermobando']?></option>
                                    <?php
                                }
                                ?>
                                <option value="Да"> Да </option>
                                <option value="Нет"> Нет </option>
                            </select>
                        </td>

                        <td class="tb_title_info">
                            <select id="main_cloth" name="main_cloth" class="common_select" placeholder="Выберите город">
                                <?php
                                if(!empty($_POST['main_cloth'])){
                                    ?>
                                    <option value="<?= @$_POST['main_cloth']?>"><?="Выбрано: " . @$_POST['main_cloth']?></option>
                                    <?php
                                }else{
                                    ?>
                                    <option value=""></option>
                                    <?php
                                }
                                // в зависимости от типа ткани
                                // будет вывод из определенной базы данных
                                $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                                $select = mysqli_query($connect, $sql);
                                while ($select_while = mysqli_fetch_assoc($select)) {
                                    ?>
                                    <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="m_count" size="10" value="<?=@$_POST['m_count']?>"</td>


                        <td class="tb_title_info">
                            <select id="lining" name="lining" class="common_select" placeholder="Выберите город">
                                <?php
                                if(!empty($_POST['lining'])){
                                    ?>
                                    <option value="<?= @$_POST['lining']?>"><?="Выбрано: " . @$_POST['lining']?></option>
                                    <?php
                                }else{
                                    ?>
                                    <option value=""></option>
                                    <?php
                                }
                                // в зависимости от типа ткани
                                // будет вывод из определенной базы данных
                                $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                                $select = mysqli_query($connect, $sql);
                                while ($select_while = mysqli_fetch_assoc($select)) {
                                    ?>
                                    <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="l_count" size="10" value="<?=@$_POST['l_count']?>"</td>


                        <td class="tb_title_info">
                            <select id="finishing" name="finishing" class="common_select" placeholder="Выберите город">
                                <?php
                                if(!empty($_POST['finishing'])){
                                    ?>
                                    <option value="<?= @$_POST['finishing']?>"><?="Выбрано: " . @$_POST['finishing']?></option>
                                    <?php
                                }else{
                                    ?>
                                    <option value=""></option>
                                    <?php
                                }
                                // в зависимости от типа ткани
                                // будет вывод из определенной базы данных
                                $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                                $select = mysqli_query($connect, $sql);
                                while ($select_while = mysqli_fetch_assoc($select)) {
                                    ?>
                                    <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td class="tb_title_info"><input type="text" class="input_style" name="f_count" size="10" value="<?=@$_POST['f_count']?>"</td>

                        <td class="tb_title_info">
                            <select class="common_select" name="ribbon" style="width: 150px;">
                                <?php
                                if(!empty($_POST['ribbon'])){
                                    ?>
                                    <option value="<?=$_POST['ribbon']?>">Выбрано: <?=$_POST['ribbon']?></option>
                                    <?php
                                }
                                ?>
                                <option value="Матовая 5 см"> Матовая 5 см </option>
                                <option value="Матовая 8 см"> Матовая 8 см </option>
                                <option value="Матовая 10 см"> Матовая 10 см </option>
                                <option value="Матовая бокалы"> Матовая бокалы </option>
                                <option value="Матовая бантовая"> Матовая бантовая </option>
                                <option value="Прозрачная 5см."> Прозрачная 5см. </option>
                                <option value="Прозрачная 9см."> Прозрачная 9 см. </option>
                                <option value="Прозрачная 10см."> Люверсная 10 см. </option>
                                <option value="Прозрачная 12см."> Люверсная 12 см. </option>
                            </select>
                        </td>

                        <td class="tb_title_info"><input type="submit" class="common_back_href" value="Добавить"></td>
                    </form>
                    <?php
                }
                else if($specification == 'скатерти|салфетки'){
                ?>
                <form action="sketches_room_update_pr_add.php" method="post">
                    <input type="hidden" name="id_in_sketches" value="<?= $id_in_sketches ?>">
                    <td></td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="vendor_code" size="10" value="<?=@$_POST['vendor_code']?>"></td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="count" size="10" value="<?=@$_POST['count']?>"></td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="width" size="10" value="<?=@$_POST['width']?>"></td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="length" size="10" value="<?=@$_POST['length']?>"></td>

                    <td class="tb_title_info">
                        <select id="main_cloth" name="main_cloth" class="common_select" placeholder="Выберите город">
                            <?php
                            if(!empty($_POST['main_cloth'])){
                                ?>
                                <option value="<?= @$_POST['main_cloth']?>"><?="Выбрано: " . @$_POST['main_cloth']?></option>
                                <?php
                            }else{
                                ?>
                                <option value=""></option>
                                <?php
                            }
                            // в зависимости от типа ткани
                            // будет вывод из определенной базы данных
                            $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                            $select = mysqli_query($connect, $sql);
                            while ($select_while = mysqli_fetch_assoc($select)) {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="m_count" size="10" value="<?=@$_POST['m_count']?>"></td>
                    <td class="tb_title_info">
                        <select id="lining" name="lining" class="common_select" placeholder="Выберите город">
                            <?php
                            if(!empty($_POST['lining'])){
                                ?>
                                <option value="<?= @$_POST['lining']?>"><?="Выбрано: " . @$_POST['lining']?></option>
                                <?php
                            }else{
                                ?>
                                <option value=""></option>
                                <?php
                            }
                            // в зависимости от типа ткани
                            // будет вывод из определенной базы данных
                            $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                            $select = mysqli_query($connect, $sql);
                            while ($select_while = mysqli_fetch_assoc($select)) {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="l_count" size="10" value="<?=@$_POST['l_count']?>"></td>
                    <td class="tb_title_info">
                        <select id="finishing" name="finishing" class="common_select" placeholder="Выберите город">
                            <?php
                            if(!empty($_POST['finishing'])){
                                ?>
                                <option value="<?= @$_POST['finishing']?>"><?="Выбрано: " . @$_POST['finishing']?></option>
                                <?php
                            }else{
                                ?>
                                <option value=""></option>
                                <?php
                            }
                            // в зависимости от типа ткани
                            // будет вывод из определенной базы данных
                            $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                            $select = mysqli_query($connect, $sql);
                            while ($select_while = mysqli_fetch_assoc($select)) {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="f_count" size="10" value="<?=@$_POST['f_count']?>"></td>
                    <td class="tb_title_info"><input type="text" class="input_style" name="edge" size="10" value="<?=@$_POST['edge']?>"></td>

                    <td class="tb_title_info"><input type="submit" class="common_back_href" value="Добавить"></td>

                </form>
                <?php
            }
            ?>


        </table>
        </div>







        <div class="under_table">


            <?php
            $sketches_main_info = mysqli_query($connect, "SELECT * FROM `sketches_main` WHERE `id_order` = '$id_order' AND `room` = '$room' AND `page` = '$page'");
            $sketches_main_info = mysqli_fetch_assoc($sketches_main_info);
            ?>
            <div>
                <form action="sketches_room_update_info.php" method="post">
                    <input type="hidden" name="id" value="<?= $sketches_main_info['id'] ?>">
                    <input type="hidden" name="id_in_sketches" value="<?= $id_in_sketches ?>">
                    <input type="hidden" name="id_order" value="<?= $id_order ?>">
                    <input type="hidden" name="room" value="<?= $room ?>">
                    <input type="hidden" name="page" value="<?= $page ?>">
                    <input type="hidden" name="specification" value="<?= $specification ?>">

                    <div class="note_sketches">
                        <div class="text_for_update">Примечания:</div>
                        <textarea type="text" class="input_style" name="note"><?= $sketches_main_info['note'] ?></textarea>
                    </div>
                    <div class="note_sketches">
                        <div class="text_for_update">Лицевой стороной считать:</div>
                        <textarea type="text" class="input_style" name="font_side"><?= $sketches_main_info['font_side']?></textarea>
                    </div>
                    <?php
                    if(isset($_GET['state'])){
                        ?>
                        <div class="note_sketches">
                            <div class="text_for_update">Статус: <?= $_GET['state'] ?></div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="note_sketches">
                        <button type="submit" class="common_button">Изменить</button>
                    </div>
                </form>

                <div class="edit_sketches_btn">
                    <a href="order_paint_update.php?id=<?= $sketches_main_info['id'] ?>" class="common_back_href" style="margin-right: 20px;">Изменить эскиз</a>
                    <a href="order_paint_new_create.php?id=<?= $sketches_main_info['id'] ?>" class="common_back_href">Нарисовать эскиз с нуля</a>
                </div>
            </div>


            <div>
                <?php
                $str_name_img = '_' . $id_order . '_' . $room . '_' . $page .".png";
                if(@fopen("../assets/img/img" . $str_name_img, "r")) {
                    ?>
                    <div class="image_for_designer">
                        <img src="../assets/img/img<?=$str_name_img?>?<?= filemtime('../assets/img/img' . $str_name_img) ?>" class="sketch">
                    </div>
                    <?php
                }?>
            </div>


        </div>





</div>
</div>


</body>

</html>