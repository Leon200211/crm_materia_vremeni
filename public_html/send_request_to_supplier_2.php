<?php


#================================================================================
# При смене тканей отправить поставщику запрос о наличии тканей

# Но при возврате дизайнеру, и изменению тканей не сработает

# Срабатывает лишь раз
#================================================================================









session_start();




function send_request_to_supplier_2($connect, $search_get)
{

    $array = [];
    $array_2 = [];


    // создаем массив с именами поставщиков ( можно сделать с почтами )
    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$search_get' AND `category` != 'services' AND `category` != 'sewing' AND `category` != 'modification'";
    $select = mysqli_query($connect, $sql);
    while ($select_while = mysqli_fetch_assoc($select)) {
        foreach ($select as $value) {
            if (!(in_array($value['provider'], $array_2)) and $value['provider'] != '') {
                array_push($array_2, $value['provider']);
            }
        }
    }
    // создаем шаблон массива ключ => значение
    for ($i = 0; $i < count($array_2); $i++) {
        $array[$array_2[$i]] = [];
    }

    // заполняем массив ключ => значение
    $sql = "SELECT * FROM `description_of_pink_pages` WHERE `id_pink_order` = '$search_get' AND `category` != 'services' AND `category` != 'sewing' AND `category` != 'modification'";
    $select = mysqli_query($connect, $sql);
    while ($select_while = mysqli_fetch_assoc($select)) {
        foreach ($select as $value) {
            if ($value['provider'] != '') {
                array_push($array[$value['provider']], $value['description'] . "|???|" . $value['quantity'] . "|???|" . $value['size']);
            }
        }
    }




    //проверка на отправленное письмо
    $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$search_get'";
    $select = mysqli_query($connect, $sql);
    $select = mysqli_fetch_assoc($select);
    // массив который будет хранить почты, на которые не было оправлено письмо
    $errors = array();
    // в этом цикле мы будем отправлять письмо
    if ($select['state_of_fabric_order_2'] == 0) {
        foreach ($array as $value) {
            $messeg = "";

            // получение ключа
            $to_1 = key($array);

            if (count($value) > 1) {
                foreach ($value as $v) {
                    // генерируем текст письма
                    $mas_1 = explode("|???|", $v);  // разбиваем на название и количество
                    $messeg = $messeg . "Есть ли продукт " . $mas_1[0] . " цвет: " . $mas_1[2] . " в количестве: " . $mas_1[1] . "\n";
                }
                // отправка письма
                $to = "leon20022018@yandex.ru";
                $headers = "From: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nReply-To: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nX-Mailer: PHP/" . phpversion();
                $subject = "Материя времени";
                if (mail($to, $subject, $messeg, $headers)) {

                    // запись информации об отправки в бд
                    require_once('working_with_db/work_with_mail/writing_to_database.php');
                    write_message_in_db($connect, $search_get, $to, "Запрос информации о наличии", $messeg, '-');


                    if (!mail('leon200207@yandex.ru', $subject, $messeg, $headers)) {
                        array_push($errors, $to_1);
                    }
                } else {
                    array_push($errors, $to_1);
                }
            } else {
                // генерируем текст письма
                $mas_1 = explode("|???|", $value[0]);  // разбиваем на название и количество
                $messeg = $messeg . "Есть ли продукт " . $mas_1[0] . " цвет: " . $mas_1[2] . " в количестве: " . $mas_1[1] . "\n";

                // отправка письма
                $to = "leon20022018@yandex.ru";
                $headers = "From: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nReply-To: materiyavremeni@myb-workflow.ru";
                $headers .= "\r\nX-Mailer: PHP/" . phpversion();
                $subject = "Материя времени";
                if (mail($to, $subject, $messeg, $headers)) {

                    // запись информации об отправки в бд
                    require_once('working_with_db/work_with_mail/writing_to_database.php');
                    write_message_in_db($connect, $search_get, $to, "Запрос информации о наличии", $messeg, '-');



                    if (!mail('leon200207@yandex.ru', $subject, $messeg, $headers)) {
                        array_push($errors, $to_1);
                    }
                } else {
                    array_push($errors, $to_1);
                }
            }

            // шаг к следующему ключу
            next($array);
        }


    }
    // если не было вызвано ошибок, поменять статус
    if(count($errors) == 0){
        $sql = "UPDATE `orders_main_info` SET `state_of_fabric_order_2` = '1' WHERE `id_pink_order` = '$search_get'";
        mysqli_query($connect, $sql);
        header("Location: execution_process/order_full_info_new.php?id=" . $search_get);
    } else {
        $sql = "UPDATE `orders_main_info` SET `state_of_fabric_order_2` = 'error' WHERE `id_pink_order` = '$search_get'";
        mysqli_query($connect, $sql);
    }

}


if($_GET['init'])
{
    require_once 'connect_to_database.php';
    $search_get = $_GET['id'];
    send_request_to_supplier_2($connect, $search_get);

}



?>



