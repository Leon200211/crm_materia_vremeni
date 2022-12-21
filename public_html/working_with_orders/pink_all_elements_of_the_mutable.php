<?php


#=================================================================
# Страница отображение содержимого заказа и информации по нему, главная страница для договора
#=================================================================







session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}




require_once '../connect_to_database.php';


//  -------------------------------
//  для проверки на создание заказа
$id_pink_order = $_GET['id_pink_order'];
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------


$id_pink_order = $_GET['id_pink_order'];
$info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order'");
$order_sum = 0;

while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
    $order_sum += $info_pink_order_while['quantity'] * $info_pink_order_while['price'];
}


$info_pink_order_new = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_pink_order'");
$info_pink_order_new = mysqli_fetch_assoc($info_pink_order_new);





function convert($name){
    $rusMonthNames = [
        NULL => '-',
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
<!--    <link rel="stylesheet" href="../assets/css/style.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->

    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/common_styles/table_room_style.css">
    <link rel="stylesheet" href="../assets/css/style_pink_page/style_for_work_with_pink_page.css">



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
<div class="common_div_body">

    <div>
        <div class="back_title">
            Заказы / Заказ № <?= $id_pink_order ?>
        </div>
        <a href="edit_pink_page.php" class="common_back_href">Назад</a>
    </div>

    <div class="pink_page_line_one">

        <div class="pink_page_line_one_col_one">

            <div class="pink_page_line_one_col_one_line_one">
                <div class="pink_page_line_one_col_one_line_one_left">
                    <div class="pink_page_line_one_col_one_title">Заказ № <?= $id_pink_order ?></div>
                    <div class="pink_page_line_one_col_one_state">
                        Статус заказа: <?=$info_pink_order_new['pink_state']?>
                    </div>
                </div>

                <div class="pink_page_line_one_col_one_line_one_right">
                    <form method="post">
                        <a href="pink_cap_uppdate.php?id=<?= $id_pink_order ?>" class="common_back_href">Изменить шапку р.с.</a>
                    </form>
                </div>

            </div>

            <div class="function_for_pdf">
                <div class="function_for_pdf_elem">
                    <form method="post">
                        <a href="AAA_TEST_PINK.php?id=<?= $id_pink_order ?>" class="common_back_href">Сгенерировать PDF</a>
                    </form>
                </div>
                <div class="function_for_pdf_elem">
                    <a href="<?="../assets/pdf_file/Pdf_file_for_"  . $id_pink_order . ".pdf" ?>?buster=<?= time() ?>"  target='_blank' class="common_back_href">Показать PDF</a>
                </div>
                <div class="function_for_pdf_elem_dow">
                    <a class="doing_link" href="export_pdf.php?id=<?=$id_pink_order?>">
                        <img src="../assets/img_for_style/download.png" width="25" height="25">
                    </a>
                </div>
            </div>
        </div>


        <div class="pink_page_line_one_col_two">
            <div>
                <div class="pink_page_line_one_col_two_blog">
                    <div class="pink_page_line_one_col_two_blog_title">
                        Общая сумма заказа:
                    </div>
                    <div class="pink_page_line_one_col_two_blog_sum">
                        <?=$order_sum?>
                    </div>
                </div>
                <hr class="hr-line">
                <div class="pink_page_line_one_col_two_blog">
                    <div class="pink_page_line_one_col_two_blog_line">
                        <div>
                            <div class="pink_page_line_one_col_two_blog_title">
                                Сумма предоплаты:
                            </div>
                            <div class="pink_page_line_one_col_two_blog_sum">
                                <?=$info_pink_order_new['prepayment']?>
                            </div>
                        </div>
                        <div>
                            <button id="show_bar" class="img_btn">
                                <img src="../assets/img_for_style/edit.png" width="25" height="25">
                            </button>
                        </div>
                    </div>

                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $("#show_bar").click(function() {
                                $("#bar_block").slideToggle();
                                if ($("#show_bar").html() == 'Отмена') {
                                    $("#show_bar").html('<img src="../assets/img_for_style/edit.png" width="25" height="25">');
                                } else {
                                    $("#show_bar").html('Отмена');
                                }
                            });
                        });
                    </script>
                    <style>
                        #bar_block {display: none;}
                    </style>

                    <div id="bar_block" class="pre_edit">
                        <form action="pink_prepayment_amount.php?id_pink_order=<?= $id_pink_order ?>" method="post">
                            <input type="search" name="prepayment_amount" value="<?=$info_pink_order_new['prepayment']?>" class="input_style">
                            <input type="submit" class="common_button" value="Изменить">
                        </form>
                    </div>


                </div>
            </div>

        </div>

    </div>












        <div class="room_list">
            <div id="room_list" class="room_list">
            <?php
            $info_pink_order_room = mysqli_query($connect, "SELECT distinct `room` FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `room` != '--'");
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
            <div class="room_list">
                <button id="add_room" class="common_button_room_list">
                    <img src="../assets/img_for_style/add.png" width="25" height="25">
                </button>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#add_room").click(function() {
                            $("#bar_block_room").slideToggle();
                            if ($("#add_room").html() == 'Отмена') {
                                $("#add_room").html('<img src="../assets/img_for_style/add.png" width="25" height="25">');
                            } else {
                                $("#add_room").html('Отмена');
                            }
                        });
                    });
                </script>
                <style>
                    #bar_block_room {display: none;}
                </style>
                <div id="bar_block_room" class="add_room">
                    <form action="pink_all_elements_of_the_mutable_add_room.php?id_pink_order=<?= $id_pink_order ?>" method="post">
                        <input type="search" name="room" placeholder="Новая комната" class="input_style">
                        <input type="submit" class="common_button" value="Добавить">
                    </form>
                </div>
            </div>
        </div>







        <div class="main_field_div_for_room">


        <?php
        foreach ($room_arr as $room) {
            $info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `room` = '$room'");
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

                <div class="pink_room_title_blog">
                    <div class="table_room_name">
                        <div class="table_room_name title">
                            Комната:
                        </div>
                        <div class="table_room_name room">
                            <?= $room ?>
                        </div>
                    </div>

                    <div class="pink_room_delete">
                        <?php
                        if(!(($_SESSION["state"] == 'designer' or $_SESSION["state"] == 'workshop') AND $info_pink_order_new['pink_state'] != 'Создание роз. стр.')){
                            ?>
                            <style>
                                .twitterbird {
                                    margin-bottom: 10px;
                                    width: 30px;
                                    height:30px;
                                    display:block;
                                    background:transparent url('../assets/img_for_style/delete.png') center top no-repeat;
                                }

                                .twitterbird:hover {
                                    margin-bottom: 10px;
                                    width: 30px;
                                    height:30px;
                                    display:block;
                                    background-image: url('../assets/img_for_style/delete_2.png');
                                }
                            </style>
                                <a href="pink_all_elements_of_the_mutable_delete_room.php?id_pink_order=<?= $id_pink_order ?>&room=<?= $room ?>" class="twitterbird" onclick="return confirm('Удалить?');"></a>
                            <?php
                        }
                        ?>
                    </div>
                </div>



                <tr>
                    <th class="th_title_info">Номер параграфа</th>
                    <th class="th_title_info">Тип</th>
                    <th class="th_title_info">Наименование</th>
                    <th class="th_title_info">Цвет</th>
                    <th class="th_title_info">Количество</th>
                    <th class="th_title_info">Цена<br>за единицу</th>
                    <th class="th_title_info">Сумма</th>
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
                        <?php
                        if($info_pink_order_while['id_paragraph'] != 0 and $info_pink_order_while['category'] != 'sewing' and $info_pink_order_while['category'] != 'modification'){
                            if(!($_SESSION["state"] == 'designer' AND $info_pink_order_new['pink_state'] != 'Создание роз. стр.' AND $info_pink_order_while['additional_info'] != 'Замена' AND $info_pink_order_while['additional_info'] != 'Заменено')){
                                ?>
                            <td class="tb_title_info"><a href="pink_all_elements_of_the_mutable_update.php?id=<?= $info_pink_order_while['id'] ?>" class="common_back_href">Изменить</a></td>
                            <?php
                            }
                        }else if($info_pink_order_while['id_paragraph'] != 0 and $_SESSION['state'] == 'admin'){
                            ?>
                            <td class="tb_title_info"><a href="pink_all_elements_of_the_mutable_update.php?id=<?= $info_pink_order_while['id'] ?>" class="common_back_href">Изменить</a></td>
                            <?php
                        }

                        if($_SESSION["state"] == 'admin'){
                            ?>
                            <td class="tb_title_info"><a href="pink_all_elements_of_the_mutable_delete.php?id=<?= $info_pink_order_while['id'] ?>&id_pink_order=<?= $info_pink_order_while['id_pink_order'] ?>&id_paragraph=<?= $info_pink_order_while['id_paragraph'] ?>&room=<?= $room ?>" class="common_back_href" onclick="return confirm('Удалить?');">Удалить</a></td>
                            <?php
                        }else if($_SESSION["state"] == 'designer' AND $info_pink_order_new['pink_state'] == 'Создание роз. стр.'){
                            ?>
                            <td class="tb_title_info"><a href="pink_all_elements_of_the_mutable_delete.php?id=<?= $info_pink_order_while['id'] ?>&id_pink_order=<?= $info_pink_order_while['id_pink_order'] ?>&id_paragraph=<?= $info_pink_order_while['id_paragraph'] ?>&room=<?= $room ?>" class="common_back_href" onclick="return confirm('Удалить?');">Удалить</a></td>
                            <?php
                        }
                        ?>
                    </tr>

                <?php
                }
                ?>
                </tbody>
            </table>


            <div class="add_product">
                <?php
                if($_SESSION['state'] != 'workshop'){
                    ?>
                    <form method="post">
                        <a href="pink_choice_of_action.php?id_pink_order=<?= $id_pink_order ?>&room=<?= $room ?>" class="common_back_href">Добавить</a>
                    </form>
                <?php
                }
                ?>

            </div>

            </div>
            <?php
        }
        ?>
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


        <div class="room_list">
            <div id="room_list" class="room_list">
                <button class="common_button_room_list show">Услуги</button>
            </div>
        </div>
        <div class="main_field_div_for_room">
            <table class="table_title_info">
                <h3>Услуги</h3>
                <tr>
                    <th class="th_title_info">Номер параграфа</th>
                    <th class="th_title_info">Наименование</th>
                    <th class="th_title_info">Количество</th>
                    <th class="th_title_info">Цена<br>за единицу</th>
                    <th class="th_title_info">Сумма</th>
                </tr>
                <tbody>
                <?php
                $info_pink_order = mysqli_query($connect, "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_pink_order' AND `category` = 'services'");
                while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
                    ?>
                    <tr>
                        <td class="tb_title_info"><?= $info_pink_order_while['id_paragraph'] ?></td>
                        <td class="tb_title_info"><?= $info_pink_order_while['description'] ?></td>
                        <td class="tb_title_info"><?= $info_pink_order_while['quantity'] ?></td>
                        <td class="tb_title_info"><?= $info_pink_order_while['price'] ?></td>
                        <td class="tb_title_info"><?= $info_pink_order_while['quantity'] * $info_pink_order_while['price'] ?></td>
                        <?php
                        if($info_pink_order_while['id_paragraph'] != 0){
                            if(!($_SESSION["state"] == 'designer' AND $info_pink_order_new['pink_state'] != 'Создание роз. стр.' AND $info_pink_order_while['additional_info'] != 'Замена')){
                                ?>
                                <td class="tb_title_info"><a href="pink_all_elements_of_the_mutable_update.php?id=<?= $info_pink_order_while['id'] ?>" class="common_back_href">Изменить</a></td>
                                <td class="tb_title_info"><a href="pink_all_elements_of_the_mutable_delete.php?id=<?= $info_pink_order_while['id'] ?>&id_pink_order=<?= $info_pink_order_while['id_pink_order'] ?>&id_paragraph=<?= $info_pink_order_while['id_paragraph'] ?>&room=--" class="common_back_href" onclick="return confirm('Удалить?');">Удалить</a></td>
                                <?php
                            }
                        }
                        ?>
                    </tr>

                    <?php
                }
                ?>
                </tbody>
            </table>
            <div class="add_product">
                <form method="post">
                    <a href="pink_choice_of_action.php?id_pink_order=<?= $id_pink_order ?>&category=<?= 'services' ?>" class="common_back_href">Добавить услуги</a>
                </form>
            </div>
        </div>


    </div>





</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>

