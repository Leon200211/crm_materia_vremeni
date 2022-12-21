<?php


#============================================================================
# Страница с изменение строки в определенной странице эскиза
#============================================================================




session_start();



if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';







//  -------------------------------
//  для проверки на создание заказа
$id_order = $_GET['id_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------




$id = $_GET['id'];
$specification = $_GET['specification'];

?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>Пример веб-страницы</title>


    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/style_sketches/style_for_sketches.css">


    <script src="../assets/script/app.js" defer></script>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../assets/script/paint_update.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

</head>

<body>

<header class="header">
    <?php
    include('../header.php');
    ?>
</header>



<div class="common_div_body">





    <?php
    if($specification == 'портьеры|тюли|подхваты|тп'){
        $orders = mysqli_query($connect, "SELECT * FROM `sketches_1` WHERE `id` = '$id'");
        $orders = mysqli_fetch_assoc($orders);
    ?>
    <div>
        <div class="back_title">
            Заказ № <?= $id_order ?> / Пункт № <?= $orders['id_paragraph'] ?>
        </div>
        <a href="sketches_room_update.php?id_in_sketches=<?= $orders['id_sketches_main'] ?>" class="common_back_href">Назад</a>
    </div>
    <div class="main_field_div_sketches">



    <h3 class="update_title">Изменение пункта № <?= $orders['id_paragraph'] ?></h3>

        <?php
        if(isset($_GET['state_add'])){
            ?>
            <div class="place_find" style="color: red;">
                <div>Ошибка добавления</div>
                <div class="text_for_update">Поле <?= $_GET['state_add'] ?></div>
            </div>
            <?php
        }
        ?>

    <form action="sketches_room_update_pr_update_main.php" method="post">
        <div class="place_find">


            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="id_order" value="<?= $id_order ?>">
            <input type="hidden" name="id_sketches_main" value="<?= $orders['id_sketches_main'] ?>">
            <input type="hidden" name="specification" value="<?= $specification ?>">

            <div class="update_product_all">

                <div class="text_for_update">Изделия</div>
                <input type="text" name="vendor_code" class="input_style" size="10" value="<?= $orders['vendor_code'] ?>">

                <div class="text_for_update">Количество шт.</div>
                <input type="text" name="count" class="input_style" size="10" value="<?= $orders['count'] ?>">

                <div class="text_for_update">Ширина сбор. Виде (см.)</div>
                <input type="text" name="assembled_width" class="input_style" size="10" value="<?= $orders['assembled_width'] ?>">

                <div class="text_for_update">Коэф. сборки</div>
                <input type="text" name="coefficient" class="input_style" size="10" value="<?= $orders['coefficient'] ?>">

                <div class="text_for_update">Ширина в разверн. виде (крой) в см</div>
                <input type="text" name="unfolded_width" class="input_style" size="10" value="<?= $orders['unfolded_width'] ?>">

                <div class="text_for_update">Высота в см.</div>
                <input type="text" name="height" size="10" class="input_style" value="<?= $orders['height'] ?>">

                <div class="text_for_update">Гребешок</div>
                <input type="text" name="scallop" size="10" class="input_style" value="<?= $orders['scallop'] ?>">

                <div class="text_for_update">Основная ткань</div>


                    <select class="common_select" id="main_cloth" name="main_cloth" placeholder="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);

                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['main_cloth']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>

                <div class="text_for_update">Кол-во</div>
                <input type="text" name="m_count" size="10" class="input_style" value="<?= $orders['m_count'] ?>">

                <div class="text_for_update">Подкладка</div>
                    <select class="common_select" id="lining" name="lining" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['lining']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                <div class="text_for_update">Кол-во</div>
                <input type="text" name="l_count" size="10" class="input_style" value="<?= $orders['l_count'] ?>">

                <div class="text_for_update">Отделка</div>
                <select class="common_select" id="finishing" name="finishing" value="Выберите город">
                    <option value=""></option>
                    <?php
                    // в зависимости от типа ткани
                    // будет вывод из определенной базы данных
                    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                    $select = mysqli_query($connect, $sql);
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        if($select_while['description'] == $orders['finishing']){
                            ?>
                            <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }else {
                            ?>
                            <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>

                <div class="text_for_update">Кол-во</div>
                <input type="text" name="f_count" class="input_style" size="10" value="<?= $orders['f_count'] ?>">

                <div class="text_for_update">Низ в см.</div>
                <input type="text" name="bottom" class="input_style" size="10" value="<?= $orders['bottom'] ?>">

                <div class="text_for_update">Бока в см.</div>
                <input type="text" name="sides" class="input_style" size="10" value="<?= $orders['sides'] ?>">

                <div class="text_for_update">Тех-загиб бок. в см.</div>
                <input type="text" name="bend" class="input_style" size="10" value="<?= $orders['bend'] ?>">


                <div class="text_for_update">шт. лента</div>
                    <select class="common_select" name="ribbon">
                        <option selected="selected"  value="<?= $orders['ribbon'] ?>">Выбрано: <?= $orders['ribbon'] ?> </option>
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

                <br>
                <br>

                <button type="submit" class="common_button">Изменить</button>
            </div>
    </form>
    <?php
    }else if($specification == 'римские|франц|австрийск|тп'){
        $orders = mysqli_query($connect, "SELECT * FROM `sketches_2` WHERE `id` = '$id'");
        $orders = mysqli_fetch_assoc($orders);
        ?>
        <div>
            <div class="back_title">
                Заказ № <?= $id_order ?> / Пункт № <?= $orders['id_paragraph'] ?>
            </div>
            <a href="sketches_room_update.php?id_in_sketches=<?= $orders['id_sketches_main'] ?>" class="common_back_href">Назад</a>
        </div>

        <div class="main_field_div_sketches">


        <h3 class="update_title">Изменение пункта № <?= $orders['id_paragraph'] ?></h3>
        <?php
        if(isset($_GET['state_add'])){
            ?>
            <div class="place_find">
                <div>Ошибка добавления</div>
                <div class="text_for_update">Поле <?= $_GET['state_add'] ?></div>
            </div>
            <?php
        }
        ?>

        <form action="sketches_room_update_pr_update_main.php" method="post">
            <div class="place_find">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="id_order" value="<?= $id_order ?>">
                <input type="hidden" name="id_sketches_main" value="<?= $orders['id_sketches_main'] ?>">
                <input type="hidden" name="specification" value="<?= $specification ?>">


                <div class="update_product_all">

                    <div class="text_for_update">Изделия</div>
                    <input type="text" name="vendor_code" class="input_style" size="10" value="<?= $orders['vendor_code'] ?>">

                    <div class="text_for_update">Количество шт.</div>
                    <input type="text" name="count" size="10" class="input_style" value="<?= $orders['count'] ?>">

                    <div class="text_for_update">Ширина по карнизу (см)</div>
                    <input type="text" name="eaves_width" class="input_style" size="10" value="<?= $orders['eaves_width'] ?>">

                    <div class="text_for_update">Высота изделия (см)</div>
                    <input type="text" name="height" class="input_style" size="10" value="<?= $orders['height'] ?>">

                    <div class="text_for_update">Гребешок</div>
                    <input type="text" name="scallop" class="input_style" size="10" value="<?= $orders['scallop'] ?>">

                    <div class="text_for_update">Основная ткань</div>
                    <select class="common_select" id="main_cloth" name="main_cloth" placeholder="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);

                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['main_cloth']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" name="m_count" class="input_style" size="10" value="<?= $orders['m_count'] ?>">

                    <div class="text_for_update">Подкладка</div>
                    <select class="common_select" id="lining" name="lining" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['lining']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" name="l_count" class="input_style" size="10" value="<?= $orders['l_count'] ?>">

                    <div class="text_for_update">Отделка</div>
                    <select class="common_select" id="finishing" name="finishing" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['finishing']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" name="f_count" class="input_style" size="10" value="<?= $orders['f_count'] ?>">

                    <div class="text_for_update">Кол-во. фибергласовых (шт)</div>
                    <input type="text" name="count_fib" class="input_style" size="10" value="<?= $orders['count_fib'] ?>">

                    <div class="text_for_update">Кол-во барабанов (шт)</div>
                    <input type="text" name="count_drums" class="input_style" size="10" value="<?= $orders['count_drums'] ?>">

                    <br>
                    <br>
                    <button type="submit" class="common_button">Изменить</button>
                </div>
        </form>
        <?php
    }else if($specification == 'покрывала'){
        $orders = mysqli_query($connect, "SELECT * FROM `sketches_3` WHERE `id` = '$id'");
        $orders = mysqli_fetch_assoc($orders);
        ?>
        <div>
            <div class="back_title">
                Заказ № <?= $id_order ?> / Пункт № <?= $orders['id_paragraph'] ?>
            </div>
            <a href="sketches_room_update.php?id_in_sketches=<?= $orders['id_sketches_main'] ?>" class="common_back_href">Назад</a>
        </div>

        <div class="main_field_div_sketches">


        <h3 class="update_title">Изменение пункта № <?= $orders['id_paragraph'] ?></h3>
        <?php
        if(isset($_GET['state_add'])){
            ?>
            <div class="place_find">
                <div>Ошибка добавления</div>
                <div class="text_for_update">Поле <?= $_GET['state_add'] ?></div>
            </div>
            <?php
        }
        ?>

        <form action="sketches_room_update_pr_update_main.php" method="post">
            <div class="place_find">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="id_order" value="<?= $id_order ?>">
                <input type="hidden" name="id_sketches_main" value="<?= $orders['id_sketches_main'] ?>">
                <input type="hidden" name="specification" value="<?= $specification ?>">


                <div class="update_product_all">

                    <div class="text_for_update">Изделия</div>
                    <input type="text" name="vendor_code" class="input_style" size="10" value="<?= $orders['vendor_code'] ?>">

                    <div class="text_for_update">Количество шт.</div>
                    <input type="text" name="count" class="input_style" size="10" value="<?= $orders['count'] ?>">

                    <div class="text_for_update">Ширина габаритная (см)</div>
                    <input type="text" name="width" class="input_style" size="10" value="<?= $orders['width'] ?>">

                    <div class="text_for_update">Длина габаритная (см)</div>
                    <input type="text" name="length" class="input_style" size="10" value="<?= $orders['length'] ?>">


                    <div class="text_for_update">Стежка (цех или анита)</div>
                    <select class="common_select" name="stitch" style="width: 150px;">
                        <option selected="selected" value="<?= $orders['stitch'] ?>">Выбрано: <?= $orders['stitch'] ?></option>
                        <option value="Квадраты"> Квадраты </option>
                        <option value="Ромбы"> Ромбы </option>
                        <option value="Прямые линии"> Прямые линии </option>
                        <option value="Точечные закрепки"> Точечные закрепки </option>
                    </select>

                    <div class="text_for_update">Наименование стежки</div>
                    <select class="common_select" name="stitch_name" style="width: 150px;">
                        <option selected="selected" value="<?= $orders['stitch_name'] ?>">Выбрано: <?= $orders['stitch_name'] ?></option>
                        <option value="Квадраты"> Квадраты </option>
                        <option value="Ромбы"> Ромбы </option>
                        <option value="Прямые линии"> Прямые линии </option>
                        <option value="Точечные закрепки"> Точечные закрепки </option>
                    </select>


                    <div class="text_for_update">Шаг стежки</div>
                    <input type="text" class="input_style" name="stitch_step" size="10" value="<?= $orders['stitch_step'] ?>">

                    <div class="text_for_update">Основная ткань</div>
                    <select class="common_select" id="main_cloth" name="main_cloth" placeholder="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);

                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['main_cloth']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" name="m_count" class="input_style" size="10" value="<?= $orders['m_count'] ?>">

                    <div class="text_for_update">Подкладка</div>
                    <select class="common_select" id="lining" name="lining" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['lining']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="l_count" size="10" value="<?= $orders['l_count'] ?>">

                    <div class="text_for_update">Отделка</div>
                    <select class="common_select" id="finishing" name="finishing" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['finishing']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="f_count" size="10" value="<?= $orders['f_count'] ?>">

                    <div class="text_for_update">Края (подборт или мешком)</div>
                    <input type="text" class="input_style" name="edges" size="10" value="<?= $orders['edges'] ?>">
                    <div class="text_for_update">Синтепон (100/200г)</div>
                    <input type="text" class="input_style" name="centipone" size="10" value="<?= $orders['centipone'] ?>">
                    <div class="text_for_update">Радиус закругления или прям угол</div>
                    <input type="text" class="input_style" name="corner" size="10" value="<?= $orders['corner'] ?>">

                    <br>
                    <br>
                    <button type="submit" class="common_button">Изменить</button>
                </div>
        </form>
        <?php
    }else if($specification == 'подушки|наволочки|валики'){
        $orders = mysqli_query($connect, "SELECT * FROM `sketches_4` WHERE `id` = '$id'");
        $orders = mysqli_fetch_assoc($orders);
        ?>
        <div>
            <div class="back_title">
                Заказ № <?= $id_order ?> / Пункт № <?= $orders['id_paragraph'] ?>
            </div>
            <a href="sketches_room_update.php?id_in_sketches=<?= $orders['id_sketches_main'] ?>" class="common_back_href">Назад</a>
        </div>

        <div class="main_field_div_sketches">

        <h3 class="update_title">Изменение пункта № <?= $orders['id_paragraph'] ?></h3>
        <?php
        if(isset($_GET['state_add'])){
            ?>
            <div class="place_find">
                <div>Ошибка добавления</div>
                <div class="text_for_update">Поле <?= $_GET['state_add'] ?></div>
            </div>
            <?php
        }
        ?>

        <form action="sketches_room_update_pr_update_main.php" method="post">
            <div class="place_find">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="id_order" value="<?= $id_order ?>">
                <input type="hidden" name="id_sketches_main" value="<?= $orders['id_sketches_main'] ?>">
                <input type="hidden" name="specification" value="<?= $specification ?>">


                <div class="update_product_all">

                    <div class="text_for_update">Изделия</div>
                    <input type="text" class="input_style" name="vendor_code" size="10" value="<?= $orders['vendor_code'] ?>">

                    <div class="text_for_update">Количество шт.</div>
                    <input type="text" class="input_style" name="count" size="10" value="<?= $orders['count'] ?>">

                    <div class="text_for_update">Ширина габаритная / диаметр валика (см</div>
                    <input type="text" class="input_style" name="width" size="10" value="<?= $orders['width'] ?>">

                    <div class="text_for_update">Длина габаритная (см)</div>
                    <input type="text" class="input_style" name="length" size="10" value="<?= $orders['length'] ?>">

                    <div class="text_for_update">Основная ткань</div>
                    <select class="common_select" id="main_cloth" name="main_cloth" placeholder="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);

                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['main_cloth']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="m_count" size="10" value="<?= $orders['m_count'] ?>">



                    <div class="text_for_update">Внутрення подушка/чехол</div>
                    <select class="common_select" name="pillow" style="width: 150px;">
                        <option selected="selected" value="<?= $orders['pillow'] ?>">Выбрано: <?= $orders['pillow'] ?></option>
                        <option value="Да"> Да </option>
                        <option value="Нет"> Нет </option>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="p_count" size="10" value="<?= $orders['p_count'] ?>">


                    <div class="text_for_update">Отделка</div>
                    <select class="common_select" id="finishing" name="finishing" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['finishing']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="f_count" size="10" value="<?= $orders['f_count'] ?>">



                    <br>
                    <br>
                    <button type="submit" class="common_button">Изменить</button>
                </div>
        </form>
        <?php
    }else if($specification == 'сваги|джаботы|ламбрикены'){
        $orders = mysqli_query($connect, "SELECT * FROM `sketches_5` WHERE `id` = '$id'");
        $orders = mysqli_fetch_assoc($orders);
        ?>
        <div>
            <div class="back_title">
                Заказ № <?= $id_order ?> / Пункт № <?= $orders['id_paragraph'] ?>
            </div>
            <a href="sketches_room_update.php?id_in_sketches=<?= $orders['id_sketches_main'] ?>" class="common_back_href">Назад</a>
        </div>

        <div class="main_field_div_sketches">


        <h3 class="update_title">Изменение пункта № <?= $orders['id_paragraph'] ?></h3>
        <?php
        if(isset($_GET['state_add'])){
            ?>
            <div class="place_find">
                <div>Ошибка добавления</div>
                <div class="text_for_update">Поле <?= $_GET['state_add'] ?></div>
            </div>
            <?php
        }
        ?>

        <form action="sketches_room_update_pr_update_main.php" method="post">
            <div class="place_find">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="id_order" value="<?= $id_order ?>">
                <input type="hidden" name="id_sketches_main" value="<?= $orders['id_sketches_main'] ?>">
                <input type="hidden" name="specification" value="<?= $specification ?>">


                <div class="update_product_all">

                    <div class="text_for_update">Изделия</div>
                    <input type="text" class="input_style" name="vendor_code" size="10" value="<?= $orders['vendor_code'] ?>">

                    <div class="text_for_update">Количество шт.</div>
                    <input type="text" class="input_style" name="count" size="10" value="<?= $orders['count'] ?>">

                    <div class="text_for_update">Ширина по карнизу (см)</div>
                    <input type="text" class="input_style" name="eaves_width" size="10" value="<?= $orders['eaves_width'] ?>">

                    <div class="text_for_update">Высота в см</div>
                    <input type="text" class="input_style" name="height" size="10" value="<?= $orders['height'] ?>">

                    <div class="text_for_update">Обработка низа</div>
                    <input type="text" class="input_style" name="bottom_processing" size="10" value="<?= $orders['bottom_processing'] ?>">


                    <div class="text_for_update">Липучка</div>
                    <select class="common_select" id="vendor_code" name="velcro" placeholder="Выберите город">
                        <option selected="selected" value="<?= $orders['velcro'] ?>">Выбрано: <?= $orders['velcro'] ?></option>
                        <option value="Да"> Да </option>
                        <option value="Нет"> Нет </option>
                    </select>

                    <div class="text_for_update">Термобандо</div>
                    <select class="common_select" id="vendor_code" name="thermobando" placeholder="Выберите город">
                        <option selected="selected" value="<?= $orders['thermobando'] ?>">Выбрано: <?= $orders['thermobando'] ?></option>
                        <option value="Да"> Да </option>
                        <option value="Нет"> Нет </option>
                    </select>

                    <div class="text_for_update">Основная ткань</div>
                    <select class="common_select" id="main_cloth" name="main_cloth" placeholder="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);

                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['main_cloth']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <div class="text_for_update">Кол-во</div>
                    <input type="text" name="m_count" class="input_style" size="10" value="<?= $orders['m_count'] ?>">



                    <div class="text_for_update">Подкладка</div>
                    <select class="common_select"  id="lining" name="lining" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['lining']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="l_count" size="10" value="<?= $orders['l_count'] ?>">


                    <div class="text_for_update">Отделка</div>
                    <select class="common_select" id="finishing" name="finishing" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['finishing']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="f_count" size="10" value="<?= $orders['f_count'] ?>">

                    <div class="text_for_update">Шт. лента</div>
                    <select class="common_select" name="ribbon" style="width: 150px;">
                        <option selected="selected"  value="<?= $orders['ribbon'] ?>">Выбрано: <?= $orders['ribbon'] ?> </option>
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


                    <br>
                    <br>
                    <button type="submit" class="common_button">Изменить</button>
                </div>
        </form>
        <?php
    }else if($specification == 'скатерти|салфетки'){
        $orders = mysqli_query($connect, "SELECT * FROM `sketches_6` WHERE `id` = '$id'");
        $orders = mysqli_fetch_assoc($orders);
        ?>
        <div>
            <div class="back_title">
                Заказ № <?= $id_order ?> / Пункт № <?= $orders['id_paragraph'] ?>
            </div>
            <a href="sketches_room_update.php?id_in_sketches=<?= $orders['id_sketches_main'] ?>" class="common_back_href">Назад</a>
        </div>

        <div class="main_field_div_sketches">


        <h3 class="update_title">Изменение пункта № <?= $orders['id_paragraph'] ?></h3>
        <?php
        if(isset($_GET['state_add'])){
            ?>
            <div class="place_find">
                <div>Ошибка добавления</div>
                <div class="text_for_update">Поле <?= $_GET['state_add'] ?></div>
            </div>
            <?php
        }
        ?>

        <form action="sketches_room_update_pr_update_main.php" method="post">
            <div class="place_find">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="id_order" value="<?= $id_order ?>">
                <input type="hidden" name="id_sketches_main" value="<?= $orders['id_sketches_main'] ?>">
                <input type="hidden" name="specification" value="<?= $specification ?>">


                <div class="update_product_all">

                    <div class="text_for_update">Изделия</div>
                    <input type="text" class="input_style" name="vendor_code" size="10" value="<?= $orders['vendor_code'] ?>">

                    <div class="text_for_update">Количество шт.</div>
                    <input type="text" class="input_style" name="count" size="10" value="<?= $orders['count'] ?>">

                    <div class="text_for_update">Ширина габаритная / диаметр валика (см)</div>
                    <input type="text" class="input_style" name="width" size="10" value="<?= $orders['width'] ?>">

                    <div class="text_for_update">Длина габаритная (см)</div>
                    <input type="text" class="input_style" name="length" size="10" value="<?= $orders['length'] ?>">


                    <div class="text_for_update">Основная ткань</div>
                    <select class="common_select" id="main_cloth" name="main_cloth" placeholder="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);

                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['main_cloth']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="m_count" size="10" value="<?= $orders['m_count'] ?>">


                    <div class="text_for_update">Подкладка</div>
                    <select class="common_select" id="lining" name="lining" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['lining']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="l_count" size="10" value="<?= $orders['l_count'] ?>">


                    <div class="text_for_update">Отделка</div>
                    <select class="common_select" id="finishing" name="finishing" value="Выберите город">
                        <option value=""></option>
                        <?php
                        // в зависимости от типа ткани
                        // будет вывод из определенной базы данных
                        $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'";
                        $select = mysqli_query($connect, $sql);
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            if($select_while['description'] == $orders['finishing']){
                                ?>
                                <option selected="selected" value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }else {
                                ?>
                                <option value="<?= $select_while['description'] ?>"><?= $select_while['description'] ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <div class="text_for_update">Кол-во</div>
                    <input type="text" class="input_style" name="f_count" size="10" value="<?= $orders['f_count'] ?>">

                    <div class="text_for_update">Обработка края</div>
                    <input type="text" class="input_style" name="edge" size="10" value="<?= $orders['edge'] ?>">


                    <br>
                    <br>
                    <button type="submit" class="common_button">Изменить</button>
                </div>
        </form>
        <?php
    }
    ?>


    </div>

</div>
</body>


