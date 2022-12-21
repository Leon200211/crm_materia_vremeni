<?php


#======================================================================
# Отмечаем ткань на замену для дизайнера
#======================================================================




session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';

$id_in_db = $_POST['id_in_db'];
$id_order = $_POST['id'];

if(!empty($id_in_db)) {
    mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `additional_info` = 'Замена' WHERE `description_of_pink_pages`.`id` = '$id_in_db'");
    header("Location: order_full_info_marriage.php?id=$id_order");
}
else{
    header("Location: order_full_info_marriage.php?id=$id_order");
}