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