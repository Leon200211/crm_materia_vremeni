<?php


#=================================================================================
# Страница с отмечанием браков и замен и возвратов
#=================================================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';



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


$id = $_GET['id'];
$info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id'");
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
<!--    <link rel="stylesheet" href="../assets/css/life_search.css">-->


    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/other_features_from_status_page/style_for_with_defective.css">


    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../assets/script/paint_update.js" defer></script>
    <script src="../assets/script/app.js" defer></script>
</head>


<body>
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

        <div class="main_title">Брак по заказу №<?= $id ?></div>




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
           $info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id' AND `room` = '$room' AND `category` != 'services' and `category` != 'sewing' and `category` != 'modification'");
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
                        <?php
                        if($_SESSION['state'] == 'admin'){
                            # если выставлена цена поставщика или имеет статус отмена или имеет статус брак или имеет статус замена
                            if($info_pink_order_while['supplier_price'] != 0 or $info_pink_order_while['additional_info'] == "Отмена" or $info_pink_order_while['additional_info'] == "Брак"  or $info_pink_order_while['additional_info'] == "Замена"){
                                # если товар имеет статус отмена или имеет статус брак или имеет статус замена, то можем убрать этот статус
                                if($info_pink_order_while['additional_info'] == "Брак" or $info_pink_order_while['additional_info'] == "Отмена" or $info_pink_order_while['additional_info'] == "Замена"){
                                    ?>
                                    <td style="color:red" align="center" width="60"><?= $info_pink_order_while['additional_info'] ?></td>

                                    <form action="order_full_info_marriage_updata.php" method="post">
                                        <input type="hidden" name="id" value="<?= $info_pink_order_while['id'] ?>">
                                        <input type="hidden" name="id_order" value="<?= $id ?>">
                                        <!--  Убрать текущий статус-->
                                        <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Убрать <?= $info_pink_order_while['additional_info'] ?></button></td>
                                        <td></td>
                                    </form>
                                    <?php
                                    // добавить статус брак или замена
                                }else {
                                    if($info_pink_order_while['additional_info'] == "Брак (цех)"){
                                        ?>
                                        <td style="color:red" class="tb_title_info">Брак (цех)</td>
                                        <?php
                                    }else if($info_pink_order_while['additional_info'] == ""){
                                        ?>
                                        <td style="color:green" class="tb_title_info">Доставлено</td>
                                        <?php
                                    }
                                    ?>
                                    <form action="mail_page_marriage.php" method="post">
                                        <input type="hidden" name="id_in_db" value="<?= $info_pink_order_while['id'] ?>">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <input type="hidden" name="id_paragraph" value="<?= $info_pink_order_while['id_paragraph'] ?>">
                                        <input type="hidden" name="provider" value="<?= $info_pink_order_while['provider'] ?>">
                                        <input type="hidden" name="description" value="<?= $info_pink_order_while['description'] ?>">
                                        <input type="hidden" name="quantity" value="<?= $info_pink_order_while['quantity'] ?>">
                                        <input type="hidden" name="size" value="<?= $info_pink_order_while['size'] ?>">
                                        <input type="hidden" name="type" value="<?= 'm' ?>">
                                        <input type="hidden" name="room" value="<?= $room ?>">
                                        <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Брак</button></td>
                                    </form>



                                    <form action="order_full_info_marriage_designer.php" method="post">
                                        <input type="hidden" name="id_in_db" value="<?= $info_pink_order_while['id'] ?>">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Замена</button></td>
                                    </form>

                                    <?php
                                }
                                // выставить статус отмены ткани или замены если товар еще не пришел
                            } else if($info_pink_order_while['supplier_price'] == 0 and $info_pink_order_while['additional_info'] != "Замена" and $info_pink_order_while['additional_info'] != "Заменено") {
                                ?>
                                <td class="tb_title_info">Ожидание доставки</td>
                                <td></td>
                                <form action="mail_page_marriage.php" method="post">
                                    <input type="hidden" name="id_paragraph" value="<?= $info_pink_order_while['id_paragraph'] ?>">
                                    <input type="hidden" name="id_in_db" value="<?= $info_pink_order_while['id'] ?>">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="provider" value="<?= $info_pink_order_while['provider'] ?>">
                                    <input type="hidden" name="description" value="<?= $info_pink_order_while['description'] ?>">
                                    <input type="hidden" name="quantity" value="<?= $info_pink_order_while['quantity'] ?>">
                                    <input type="hidden" name="size" value="<?= $info_pink_order_while['size'] ?>">
                                    <input type="hidden" name="type" value="<?= 'c' ?>">
                                    <input type="hidden" name="room" value="<?= $room ?>">
                                    <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Отмена ткани</button></td>
                                </form>

                                <form action="order_full_info_marriage_designer.php" method="post">
                                    <input type="hidden" name="id_in_db" value="<?= $info_pink_order_while['id'] ?>">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Замена</button></td>
                                </form>
                                <?php
                                // убрать замену если товар еще не пришел
                            } else if($info_pink_order_while['supplier_price'] == 0 and $info_pink_order_while['additional_info'] == "Замена") {
                                ?>
                                <td style="color:red" align="center" width="60"><?= $info_pink_order_while['additional_info'] ?></td>

                                <form action="order_full_info_marriage_updata.php" method="post">
                                    <input type="hidden" name="id" value="<?= $info_pink_order_while['id'] ?>">
                                    <input type="hidden" name="id_order" value="<?= $id ?>">
                                    <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Убрать <?= $info_pink_order_while['additional_info'] ?></button></td>
                                    <td></td>
                                </form>
                                <?php
                                // убрать заменено если товар еще не пришел
                            }else if($info_pink_order_while['supplier_price'] == 0 and $info_pink_order_while['additional_info'] == "Заменено") {
                                ?>
                                <td style="color:red" align="center" width="60"><?= $info_pink_order_while['additional_info'] ?></td>

                                <form action="order_full_info_marriage_updata.php" method="post">
                                    <input type="hidden" name="id" value="<?= $info_pink_order_while['id'] ?>">
                                    <input type="hidden" name="id_order" value="<?= $id ?>">
                                    <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Убрать <?= $info_pink_order_while['additional_info'] ?></button></td>
                                    <td></td>
                                </form>
                                <?php
                            }
                        }else if ($_SESSION['state'] == 'workshop'){

                            if($info_pink_order_while['supplier_price'] != 0){
                                if($info_pink_order_while['additional_info'] == "Брак (цех)"){
                                    ?>
                                    <td style="color:red" class="tb_title_info">Брак (цех)</td>

                                    <form action="order_full_info_marriage_updata.php" method="post">
                                        <input type="hidden" name="id" value="<?= $info_pink_order_while['id'] ?>">
                                        <input type="hidden" name="id_order" value="<?= $id ?>">
                                        <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Убрать <?= $info_pink_order_while['additional_info'] ?></button></td>
                                        <td></td>
                                    </form>
                                    <?php
                                }else if($info_pink_order_while['additional_info'] == ""){
                                    ?>
                                    <td style="color:green" class="tb_title_info">Доставлено</td>

                                    <form action="order_full_info_marriage_updata.php" method="post">
                                        <input type="hidden" name="id" value="<?= $info_pink_order_while['id'] ?>">
                                        <input type="hidden" name="id_order" value="<?= $id ?>">

                                        <input type="hidden" name="type_workshop" value="1">

                                        <td><button type="submit" class="common_button" onclick="return confirm('Подтверждаю');">Брак (цех)</button></td>
                                    </form>
                                    <?php
                                }
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