<?php


#===========================================================================================
# Здесь содержаться различные функции, которые используются в разных местах системы
#===========================================================================================





function calculate_the_cost_of_sewing($connect, $id_order){

    $sum = 0;

    $sql = "SELECT `quantity`, `price` FROM `description_of_pink_pages` WHERE `id_pink_order` = '$id_order' AND (`category` = 'sewing' OR `category` = 'modification')";
    $select = mysqli_query($connect, $sql);
    while ($select_while = mysqli_fetch_assoc($select)) {
        $sum += $select_while['price'] * $select_while['quantity'];
    }

    return $sum;

}


function show_normal_price(){

}


function set_notice($connect, $id_user, $id_order){

    // проверяем, существует ли уведомление
    $prov_notice = mysqli_query($connect, "SELECT * FROM `notice` WHERE `id_user` = '$id_user' and `id_order` = '$id_order'");

    if(empty(mysqli_fetch_assoc($prov_notice)['id_user'])){ // если еще нет уведомлений по этому заказу
        mysqli_query($connect, "INSERT INTO `notice` (`id`, `id_user`, `id_order`) VALUES (NULL, '$id_user', '$id_order')");
    }

}


function get_designer_name($connect, $id_designer){

    $sql = "SELECT `name` FROM `users` WHERE `id` = '$id_designer'";
    $id_designer = mysqli_query($connect, $sql);
    $id_designer = mysqli_fetch_assoc($id_designer);

    return $id_designer['name'];

}

function get_performer_name($connect, $id_performer){

    $sql_performer = "SELECT `name` FROM `performers` WHERE `id` = '$id_performer'";
    $id_performer = mysqli_query($connect, $sql_performer);
    $id_performer = mysqli_fetch_assoc($id_performer);

    if($id_performer){
        return $id_performer['name'];
    }else{
        return '-';
    }

}
