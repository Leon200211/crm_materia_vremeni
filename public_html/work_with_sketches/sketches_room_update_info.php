<?php

#=============================================================
# Добавляем/изменяем Примечание и 'лицевой стороной считайть' у страницы в эскизе
#=============================================================




session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once "../connect_to_database.php";



$id = $_POST['id'];
$id_order = $_POST['id_order'];
$room = $_POST['room'];
$page = $_POST['page'];
$specification = $_POST['specification'];
$id_in_sketches = $_POST['id_in_sketches'];
$note = $_POST['note'];
$font_side = $_POST['font_side'];


//  -------------------------------
//  для проверки на создание заказа
$sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_order'";
$select = mysqli_query($connect, $sql);
$select = mysqli_fetch_assoc($select);
if($select['executor_id'] != $_SESSION['id_user'] and $_SESSION['state'] != 'admin') {
    echo "Доступ запрещен";
    die;
}
// --------------------------------------



if(strlen($note) > 500 or strlen($font_side) > 500){
    header("Location: sketches_room_update.php?id_in_sketches=" . $id_in_sketches . "&state=Слишком много текста");
}else {

    // разделяем строку на несколько строк по 50 символов мксимум
    // без разрыва слов
    // это нужно чтобы в pdf все выводилось красиво
    $new_note = "";
    $len_text = strlen($note);
    $flag = 0;
    if ($len_text > 80) {
        $i = 0;
        while ($i < $len_text) {
            $b = 80;
            if ($i + 80 < strlen($note)) {
                while ($note[$i + $b] != " ") {
                    $b -= 1;
                    if ($b < 0) {
                        $flag = 1;
                        break;
                    }
                }
            }
            if ($flag == 0) {
                if ($i - (80 - $b) < 0) {
                    $new_note = $new_note . substr($note, $i, $b) . "\r\n";
                    $i = $b;
                } else {
                    $new_note = $new_note . substr($note, $i, $b) . "\r\n";
                    $i += $b;
                }
            } else {
                $new_note = $new_note . substr($note, $i, 80) . "\r\n";
                $flag = 0;
                $i += 80;
            }
        }
    } else {
        $new_note = $note;
    }
    // разделяем строку на несколько строк по 50 символов мксимум
    // без разрыва слов
    // это нужно чтобы в pdf все выводилось красиво
    $new_font_side = "";
    $len_text = strlen($font_side);

    $flag = 0;
    if ($len_text > 80) {
        $i = 0;
        while ($i < $len_text) {
            $b = 80;
            if ($i + 80 < strlen($font_side)) {
                while ($font_side[$i + $b] != " ") {
                    $b -= 1;
                    if ($b < 0) {
                        $flag = 1;
                        break;
                    }
                }
            }
            if ($flag == 0) {
                if ($i - (80 - $b) < 0) {
                    $new_font_side = $new_font_side . substr($font_side, $i, $b) . "\r\n";
                    $i = $b;
                } else {
                    $new_font_side = $new_font_side . substr($font_side, $i, $b) . "\r\n";
                    $i += $b;
                }
            } else {
                $new_font_side = $new_font_side . substr($font_side, $i, 80) . "\r\n";
                $flag = 0;
                $i += 80;
            }
        }
    } else {
        $new_font_side = $font_side;
    }


    mysqli_query($connect, "UPDATE `sketches_main` SET `note` = '$new_note', `font_side` = '$new_font_side' WHERE `id` = '$id'");


    header("Location: sketches_room_update.php?id_in_sketches=" . $id_in_sketches . "&state=Обновлено!");
}