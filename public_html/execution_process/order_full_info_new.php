<?php

#====================================================================
#====================================================================
# ГЛАВНАЯ СТРАНИЦА ФУНКЦИОЛНАЛА
# ИМЕННО ЗДЕСЬ ПРОИСХОДИТ СМЕНА СТАТУСА
# ВЫСТАВЛЕНИЕ КОЭФИЦИЕНТОВ И БРАКОВ
#====================================================================
#====================================================================




session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}
require_once '../connect_to_database.php';


//  -------------------------------
//  для проверки на создание заказа
if(!empty($_GET['id'])){
    $id = $_GET['id'];
}
if(!empty($_POST['id'])){
    $id = $_POST['id'];
};
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin' and $_SESSION['state'] != 'workshop') {
    echo "Доступ запрещен";
    die;
}

$global_executor_id = $select['executor_id'];
$global_order_state = $select['pink_state'];

// --------------------------------------



//  -------------------------------
//  удаление обычных уведомлений
$user_id = $_SESSION['id_user'];
$select_test = mysqli_query($connect, "SELECT * FROM `notice` WHERE `id_user` = '$user_id' AND `id_order` = '$id'");
$select_test = mysqli_fetch_assoc($select_test);
if(!empty($select_test['id'])){
    mysqli_query($connect, "DELETE FROM `notice` WHERE `id_user` = '$user_id' AND `id_order` = '$id'");
}
//  -------------------------------
//  удаление важных уведомлений на прочтение
$select_important_notice = mysqli_query($connect, "SELECT `id` FROM `notice_important` WHERE `id_user` = '$user_id' AND `id_order` = '$id' AND `remove` = 'read'");
while($select_important_notice_while = mysqli_fetch_assoc($select_important_notice)){
    if(!empty($select_important_notice_while['id'])){
        mysqli_query($connect, "DELETE FROM `notice_important` WHERE `id` = '{$select_important_notice_while['id']}'");
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
<!--    <link rel="stylesheet" href="../assets/css/style_messag.css">-->
<!--    <link rel="stylesheet" href="../assets/css/full_info_css.css">-->
<!--    <link rel="stylesheet" href="../assets/css/style_for_paint.css">-->
<!--    <link rel="stylesheet" href="../assets/css/accountant_stule.css">-->

    <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">
    <link rel="stylesheet" href="../assets/css/execution_process/main_page.css">

    <title>Pink</title>

    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
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
    // если не изменяем статус, то выводим информацию
    if(empty($_POST['state_order'])){
        if(!empty($_GET['id'])){
            $id = $_GET['id'];
        }
        if(!empty($_POST['id'])){
            $id = $_POST['id'];
        }
        ?>
        <div>
            <div class="back_title">
                Заказы / Заказ № <?= $id ?>
            </div>
            <a href="messag.php" class="common_back_href">Назад</a>
        </div>

        <div class="executor_page_line_one">


            <div class="executor_page_line_one_block_one">

                <div class="executor_page_line_one_block_one_title_line">
                    <div class="executor_page_line_one_block_one_title_line_text">
                        Заказ № <?= $id ?>
                    </div>
                    <div class="executor_page_line_one_block_one_title_line_href">
                        <a href="<?="../assets/pdf_file/Pdf_file_for_"  . $id . ".pdf"?>?buster=<?= time() ?>"  target='_blank' class="common_back_href">Розовая страница </a>
                    </div>
                    <div class="executor_page_line_one_block_one_title_line_href">
                        <a href="<?="../assets/price_pdf/price_pdf_"  . $id . ".pdf"?>?buster=<?= time() ?>"  target='_blank' class="common_back_href">Эскизы документ</a>
                    </div>

                    <?php
                    if($_SESSION['state'] == 'admin' or
                    ($_SESSION['state'] == 'designer' and $_SESSION['id_user']== $global_executor_id and ($global_order_state == 'Поступил в салон' or $global_order_state == 'Доставка клиенту')) or
                    ($_SESSION['state'] == 'workshop' and $_SESSION['id_user'] == $global_executor_id)){
                        ?>
                        <div class="executor_page_line_one_block_one_title_line_href">
                            <a href="create_delivery_acceptance_certificate.php?id=<?= $id ?>" target="_blank" class="common_back_href">Сгенерировать АКТ</a>
                        </div>

                    <?php
                    }
                    ?>

                </div>

                <div class="executor_page_line_one_block_one_line"></div>

                <div class="executor_page_line_one_block_one_title_state">
                    <?php
                    // РАБОТА СО СТАТУСАМИ
                    $sql_for_line_one = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id'";
                    $select_for_line_one = mysqli_query($connect, $sql_for_line_one);
                    $select_for_line_one = mysqli_fetch_assoc($select_for_line_one);


                    $order_state = $select_for_line_one["pink_state"];
                    $order_id = $select_for_line_one["id_pink_order"];
                    $id_executor = $select_for_line_one['executor_id'];
                    // пункты для изменения статуса
                    if ($_SESSION['state'] == 'admin') {
                        if (empty($_POST['state_order'])){
                        ?>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="order_id_new" value="<?= $order_id ?>">

                            <div class="search_info_state_change_title">
                                Текущий статус: <?=$select_for_line_one['pink_state']?>
                            </div>

                            <div class="state_change">
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-1" type="radio" value="Создание роз. стр.">
                                    <label for="radio-1">Создание роз. стр.</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-2" type="radio" value="Создание эскизов">
                                    <label for="radio-2">Создание эскизов</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-3" type="radio" value="Заказ без пошива">
                                    <label for="radio-3">Заказ без пошива</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-4" type="radio" value="Поступил в цех">
                                    <label for="radio-4">Поступил в цех</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-5" type="radio" value="Выдан закройщику">
                                    <label for="radio-5">Выдан закройщику</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-6" type="radio" value="Пошив">
                                    <label for="radio-6">Пошив</label>
                                </div>
                                <div class="form_radio_btn" id="for_courier_name">
                                    <input name="state_order" id="radio-7" type="radio" value="Ожидание отправки в салон">
                                    <label for="radio-7">Ожидание отправки в салон</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-8" type="radio" value="Доставка в салон">
                                    <label for="radio-8">Доставка в салон</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-9" type="radio" value="Поступил в салон">
                                    <label for="radio-9">Поступил в салон</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-10" type="radio" value="Доставка клиенту">
                                    <label for="radio-10">Доставка клиенту</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-11" type="radio" value="Завершен">
                                    <label for="radio-11">Завершен</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-12" type="radio" value="Возврат дизайнеру">
                                    <label for="radio-12">Возврат дизайнеру</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-13" type="radio" value="Брак ткани">
                                    <label for="radio-13">Брак ткани</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-14" type="radio" value="Перевыбор ткани в салоне">
                                    <label for="radio-14">Перевыбор ткани в салоне</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-15" type="radio" value="Возврат ткани">
                                    <label for="radio-15">Возврат ткани</label>
                                </div>
                            </div>





                            <div id="input_courier_name">
                                <input class="input_style" id="courier_name" name="courier_name">
                                <label for="courier_name">Имя курьера</label>
                            </div>
                            <div id="input_note_designer">
                                <input class="input_style" id="note_designer" name="note_designer">
                                <label for="note_designer">Примечание для дизайнера</label>
                            </div>
                            <style>
                                #input_courier_name {display: none;}
                                #input_note_designer {display: none;}
                            </style>
                            <script type="text/javascript">
                                var div = document.getElementById('radio-8');
                                div.addEventListener('click', function (event) {
                                    $("#input_courier_name").slideToggle();
                                });

                                var div = document.getElementById('radio-14');
                                div.addEventListener('click', function (event) {
                                    $("#input_note_designer").slideToggle();
                                });
                                var div = document.getElementById('radio-12');
                                div.addEventListener('click', function (event) {
                                    $("#input_note_designer").slideToggle();
                                });
                            </script>

                            <div>
                                <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                            </div>




                        </form>
                        <?php
                        }
                    }else if($_SESSION['state'] == 'designer'){
                        if ($order_state == 'Создание роз. стр.' or
                            $order_state == 'Создание эскизов'){
                            ?>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="order_id_new" value="<?= $order_id ?>">

                                <div class="search_info_state_change_title">
                                    Текущий статус: <?=$select_for_line_one['pink_state']?>
                                </div>

                                <div class="state_change">

                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-1" type="radio" value="Создание роз. стр.">
                                        <label for="radio-1">Создание роз. стр.</label>
                                    </div>
                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-2" type="radio" value="Создание эскизов">
                                        <label for="radio-2">Создание эскизов</label>
                                    </div>
                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-3" type="radio" value="Заказ без пошива">
                                        <label for="radio-3">Заказ без пошива</label>
                                    </div>
                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-4" type="radio" value="Поступил в цех">
                                        <label for="radio-4">Поступил в цех</label>
                                    </div>


                                    <div>
                                        <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                    </div>
                                </div>
                            </form>
                            <?php
                        } else if(
                            $order_state == 'Доставка в салон'){
                            ?>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="order_id_new" value="<?= $order_id ?>">

                                <div class="search_info_state_change_title">
                                    Текущий статус: <?=$select_for_line_one['pink_state']?>
                                </div>

                                <div class="state_change">
                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-1" type="radio" value="Поступил в салон">
                                        <label for="radio-1">Поступил в салон</label>
                                    </div>

                                    <div>
                                        <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                    </div>
                                </div>
                            </form>
                            <?php
                        }else if(
                            $order_state == 'Поступил в салон' or
                            $order_state == 'Доставка клиенту'){
                            ?>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="order_id_new" value="<?= $order_id ?>">

                                <div class="search_info_state_change_title">
                                    Текущий статус: <?=$select_for_line_one['pink_state']?>
                                </div>

                                <div class="state_change">
                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-1" type="radio" value="Доставка клиенту">
                                        <label for="radio-1">Доставка клиенту</label>
                                    </div>
                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-2" type="radio" value="Завершен">
                                        <label for="radio-2">Завершен</label>
                                    </div>

                                    <div>
                                        <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                    </div>
                                </div>
                            </form>
                            <?php
                        }else if($order_state == 'Перевыбор ткани в салоне' or
                            $order_state == 'Возврат дизайнеру' or
                            $order_state == 'Возврат ткани'){
                            ?>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="hidden" name="order_id_new" value="<?= $order_id ?>">

                                <div class="search_info_state_change_title">
                                    Текущий статус: <?=$select_for_line_one['pink_state']?>
                                </div>

                                <div class="state_change">
                                    <div class="form_radio_btn">
                                        <input name="state_order" id="radio-1" type="radio" value="Поступил в цех">
                                        <label for="radio-1">Поступил в цех</label>
                                    </div>

                                    <div>
                                        <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                    </div>
                                </div>
                            </form>
                            <?php
                        }
                    } else if($_SESSION['state'] == 'workshop') {        ////////////////////////////////////////////////////
                        if($id_executor != $_SESSION['id_user']
                            and ($order_state == 'Поступил в цех' or
                                $order_state == 'Выдан закройщику' or
                                $order_state == 'Пошив' or
                                $order_state == 'Ожидание отправки в салон' or
                                $order_state == 'Брак ткани')){
                        ?>
                        <form method="post">

                            <div class="search_info_state_change_title">
                                Текущий статус: <?=$select_for_line_one['pink_state']?>
                            </div>

                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="hidden" name="order_id_new" value="<?= $order_id ?>">
                            <div class="state_change">
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-1" type="radio" value="Поступил в цех">
                                    <label for="radio-1">Поступил в цех</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-2" type="radio" value="Выдан закройщику">
                                    <label for="radio-2">Выдан закройщику</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-3" type="radio" value="Пошив">
                                    <label for="radio-3">Пошив</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-4" type="radio" value="Ожидание отправки в салон">
                                    <label for="radio-4">Ожидание отправки в салон</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-5" type="radio" value="Доставка в салон">
                                    <label for="radio-5">Доставка в салон</label>
                                </div>
                                <div class="form_radio_btn">
                                    <input name="state_order" id="radio-6" type="radio" value="Брак ткани">
                                    <label for="radio-6">Брак ткани</label>
                                </div>
                            </div>

                            <div id="input_courier_name">
                                <input class="input_style" id="courier_name" name="courier_name">
                                <label for="courier_name">Имя курьера</label>
                            </div>
                            <style>
                                #input_courier_name {display: none;}
                            </style>
                            <script type="text/javascript">
                                var div = document.getElementById('radio-5');
                                div.addEventListener('click', function (event) {
                                    $("#input_courier_name").slideToggle();
                                });
                            </script>

                            <div>
                                <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                            </div>

                        </form>
                        <?php
                        }else if($id_executor == $_SESSION['id_user']){
                            if ($order_state == 'Создание роз. стр.' or
                                $order_state == 'Создание эскизов'){
                                ?>
                                <form method="post">

                                    <div class="search_info_state_change_title">
                                        Текущий статус: <?=$select_for_line_one['pink_state']?>
                                    </div>

                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="order_id_new" value="<?= $order_id ?>">
                                    <div class="state_change">
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-1" type="radio" value="Создание роз. стр.">
                                            <label for="radio-1">Создание роз. стр.</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-2" type="radio" value="Создание эскизов">
                                            <label for="radio-2">Создание эскизов</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-3" type="radio" value="Заказ без пошива">
                                            <label for="radio-3">Заказ без пошива</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-4" type="radio" value="Поступил в цех">
                                            <label for="radio-4">Поступил в цех</label>
                                        </div>

                                        <div>
                                            <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                        </div>
                                    </div>
                                </form>
                                <?php
                            } else if(
                                $order_state == 'Доставка в салон'){
                                ?>
                                <form method="post">

                                    <div class="search_info_state_change_title">
                                        Текущий статус: <?=$select_for_line_one['pink_state']?>
                                    </div>

                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="order_id_new" value="<?= $order_id ?>">
                                    <div class="state_change">
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-1" type="radio" value="Поступил в салон">
                                            <label for="radio-1">Поступил в салон</label>
                                        </div>

                                        <div>
                                            <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }else if(
                                $order_state == 'Поступил в салон' or
                                $order_state == 'Доставка клиенту'){
                                ?>
                                <form method="post">

                                    <div class="search_info_state_change_title">
                                        Текущий статус: <?=$select_for_line_one['pink_state']?>
                                    </div>

                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="order_id_new" value="<?= $order_id ?>">
                                    <div class="state_change">
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-1" type="radio" value="Доставка клиенту">
                                            <label for="radio-1">Доставка клиенту</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-2" type="radio" value="Завершен">
                                            <label for="radio-2">Завершен</label>
                                        </div>

                                        <div>
                                            <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }else if($order_state == 'Перевыбор ткани в салоне' or
                                $order_state == 'Возврат дизайнеру' or
                                $order_state == 'Возврат ткани'){
                                ?>
                                <form method="post">

                                    <div class="search_info_state_change_title">
                                        Текущий статус: <?=$select_for_line_one['pink_state']?>
                                    </div>

                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="order_id_new" value="<?= $order_id ?>">
                                    <div class="state_change">
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-1" type="radio" value="Поступил в цех">
                                            <label for="radio-1">Поступил в цех</label>
                                        </div>

                                        <div>
                                            <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                        </div>
                                    </div>
                                </form>
                            <?php
                            }else if(($order_state == 'Поступил в цех' or
                                $order_state == 'Выдан закройщику' or
                                $order_state == 'Пошив' or
                                $order_state == 'Ожидание отправки в салон' or
                                $order_state == 'Брак ткани')){
                                ?>
                                <form method="post">

                                    <div class="search_info_state_change_title">
                                        Текущий статус: <?=$select_for_line_one['pink_state']?>
                                    </div>

                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <input type="hidden" name="order_id_new" value="<?= $order_id ?>">
                                    <div class="state_change">
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-1" type="radio" value="Поступил в цех">
                                            <label for="radio-1">Поступил в цех</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-2" type="radio" value="Выдан закройщику">
                                            <label for="radio-2">Выдан закройщику</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-3" type="radio" value="Пошив">
                                            <label for="radio-3">Пошив</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-4" type="radio" value="Ожидание отправки в салон">
                                            <label for="radio-4">Ожидание отправки в салон</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-5" type="radio" value="Доставка в салон">
                                            <label for="radio-5">Доставка в салон</label>
                                        </div>
                                        <div class="form_radio_btn">
                                            <input name="state_order" id="radio-6" type="radio" value="Брак ткани">
                                            <label for="radio-6">Брак ткани</label>
                                        </div>
                                    </div>


                                    <div id="input_courier_name">
                                        <input class="input_style" id="courier_name" name="courier_name">
                                        <label for="courier_name">Имя курьера</label>
                                    </div>
                                    <style>
                                        #input_courier_name {display: none;}
                                    </style>
                                    <script type="text/javascript">
                                        var div = document.getElementById('radio-5');
                                        div.addEventListener('click', function (event) {
                                            $("#input_courier_name").slideToggle();
                                        });
                                    </script>

                                    <div>
                                        <input type="submit" value="Изменить" class="state_change_button" onclick="return confirm('Подтверждаю');">
                                    </div>

                                </form>
                            <?php
                            }
                        }
                    } // блок со статусами конец
                    ?>
                </div>


                <div class="executor_page_line_one_block_one_line"></div>

                <div class="executor_page_line_one_block_one_title_footer">
                    <?php   // брак ткани для бухгалтера
                    if($_SESSION['state'] == 'admin'){
                        ?>
                        <div class="executor_page_line_one_block_one_title_line_href">
                            <a href="order_full_info_coefficient.php?id=<?= $id ?>" class="common_back_href">Коэффициент</a>
                        </div>
                        <div class="executor_page_line_one_block_one_title_line_href">
                            <a href="order_full_info_marriage.php?id=<?= $id ?>" class="common_back_href">Брак, замена, отмена</a>
                        </div>
                        <?php
                    }else if($_SESSION['state'] == 'workshop'){
                        ?>
                        <div class="executor_page_line_one_block_one_title_line_href">
                            <a href="order_full_info_marriage.php?id=<?= $id ?>" class="common_back_href">Брак</a>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="executor_page_line_one_block_one_title_line_href">
                        <a href="goods_info.php?id=<?= $id ?>" class="common_back_href">Информация о товарах</a>
                    </div>
                </div>


            </div>


            <div class="executor_page_line_one_block_two">
                <?php

                // установки даты отправки заказа с курьером
                if(!empty($_POST['calendar'])){
                    $end_date = $_POST['calendar'];
                    $id_pink_order = $_POST['id'];

                    $end_date_arr = explode("-", $end_date);
                    $end_date_new = $end_date_arr[2] . "." . $end_date_arr[1] . "." . $end_date_arr[0];

                    $today = date("d.m.Y");
                    $result = strtotime($today) <= strtotime($end_date_new);

                    if($result) {
                        mysqli_query($connect, "UPDATE `orders_date` SET `date_end` = '$end_date_new' WHERE `orders_date`.`id_order` = '$id_pink_order'");

                        // изменил дату готовности цех
                        $select = mysqli_query($connect, "SELECT `id` FROM `users` WHERE `id` = '$global_executor_id' OR `state` = 'admin'");
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            set_notice_important($connect, $select_while['id'], $id, 'Изменена дата готовности - цех', 'read');
                        }

                    }
                }
                // установка примерной даты завершения для дизайнера
                if(!empty($_POST['calendar_2'])){
                    $end_date = $_POST['calendar_2'];
                    $id_pink_order = $_POST['id'];

                    $end_date_arr = explode("-", $end_date);
                    $end_date_new = $end_date_arr[2] . "." . $end_date_arr[1] . "." . $end_date_arr[0];

                    $today = date("d.m.Y");
                    $result = strtotime($today) <= strtotime($end_date_new);

                    if($result) {
                        mysqli_query($connect, "UPDATE `orders_date` SET `date_end_designer` = '$end_date_new' WHERE `orders_date`.`id_order` = '$id_pink_order'");

                        // изменил дату готовности дизайнер
                        $select = mysqli_query($connect, "SELECT `id` FROM `users` WHERE `state` = 'workshop' OR `state` = 'admin'");
                        while ($select_while = mysqli_fetch_assoc($select)) {
                            set_notice_important($connect, $select_while['id'], $id, 'Изменена примерная дата готовности - салон', 'read');
                        }
                    }
                }


                // титульная информация
                $sql_2 = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id'";
                $select_2 = mysqli_query($connect, $sql_2);
                foreach($select_2 as $row) {

                    // достаем дату
                    $id_order = $row['id_pink_order'];
                    $sql_date = "SELECT `date_create`, `date_end_designer`, `date_end` FROM `orders_date` WHERE `id_order` = '$id_order'";
                    $select_date = mysqli_query($connect, $sql_date);
                    $select_date = mysqli_fetch_assoc($select_date);
                    $order_data = $select_date["date_create"];


                    $order_state = $row["pink_state"];
                    $order_id = $row["id_pink_order"];
                    $id_executor = $row['executor_id'];

                    if (!empty($select_date["date_end"])) {
                        $end_date_info_workshop = $select_date["date_end"];
                    } else {
                        $end_date_info_workshop = '-';
                    }
                    if (!empty($select_date["date_end_designer"])) {
                        $end_date_info_designer = $select_date["date_end_designer"];
                    } else {
                        $end_date_info_designer = '-';
                    }
                }
                ?>

                <div class="search_info_date">Дата добавления <?= $order_data ?></div>
                <div class="search_info_date">Примерная дата готовности (цех) - <?= $end_date_info_workshop ?></div>
                <div class="search_info_date">Примерная дата завершения (дизайнер) - <?= $end_date_info_designer ?></div>
                <br>
                <?php


                // изменение примерной даты готовности для цеха
                if($_SESSION['state'] == 'admin' or $_SESSION['state'] == 'workshop') {
                    ?>
                    <div class="search_info_date">
                        <form method="post">
                            <p>Выберите дату готовности:
                                <input type="date" name="calendar">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="submit" value="Отправить" class="common_back_href"></p>
                        </form>
                    </div>
                    <?php
                }
                // изменение примерной даты готовности для дизайнера
                if($_SESSION['state'] == 'admin' or $_SESSION['state'] == 'designer') {
                    ?>
                    <div class="search_info_date">
                        <form method="post">
                            <p>Выберите примерную дату завершения:
                                <input type="date" name="calendar_2">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <input type="submit" value="Отправить" class="common_back_href"></p>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>


        </div>



        <div class="executor_page_line_two">
            <div class="executor_page_line_two_note">
                <?php
                $sql_turnover = "SELECT * FROM `turnover_table` WHERE `id_order` = '$id'";
                $select_turnover = mysqli_query($connect, $sql_turnover);
                $select_turnover = mysqli_fetch_assoc($select_turnover);
                ?>

                <div class="executor_page_line_two_note_title">
                    Заметка
                </div>


                <?php   // Изменение информации по текучки заказа
                if($_SESSION['state'] == 'admin' or $_SESSION['state'] == 'workshop') {
                    ?>

                    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                    <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $("#show_bar").click(function() {
                                $("#bar_block").slideToggle();
                                if ($("#show_bar").html() == 'Отмена') {
                                    $("#show_bar").html('Изменить информацию');
                                } else {
                                    $("#show_bar").html('Отмена');
                                }
                            });
                        });
                    </script>
                    <style>
                        #bar_block {display: none;}
                    </style>
                    <button id="show_bar" class="common_button">Изменить информацию</button>


                    <script type="text/javascript">
                        $(document).ready(function() {
                            $("#show_bar_2").click(function() {
                                $("#bar_block_2").slideToggle();
                                if ($("#show_bar_2").html() == 'Отмена') {
                                    $("#show_bar_2").html('Изменить примечание');
                                } else {
                                    $("#show_bar_2").html('Отмена');
                                }
                            });
                        });
                    </script>
                    <style>
                        #bar_block_2 {display: none;}
                    </style>
                    <button id="show_bar_2" class="common_button">Изменить примечание</button>


                    <div class="executor_page_line_two_note_edit">
                        <div id="bar_block">
                            <form action="update_turnover_table.php" method="post">
                                <div>
                                    <input name="id_order" value="<?= $id ?>" hidden>
                                    <input name="type" value="<?= 1 ?>" hidden>
                                    <div class="executor_page_line_two_note_edit_item">
                                        Стоипость пошива цех
                                        <input class="input_style" type="text" name="workshop_cost_sewing" value="<?=$select_turnover['workshop_cost_sewing']?>">
                                    </div>
                                    <div class="executor_page_line_two_note_edit_item">
                                        Стоимость цех нов пр
                                        <input class="input_style" type="text" name="workshop_cost" value="<?=$select_turnover['workshop_cost']?>">
                                    </div>
                                    <div class="executor_page_line_two_note_edit_item">
                                        Закройщик:
                                        <select class="common_select" name="performer" id="performer">
                                            <?php
                                            $sql_performer = "SELECT `id`, `name` FROM `performers` WHERE `id` = '{$select_turnover['performer']}'";
                                            $select_performer = mysqli_query($connect, $sql_performer);
                                            $select_performer = mysqli_fetch_assoc($select_performer);
                                            if(isset($select_performer)){
                                                ?>
                                                <option value="<?=@$select_performer['id']?>">Выбран: <?=@$select_performer['name']?></option>
                                            <?php
                                            }
                                            ?>
                                            <option value=""></option>
                                            <?php
                                            $sql_for_name = "SELECT * FROM `performers`";
                                            $select_for_name = mysqli_query($connect, $sql_for_name);
                                            while ($select_for_name_while = mysqli_fetch_assoc($select_for_name)){
                                                ?>
                                                <option value="<?=$select_for_name_while['id']?>"><?=$select_for_name_while['name']?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="executor_page_line_two_note_edit_item">
                                        Курьер: <?=$select_turnover['courier']?>
                                    </div>
                                    <div class="executor_page_line_two_note_edit_item">
                                        <input type="submit" class="common_back_href" name="submit" value="Изменить" />
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="bar_block_2">
                            <form action="update_turnover_table.php" method="post">
                                <div>
                                    <input name="id_order" value="<?= $id ?>" hidden>
                                    <input name="type" value="<?= 2 ?>" hidden>
                                    Примечание
                                    <div class="executor_page_line_two_note_edit_item">
                                        <textarea class="input_style" name="note"><?=$select_turnover['note']?></textarea>
                                    </div>
                                    <div class="executor_page_line_two_note_edit_item">
                                        <input type="submit" class="common_back_href" name="submit" value="Изменить" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                    <?php
                }
                ?>
            </div>


            <div class="executor_page_line_two_complaint">
                <?php
                // рекламация о браке
                if($_SESSION['state'] == 'designer' or $_SESSION['state'] == 'admin')
                {
                    ?>
                    <div>
                        <a href="complaint/create_complaint.php?id_order=<?=$id?>" class="common_button">Рекламация о браке</a>
                    </div>
                    <div class="executor_page_line_two_complaint_line"></div>
                    <?php
                }

                $sql_complaint = "SELECT * FROM `complaint_table` WHERE `id_order` = '$id'";
                $complaint = mysqli_query($connect, $sql_complaint);
                ?>
                <div class="important_indicator">
                <?php
                while($complaint_while = mysqli_fetch_assoc($complaint)){
                    ?>
                    <div>
                        <a href="<?="../assets/complaint/pdf/" . $complaint_while['file']?>?buster=<?= time() ?>"  target='_blank' class="important_indicator_href">Рекламация о браке | <?=$complaint_while['name']?> | <?=$complaint_while['date_create']?></a>
                    </div>
                    <?php
                }
                ?>
                </div>
            </div>
        </div>



        <div class="executor_page_sketches">
            <?php
            if($_SESSION['state'] != 'workshop' or ($order_state != 'Создание роз. стр.' and $order_state != 'Создание эскизов')){
                ?>
                <div class="executor_page_sketches_body">



                    <div class="info_about_update_state">
                        <h1>Эскизы</h1>
                    </div>



                    <div class="room_list">
                        <div id="room_list" class="room_list">
                            <?php
                            $info_pink_order_room = mysqli_query($connect, "SELECT distinct `room` FROM `sketches_main` WHERE `id_order` = '$id' AND `room` != '--'");
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
                    </div>



                    <div class="main_field_div_for_room">


                        <?php
                        foreach ($room_arr as $room) {
                            $info_pink_order = mysqli_query($connect, "SELECT * FROM `sketches_main` WHERE `id_order` = '$id' AND `room` = '$room'");
                            if ($room_arr[0] === $room) {
                                // это первая запись
                                ?>
                                <div class="room_main_info show"><?php
                            }else{
                                ?>
                                <div class="room_main_info">
                                <?php
                            }


                            while ($info_pink_order_while = mysqli_fetch_assoc($info_pink_order)) {
                            ?>

                                <div class="executor_page_sketches_main">

                                    <div>

                                        <div class="executor_page_sketches_main_spec">
                                            Спецификация: <?= $info_pink_order_while['specification'] ?>
                                        </div>

                                        <div class="executor_page_sketches_main_note">
                                            <div>
                                                <div class="executor_page_sketches_main_note_title">Примечание: </div>
                                                <div class="search_info_help_info_text"><?= $info_pink_order_while['note'] ?></div>
                                            </div>
                                            <div>
                                                <div class="executor_page_sketches_main_note_title">Лицевой стороной считать: </div>
                                                <div class="search_info_help_info_text"><?= $info_pink_order_while['font_side'] ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <?php
                                        if(@fopen("../assets/img/img" . $info_pink_order_while['image'], "r")) {
                                            ?>
                                            <div class="image_for_designer">
                                                <img src="../assets/img/img<?=$info_pink_order_while['image']?>?<?= filemtime('../assets/img/img' . $info_pink_order_while['image']) ?>" class="sketch">
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                </div>

                                <div class="executor_page_sketches_main_line"></div>

                            <?php
                            }
                            ?>
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
                </div>



            <?php
            }
            ?>
        </div>

    <?php
    }
    // раздел с изменение статуса заказа
    else {    // изменяем статус

        // проверяем есть ли выбранный статус
        if (!empty($_POST['state_order']) and !empty($_POST['id'])){
            $state_order = $_POST['state_order'];
            $search_get = $_POST['id'];

            //  -------------------------------
            //  удаление важных уведомлений после изменения статуса
            $select_important_notice = mysqli_query($connect, "SELECT `id` FROM `notice_important` WHERE `id_order` = '$id' AND `remove` = 'change'");
            while($select_important_notice_while = mysqli_fetch_assoc($select_important_notice)){
                if(!empty($select_important_notice_while['id'])){
                    mysqli_query($connect, "DELETE FROM `notice_important` WHERE `id` = '{$select_important_notice_while['id']}'");
                }
            }


            // !!!!!!!!!!!!!!!!!!!!!
            // для добавления уведомления на заказ
            // отправить письпа поставщикам, о доставки тканей в салон
            // доставка новыйх тканий которые были заменены
            if($state_order == "Поступил в цех") {

                $sql_post = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'";
                $select_post = mysqli_query($connect, $sql_post);
                $select_post = mysqli_fetch_assoc($select_post);
                if($select_post['pink_state'] == 'Возврат дизайнеру' or $select_post['pink_state'] == 'Перевыбор ткани в салоне' or $select_post['pink_state'] == 'Возврат ткани'){
                    // отправка письма поставщику
                    require_once('../send_request_to_supplier_3.php');
                    send_request_to_supplier_3($connect, $search_get);
                }else{
                    // отправляем письмо поставщикам
                    // если мы получаем статус поступил в цех не после возврата дизайнеру, то отправляем запрос поставщику
                    if($_SESSION['state'] == 'designer' or $_SESSION['state'] == 'admin'){
                        $select = mysqli_query($connect, "SELECT `state_of_fabric_order` FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                        $select_while = mysqli_fetch_assoc($select);
                        if($select_while['state_of_fabric_order'] != '1'){
                            // отправка письма поставщику
                            require_once('../send_request_to_supplier.php');
                            send_request_to_supplier($connect, $search_get);
                        }
                    }


                    // дата поступления в цех и поступления эскизов
                    $today = date("d.m.Y");
                    $sql_date = "UPDATE `orders_date` SET `date_entered_workshop` = '$today', `sketches_arrival` = '$today' WHERE `orders_date`.`id_order` = '$search_get';";
                    $sql_date = mysqli_query($connect, $sql_date);

                }

                // добавляем уведомление
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `state` = 'workshop' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }
            } else if($state_order == "Создание эскизов"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }

                // дата поступления розовой страницы
                $today = date("d.m.Y");
                $sql_date = "UPDATE `orders_date` SET `pink_page_arrival` = '$today' WHERE `orders_date`.`id_order` = '$search_get';";
                $sql_date = mysqli_query($connect, $sql_date);

            } else if($state_order == "Заказ без пошива"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }
            } else if($state_order == "Выдан закройщику"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin' OR `state` = 'workshop'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }

                // дата выдачи закройщику
                $today = date("d.m.Y");
                $sql_date = "UPDATE `orders_date` SET `date_delivery_cutter` = '$today' WHERE `orders_date`.`id_order` = '$search_get';";

                $sql_date = mysqli_query($connect, $sql_date);

            }  else if($state_order == "Пошив"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin' OR `state` = 'workshop'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }

                // дата начала работы
                $today = date("d.m.Y");
                $sql_date = "UPDATE `orders_date` SET `date_start_work` = '$today' WHERE `orders_date`.`id_order` = '$search_get';";
                $sql_date = mysqli_query($connect, $sql_date);


            } else if($state_order == "Ожидание отправки в салон"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin' OR `state` = 'workshop'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }
            } else if($state_order == "Доставка в салон"){

                // если курьер не указан
                if(empty($_POST['courier_name'])){
                    ?>
                    <div class="find_info">
                        <div class="body_result">
                            <div class="body_result_title">Ошибка! Не указан курьер!</div>
                            <a href="order_full_info_new.php?id=<?= $search_get ?>" class="common_back_href">Вернуться</a>
                        </div>
                    </div>
                    <?php
                    die();
                }

                // сохранение курьера
                $sql = "UPDATE `turnover_table` SET `courier` = '{$_POST['courier_name']}' WHERE `turnover_table`.`id_order` = '$search_get';";
                mysqli_query($connect, $sql);

                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT `executor_id` FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT `id` FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }

                // дата поступления розовой страницы
                $today = date("d.m.Y");
                $sql_date = "UPDATE `orders_date` SET `departure_date` = '$today' WHERE `orders_date`.`id_order` = '$search_get';";
                $sql_date = mysqli_query($connect, $sql_date);

            } else if($state_order == "Поступил в салон"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }
            } else if($state_order == "Доставка клиенту"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }
            }
            // Возврат дизайнеру
            else if($state_order == "Возврат дизайнеру"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);

                    // Важное уведомление
                    $select = mysqli_query($connect, "SELECT `id` FROM `users` WHERE `state` = 'workshop' OR `id` = '$global_executor_id' OR `state` = 'admin'");
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        set_notice_important($connect, $select_while['id'], $id, "Возврат дизайнеру <br> Примечание: {$_POST['note_designer']}", 'change');
                    }

                }
            }
            // Брак ткани
            else if($state_order == "Брак ткани"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);

                    // Важное уведомление
                    $select = mysqli_query($connect, "SELECT `id` FROM `users` WHERE `state` = 'workshop' OR `id` = '$global_executor_id' OR `state` = 'admin'");
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        set_notice_important($connect, $select_while['id'], $id, 'Брак ткани', 'change');
                    }

                }
            }
            //Перевыбор ткани в салоне
            else if($state_order == "Перевыбор ткани в салоне"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);

                    // Важное уведомление
                    $select = mysqli_query($connect, "SELECT `id` FROM `users` WHERE `state` = 'workshop' OR `id` = '$global_executor_id' OR `state` = 'admin'");
                    while ($select_while = mysqli_fetch_assoc($select)) {
                        set_notice_important($connect, $select_while['id'], $id, "Перевыбор ткани в салоне <br> Примечание: {$_POST['note_designer']}", 'change');
                    }

                }
            }
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            //Возврат ткани
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            else if($state_order == "Возврат ткани"){
                // добавляем уведомление
                $executor_id = mysqli_query($connect, "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'");
                $executor_id = mysqli_fetch_assoc($executor_id);
                $executor_id = $executor_id['executor_id'];
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = '$executor_id' OR `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);

                    // Важное уведомление
                    //$select = mysqli_query($connect, "SELECT `id` FROM `users` WHERE `state` = 'workshop' OR `id` = '$global_executor_id' OR `state` = 'admin'");
                    //while ($select_while = mysqli_fetch_assoc($select)) {
                    //    set_notice_important($connect, $select_while['id'], $id, 'Возврат ткани', 'change');
                    //}

                }
            }

            // если заказ завершен
            else if($state_order == "Завершен"){
                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `state` = 'admin'");
                while ($select_while = mysqli_fetch_assoc($select)) {
                    set_notice($connect, $select_while['id'], $search_get);
                }
                $today = date("d.m.Y");
                mysqli_query($connect, "UPDATE `orders_date` SET `data_final_end` = '$today' WHERE `id_order` = '$search_get'");
            }

            // обновляем статус в бд
            mysqli_query($connect, "UPDATE `orders_main_info` SET `pink_state` = '$state_order' WHERE `id_pink_order` = '$search_get'");


            // страница с информацией о изменении статуса (редирект)
            if($_SESSION['state'] == 'workshop' and ($state_order == 'Доставка в салон' or $state_order == 'Доставка клиенту' or $state_order == 'Возврат дизайнеру'))
            {
                ?>
                <div class="find_info">
                    <div class="body_result">
                        <div class="body_result_title">Статус успешно изменен!</div>
                        <a href="order_full_info_new.php?id=<?= $search_get ?>" class="common_back_href">Вернуться</a>
                    </div>
                </div>

                <?php
            }else {
                // если мы не цех
                ?>

                <div class="find_info">
                    <div class="body_result">
                        <div class="body_result_title">Статус успешно изменен!</div>

                        <?php
                        if(($_SESSION['state'] == 'designer' or $_SESSION['state'] == 'admin') and $state_order == "Создание эскизов"){

                            ?>
                            <div>
                                <!--Можно нажать на ссылку и отправить запросы узнать о наличии тканей-->
                                <a href="../send_request_to_supplier_2.php?id=<?= $search_get ?>&init=true" class="common_back_href">Узнать о наличии ткани</a>
                                <br>
                                <br>
                                <br>
                            </div>
                            <?php
                        }
                        ?>
                        <div>
                            <a href="order_full_info_new.php?id=<?= $search_get ?>" class="common_back_href">Вернуться</a>
                        </div>
                    </div>
                </div>

                <?php
            }
        }


    }
    ?>










</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

  
</html>
