<?php


#=========================================
# удаление пункта из комнаты
#=========================================




session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once "../connect_to_database.php";


// достаем инфу из sketches_main по ключу его id
$id_in_sketches = $_GET['id_in_sketches'];
$sql_sketches_main = "SELECT * FROM `sketches_main` WHERE `id` = '$id_in_sketches'";
$sketches_main = mysqli_query($connect, $sql_sketches_main);
$sketches_main = mysqli_fetch_assoc($sketches_main);



$id_order = $sketches_main['id_order'];
$room = $sketches_main['room'];
$page = $sketches_main['page'];
$specification = $sketches_main['specification'];



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





if($specification == 'портьеры|тюли|подхваты|тп') {
    $from = 'sketches_1';
} else if ($specification == 'римские|франц|австрийск|тп') {
    $from = 'sketches_2';
}else if ($specification == 'покрывала') {
    $from = 'sketches_3';
}else if ($specification == 'подушки|наволочки|валики') {
    $from = 'sketches_4';
}else if ($specification == 'сваги|джаботы|ламбрикены') {
    $from = 'sketches_5';
}else if ($specification == 'скатерти|салфетки') {
    $from = 'sketches_6';
}


if(@fopen("../assets/img/img_" . $id_order . '_' . $room . '_' . $page . '_' . $id_in_sketches . ".png", "r")) {
    unlink("../assets/img/img_" . $id_order . '_' . $room . '_' . $page . '_' . $id_in_sketches . ".png");  // удаляет файл
}


mysqli_query($connect, "UPDATE `sketches_main` SET `page` = `page`-1 WHERE `id_order` = '$id_order' AND `page` > '$page' AND `room` = '$room'");
mysqli_query($connect, "DELETE FROM `sketches_main` WHERE `sketches_main`.`id` = '$id_in_sketches'");
mysqli_query($connect, "DELETE FROM `$from` WHERE  `id_sketches_main` = '$id_in_sketches'");



header('Location:sketches_room.php?id_pink_order=' . $id_order);