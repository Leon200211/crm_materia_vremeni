<?php#=================================================================# Исполняемый файл, который проверяет данные из формы (create_pink_order) и добавляет их в бд#=================================================================session_start();if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin' AND $_SESSION['state'] != 'designer' AND $_SESSION['state'] != 'workshop')){    echo "Доступ запрещен";    die;}if(!empty($_POST['customer_fam']) and !empty($_POST['customer_phone']) and !empty($_POST['customer_name']) and !empty($_POST['customer_otch'])){    if(strlen($_POST['customer_fam'])>1 and strlen($_POST['customer_name'])>1 and strlen($_POST['customer_otch'])>1){        $salon = $_POST['salon'];        #$id_order_create = $_POST['id_order_create'];        require_once '../connect_to_database.php';//        $sql = "SELECT * FROM `orders_main_info` WHERE `id_pink_order` = '$id_order_create'";//        $id_order = mysqli_query($connect, $sql);//        $id_order = mysqli_fetch_assoc($id_order);        $sql = "SELECT * FROM `orders_main_info` ORDER BY id DESC LIMIT 1";        $id_order = mysqli_query($connect, $sql);        $id_order = mysqli_fetch_assoc($id_order);        # проверка на номер заказа        if(!empty($id_order['id_pink_order'])){            $id_order_create = $id_order['id_pink_order'] += 1;        } else {            $id_order_create = 1;        }        $customer_fam = $_POST['customer_fam'];        $customer_name = $_POST['customer_name'];        $customer_otch = $_POST['customer_otch'];        $customet_full_name = $customer_fam . " " . $customer_name . " " . $customer_otch;        $customer_phone = $_POST['customer_phone'];        if(!empty($_POST['email'])){            $email = $_POST['email'];        } else {            $email = "";        }        $address_additional = $_POST['address_additional'];        $sql_2 = "SELECT `phone` FROM `salons` WHERE `name` = '$salon'";        $executor_info = mysqli_query($connect, $sql_2);        $executor_info = mysqli_fetch_assoc($executor_info);        $executor_id = $_SESSION['id_user'];        // дата создания        $today = date("Y-m-d");        // дата завершения для дизайнера        $designer_date = date("Y-m-d");        $designer_date = date_create($designer_date);        date_add($designer_date, date_interval_create_from_date_string("14 day"));  // прибавляем 2 недели ( по умолчанию)        $designer_date = date_format($designer_date,"Y-m-d");        $sql = "INSERT INTO `orders_main_info` (`id`, `id_pink_order`, `customer_name`, `customer_phone`, `email`, `address_additional`, `executor_id`, `salon`, `pink_image`, `pink_state`, `total_cost`, `prepayment`, `surcharge`, `state_of_fabric_order`, `state_of_fabric_order_2`, `state_of_fabric_order_3`) VALUES (NULL, '$id_order_create', '$customet_full_name', '$customer_phone', '$email', '$address_additional', '$executor_id', '$salon', '', 'Создание роз. стр.', '0', '0', '0', '0', '0', '0')";        $sql_date = "INSERT INTO `orders_date` (`id`, `id_order`, `date_create`, `date_end`, `data_final_end`, `date_end_designer`, `pink_page_arrival`, `sketches_arrival`, `date_entered_workshop`, `date_delivery_cutter`, `date_start_work`, `departure_date`, `date_note`) VALUES (NULL, '$id_order_create', '$today', '', '', '$designer_date', '', '', '', '', '', '', '')";        $sql_workshop = "INSERT INTO `turnover_table` (`id`, `id_order`, `workshop_cost_sewing`, `workshop_cost`, `performer`, `note`, `courier`) VALUES (NULL, '$id_order_create', 0, 0, '', '', '')";        if(mysqli_query($connect, $sql) and mysqli_query($connect, $sql_date) and mysqli_query($connect, $sql_workshop)){            // отправка сообщения на почту пользователю            if($email != ""){                //$to = "leon20022018@yandex.ru";                $to = $email;                $Name = $customet_full_name . ", ваш заказ №" . $id_order_create . " принят в работу";                $Disc = "Для обратной связи воспользуйтесь номером 7 777 777 77 77 \n или отправьте письмо на почту materia-vremeri@mail.ru";                $subject = "Материя времени";                $mes = $Name . "\n" . $Disc;                $headers = "From: materiyavremeni@myb-workflow.ru";                $headers .= "\r\nReply-To: materiyavremeni@myb-workflow.ru";                $headers .= "\r\nX-Mailer: PHP/".phpversion();                if(mail($to, $subject, $mes, $headers)) {                    // запись информации об отправки в бд                    require_once('../working_with_db/work_with_mail/writing_to_database.php');                    write_message_in_db($connect, $id_order_create, $to, "Уведомление о создании заказа", $mes, '-');                    // добавляем уведомление                    $my_id = $_SESSION['id_user'];                    $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `state` = 'admin' OR `id` = '$my_id'");                    while ($select_while = mysqli_fetch_assoc($select)) {                        $id_user = $select_while['id'];                        $prov_notice = mysqli_query($connect, "SELECT * FROM `notice` WHERE `id_user` = '$id_user' and `id_order` = '$id_order_create'");                        if(empty(mysqli_fetch_assoc($prov_notice)['id_user'])){ // если еще нет уведомлений по этому заказу                            mysqli_query($connect, "INSERT INTO `notice` (`id`, `id_user`, `id_order`) VALUES (NULL, '$id_user', '$id_order_create')");                        }                    }                    $location_addres = "../working_with_orders/pink_all_elements_of_the_mutable.php?id_pink_order=$id_order_create";                    header("Location: " . $location_addres);                } else {                    ?>                    <!DOCTYPE html>                    <html>                <head>                    <meta name="viewport" content="width=device-width, initial-scale=1">                    <meta charset="utf-8">                    <link rel="stylesheet" href="../assets/css/style.css">                    <link rel="stylesheet" href="../assets/css/style_messag.css">                    <link rel="stylesheet" href="../assets/css/full_info_css.css">                    <title>Pink</title>                    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>                    <script src="assets/script/app.js" defer></script>                </head>                <body>                <div class="find_info">                    <div class="info_about_update_state">                        <div class="search_info_error">При отправки сообщения на почту возникла ошибка</div>                        <a href="../working_with_orders/pink_all_elements_of_the_mutable.php?id_pink_order=<?= $id_order_create ?>" class="btn_back_to_msg">Вернуться</a>                    </div>                </div>                </body>                    <?php                }            }            else {  // вместо этого будет отправка на телефон                // добавляем уведомление                $select = mysqli_query($connect, "SELECT * FROM `users` WHERE `state` = 'admin'");                while ($select_while = mysqli_fetch_assoc($select)) {                    $id_user = $select_while['id'];                    $prov_notice = mysqli_query($connect, "SELECT * FROM `notice` WHERE `id_user` = '$id_user' and `id_order` = '$id_order_create'");                    if(empty(mysqli_fetch_assoc($prov_notice)['id_user'])){ // если еще нет уведомлений по этому заказу                        mysqli_query($connect, "INSERT INTO `notice` (`id`, `id_user`, `id_order`) VALUES (NULL, '$id_user', '$id_order_create')");                    }                }                $location_addres = "../working_with_orders/pink_all_elements_of_the_mutable.php?id_pink_order=$id_order_create";                header("Location: " . $location_addres);            }//            if(!empty($customer_phone)){//                отправка смс на телефон//            }        }else{            header("Location: create_pink_order.php?error=" . "Ошибка");        }    }else{        //header("Location: create_pink_order.php?error=" . "Фио неверно");        ?>        <!DOCTYPE html>        <html>        <head>            <meta name="viewport" content="width=device-width, initial-scale=1">            <meta charset="utf-8">            <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">            <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">            <title>Pink</title>            <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>            <script src="assets/script/app.js" defer></script>        </head>        <body>        <div class="find_info">            <div class="info_about_update_state">                <form action="create_pink_order.php" method="post">                    <div class="find_info">                        <div class="body_result">                            <input hidden name="error" value="Фио заполнено неверно">                            <input hidden name="salon" value="<?=@$_POST['salon']?>">                            <input hidden name="customer_fam" value="<?=@$_POST['customer_fam']?>">                            <input hidden name="customer_name" value="<?=@$_POST['customer_name']?>">                            <input hidden name="customer_otch" value="<?=@$_POST['customer_otch']?>">                            <input hidden name="customer_phone" value="<?=@$_POST['customer_phone']?>">                            <input hidden name="email" value="<?=@$_POST['email']?>">                            <input hidden name="address_additional" value="<?=@$_POST['address_additional']?>">                            <div class="body_result_title" style="color: red;">Фио заполнено неверно</div>                            <button type="submit" class="common_button">Исправить</button>                        </div>                    </div>                </form>            </div>        </div>        </body>        <?php    }}else{    //header("Location: create_pink_order.php?error=" . "Заполните все обязательные поля");    ?>    <!DOCTYPE html>    <html>    <head>        <meta name="viewport" content="width=device-width, initial-scale=1">        <meta charset="utf-8">        <link rel="stylesheet" href="../assets/css/common_styles/style_result.css">        <link rel="stylesheet" href="../assets/css/common_styles/common_style.css">        <title>Pink</title>        <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>        <script src="assets/script/app.js" defer></script>    </head>    <body>    <div class="find_info">        <div class="info_about_update_state">            <form action="create_pink_order.php" method="post">                <div class="find_info">                    <div class="body_result">                        <input hidden name="error" value="Фио заполнено неверно">                        <input hidden name="salon" value="<?=@$_POST['salon']?>">                        <input hidden name="customer_fam" value="<?=@$_POST['customer_fam']?>">                        <input hidden name="customer_name" value="<?=@$_POST['customer_name']?>">                        <input hidden name="customer_otch" value="<?=@$_POST['customer_otch']?>">                        <input hidden name="customer_phone" value="<?=@$_POST['customer_phone']?>">                        <input hidden name="email" value="<?=@$_POST['email']?>">                        <input hidden name="address_additional" value="<?=@$_POST['address_additional']?>">                        <div class="body_result_title" style="color: red;">Заполните все обязательные поля</div>                        <button type="submit" class="common_button">Исправить</button>                    </div>                </div>            </form>        </div>    </div>    </body>    <?php}?>