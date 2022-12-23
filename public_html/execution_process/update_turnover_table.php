<?php


#===========================================================
#= Исполняемый файл для изменения turnover_table в бд
#===========================================================



session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'workshop')){
    echo "Доступ запрещен";
    die;
}



require_once("../connect_to_database.php");

$id_order = $_POST['id_order'];
$type = $_POST['type'];



if($type == 1){
    $workshop_cost_sewing = $_POST['workshop_cost_sewing'];
    $workshop_cost = $_POST['workshop_cost'];
    $performer = $_POST['performer'];

    $sql = "UPDATE `turnover_table` SET `workshop_cost_sewing` = '$workshop_cost_sewing', `workshop_cost` = '$workshop_cost', `performer` = '$performer' WHERE `turnover_table`.`id_order` = '$id_order';";
    mysqli_query($connect, $sql);

}else if($type == 2){
    $note = $_POST['note'];

    $sql = "UPDATE `turnover_table` SET `note` = '$note' WHERE `turnover_table`.`id_order` = '$id_order';";
    mysqli_query($connect, $sql);


    // дата создания
    $today = date("d.m.Y");
    $designer_date = date("d.m.Y");
    $designer_date = date_create($designer_date);
    $designer_date = date_format($designer_date,"d.m.Y");

    $sql = "UPDATE `orders_date` SET `date_note` = '$designer_date' WHERE `orders_date`.`id_order` = '$id_order';";
    mysqli_query($connect, $sql);
}

header("Location: order_full_info_new.php?id=$id_order");


