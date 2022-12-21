<?php

#===============================================================
# Исполняемый файл для удаления строчки из определенной страницы в эскизах
#===============================================================


session_start();


if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}


require_once '../connect_to_database.php';




$id = $_GET['id'];
$specification = $_GET['specification'];




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


// достаем инфу из sketches_main по ключу его id
$sql_sketches_main = "SELECT `id_sketches_main`, `id_paragraph` FROM `$from` WHERE `id` = '$id'";
$sketches_main = mysqli_query($connect, $sql_sketches_main);
$sketches_main = mysqli_fetch_assoc($sketches_main);

$id_sketches_main = $sketches_main['id_sketches_main'];
$id_paragraph = $sketches_main['id_paragraph'];



mysqli_query($connect, "UPDATE `$from` SET `id_paragraph` = `id_paragraph`-1 WHERE `id_sketches_main` = '$id_sketches_main' AND `id_paragraph` > '$id_paragraph'");
mysqli_query($connect, "DELETE FROM `$from` WHERE `$from`.`id` = '$id'");
header("Location: sketches_room_update.php?id_in_sketches=" . $id_sketches_main);
