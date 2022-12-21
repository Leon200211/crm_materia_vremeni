<?php


#=================================================================================
# Живой поиск по БД
#=================================================================================


$mysqli = new mysqli("127.0.0.1:3307", "root", "root", "crm_materia_vremeni");
#$mysqli = new mysqli("localhost", "a0558948_crud", "2u27Ub9h", "a0558948_crud");



$name = $_POST['name'];
$data = $_POST['data'];
$data = explode("-", $data);
$month = $data[0];
$year = $data[1];


$sql_plan = "SELECT * FROM `final_report` WHERE `year` = '$year' and `month` = '$month' and `id_performer` = '$name'";
$select = mysqli_query($mysqli, $sql_plan);
$select = mysqli_fetch_assoc($select);
if($select == NULL){
    echo "
    <input type=\"text\" id=\"plan\" name=\"plan\" class=\"input_style\" style='margin-bottom: 20px;' value='0'>
    <div>
        <input type=\"submit\" name=\"submit\" class='common_button' value=\"Изменить план\"/>
    </div>
    ";
}else {
    echo "
    <input hidden name='id_in_db' value=\"{$select['id']}\">
    <input type=\"text\" id=\"plan\" name=\"plan\" class=\"input_style\" style='margin-bottom: 20px;' value=\"{$select['plan']}\">
    <div>
        <input type=\"submit\" name=\"submit\" class='common_button' value=\"Изменить план\"/>
    </div>
    ";
}


