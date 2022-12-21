<?php


#================================================================================
# Исполняемый файл по изменению статуса определенного пункта в заказе
# Конкретней убрать примечание к заказу
#================================================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';



$id = $_POST['id'];
$id_order = $_POST['id_order'];


if(!empty($id) and !empty($id_order)) {
    mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `additional_info` = '' WHERE `description_of_pink_pages`.`id` = '$id'");
    header("Location: order_full_info_marriage.php?id=$id_order");

}
else{
    header("Location: order_full_info_marriage.php?id=$id_order");
}