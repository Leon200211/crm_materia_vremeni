<?php


#================================================================================
# Исполняемый файл по изменению статуса определенного пункта в заказе
# Конкретней убрать примечание к заказу
#================================================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}

require_once '../connect_to_database.php';



$id = $_POST['id'];
$id_order = $_POST['id_order'];


if(isset($_POST['type_workshop'])){
    if(!empty($id) and !empty($id_order)) {
        mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `additional_info` = 'Брак (цех)' WHERE `description_of_pink_pages`.`id` = '$id'");
        header("Location: order_full_info_marriage.php?id=$id_order");

    }
    else{
        header("Location: order_full_info_marriage.php?id=$id_order");
    }
}else{
    if(!empty($id) and !empty($id_order)) {
        mysqli_query($connect, "UPDATE `description_of_pink_pages` SET `additional_info` = '' WHERE `description_of_pink_pages`.`id` = '$id'");
        header("Location: order_full_info_marriage.php?id=$id_order");

    }
    else{
        header("Location: order_full_info_marriage.php?id=$id_order");
    }
}
