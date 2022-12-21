<?php


#==============================================================
# Обновления плана на определенный месяц
#==============================================================

require_once '../../connect_to_database.php';

if(isset($_POST['id_in_db'])){
    $id_in_db = $_POST['id_in_db'];
    $name = $_POST['name'];
    $data = $_POST['data'];
    $plan = $_POST['plan'];

    $sql = "UPDATE `final_report` SET `plan` = '$plan' WHERE `final_report`.`id` = '$id_in_db';";
    mysqli_query($connect, $sql);
    header("Location: final_report_main_page.php");

} else{
    $name = $_POST['name'];
    $data = $_POST['data'];
    $data = explode("-", $data);
    $month = $data[0];
    $year = $data[1];
    $plan = $_POST['plan'];

    $sql = "INSERT INTO `final_report` (`id`, `year`, `month`, `id_performer`, `plan`) VALUES (NULL, '$year', '$month', '$name', '$plan');";
    mysqli_query($connect, $sql);
    header("Location: final_report_main_page.php");


}




