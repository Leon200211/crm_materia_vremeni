<?php



#==============================================================
# Страница для отображения фин отчета и обновления планов на месяц
#==============================================================





session_start();

if(empty($_SESSION['user']) OR ($_SESSION['state'] != 'admin')){
    echo "Доступ запрещен";
    die;
}


// для подсчета общей стоимости пошива заказа
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/global_functions.php';


// функция для вывода всех месяцев между двумя датами
function DiffDate($date1, $date2) {
    // Первая запись разделит даты начала и окончания.
    $startDate = explode('-', $date1);
    $endDate = explode('-', $date2);

    $startYear = $startDate[0];
    $endYear = $endDate[0];

    $startMonth = $startDate[1];
    $endMonth = $endDate[1];

    $YmArr = [];
    // Тот же год и новый год обрабатываются отдельно.
    if($startYear != $endYear){
        // Узнаем все месяцы нового года
        for ($year=$startYear; $year < $endYear; $year++) {
            for ($month=$startMonth; $month <= 12; $month++) {
                $Ym = $year . '-' . $month;// Дата склейки, склейка в соответствии с нужным вам форматом.
                $YmArr[] = $Ym;

            }
            $startMonth = 1; // Сбрасываем месяц в канун Нового года

        }
        // Находим все месяцы с этого года по настоящий
        for ($nowMonth=1; $nowMonth <= $endMonth; $nowMonth++) {
            $nowYm = $endYear . '-' . $nowMonth;
            $YmArr[] = $nowYm;
        }
    }else{
        for ($nowMonth=$startMonth; $nowMonth <= $endMonth; $nowMonth++) {
            $nowYm = $endYear . '-' . $nowMonth;
            $YmArr[] = $nowYm;
        }
    }
    return $YmArr;
}




// функция по переводу номера месяца в число
function getRusMonthName($n)
{
    $rusMonthNames = [
        1 => 'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
    ];

    return $rusMonthNames[$n];
}

require_once '../../connect_to_database.php';



?>





<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">





    <link rel="stylesheet" href="../../assets/css/style_for_final_report/style_for_final_report.css">
    <link rel="stylesheet" href="../../assets/css/common_styles/common_style.css">
    <link rel="stylesheet" href="../../assets/css/common_styles/common_life_search.css">
    <link rel="stylesheet" href="../../assets/css/style_pink_page/style_for_create_order.css">
    <link rel="stylesheet" href="../../assets/css/style_for_work_with_db/common_style_for_work_with_db.css">
    <link rel="stylesheet" href="../../assets/css/common_styles/table_room_style.css">



    <title>Пример веб-страницы</title>
    <script src="https://kit.fontawesome.com/58ebeca16e.js" crossorigin="anonymous"></script>
    <script src="../../assets/script/paint.js" defer></script>
    <script src="../../assets/script/app.js" defer></script>
</head>

<body style="background-color: #F3F3F3">
<header class="header">
    <?php
    include('../../header.php');
    ?>
</header>


<div class="common_div_body">
    <?php
    if(($_SESSION['state'] == 'admin' OR $_SESSION['state'] == 'designer' OR $_SESSION['state'] == 'workshop') AND !empty($_SESSION['user'])) {
        include('../header_for_db.php');
    }
    ?>


<div class="db_main_body">



    <div class="container_for_info" style="margin-top: 40px;">


        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#show_bar").click(function() {
                    $("#bar_block").slideToggle();
                    if ($("#show_bar").html() == 'Отмена') {
                        $("#show_bar").html('Настроить план');
                    } else {
                        $("#show_bar").html('Отмена');
                    }
                });
            });
        </script>
        <style>
            #bar_block {display: none;}
        </style>

        <button id="show_bar" class="common_button">Настроить план</button>


        <?php
        if (isset($_GET['vibor'])) {
            $vibor = $_GET['vibor'];
            ?>
            <script type="text/javascript">
                $("#show_bar").html('Отмена');
            </script>
            <style>
                #bar_block {display: block;}
            </style>
            <?php
        }else {
            $vibor = "";
        }
        ?>

        <div id="bar_block" class="add_plan_main">
            <form action="final_report_update_main.php" method="post">

                <div>
                    <script type="text/javascript">

                        var name = 0;
                        var data = 0;

                        $(function() {
                            $('#name').change(function () {
                                name = $(this).find(':selected').val();
                                console.log(name);
                                if(name != 0 && data != 0){
                                    $.ajax({
                                        url: "life_search_for_plan.php",
                                        type: "POST",
                                        data: {
                                            name: name,
                                            data: data
                                        },
                                        success: function(result){
                                            $("#plan").html(result);
                                        }});
                                }else{
                                    document.getElementById('plan').innerHTML = '';
                                }
                            });


                            $('#data').change(function () {
                                data = $(this).find(':selected').val();

                                if(name != 0 && data != 0){
                                    $.ajax({
                                        url: "life_search_for_plan.php",
                                        type: "POST",
                                        data: {
                                            name: name,
                                            data: data
                                        },
                                        success: function(result){
                                            $("#plan").html(result);
                                        }});
                                }else{
                                    document.getElementById('plan').innerHTML = '';
                                }
                            });

                        });

                    </script>


                    <div>
                        Сотрудник
                        <select class="plan_data" name="name" id="name">
                            <option></option>
                            <?php
                            $sql_for_name = "SELECT * FROM `performers` WHERE `type` != 'partner'";
                            $select_for_name = mysqli_query($connect, $sql_for_name);
                            while ($select_for_name_while = mysqli_fetch_assoc($select_for_name)){
                                ?>
                                <option value="<?=$select_for_name_while['id']?>"><?=$select_for_name_while['name']?></option>
                            <?php
                            }
                            ?>
                            <option value="-1">Партнеры</option>
                        </select>
                    </div>
                    <div>
                        Месяц
                        <select class="plan_data" name="data" id="data">
                            <option></option>
                            <?php
                            $start    = (new DateTime('2022-10-01'))->modify('first day of this month');
                            $end      = (new DateTime('2025-01-01'))->modify('first day of next month');
                            $interval = DateInterval::createFromDateString('1 month');
                            $period   = new DatePeriod($start, $interval, $end);

                            foreach ($period as $dt) {
                                ?>
                                <option><?=$dt->format("m-Y")?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>


                    <div class="test_life">
                        <form action="final_report_update_main.php">
                            <div id="plan">
                            </div>
                        </form>
                    </div>

                </div>





            </form>
        </div>



    </div>



    <div class="container_for_info" style="margin-top: 40px;">



        <script type="text/javascript">
            $(document).ready(function() {
                $("#show_report").click(function() {
                    $("#report_block").slideToggle();
                    if ($("#show_report").html() == 'Отмена') {
                        $("#show_report").html('Выбор месяца');
                    } else {
                        $("#show_report").html('Отмена');
                    }
                });
            });
        </script>
        <style>
            #report_block {display: none;}
        </style>

        <button id="show_report" class="common_button">Выбор месяца</button>



        <script type="text/javascript">
            $(document).ready(function() {
                $("#show_report_2").click(function() {
                    $("#report_block_2").slideToggle();
                    if ($("#show_report_2").html() == 'Отмена') {
                        $("#show_report_2").html('Выбор периода');
                    } else {
                        $("#show_report_2").html('Отмена');
                    }
                });
            });
        </script>
        <style>
            #report_block_2 {display: none;}
        </style>

        <button id="show_report_2" class="common_button">Выбор периода</button>



        <div id="report_block" class="add_plan_main">
            <form action="final_report_main_page.php" method="post">
                <div>
                    Месяц
                    <select class="plan_data" name="data" id="data">
                        <?php
                        $start    = (new DateTime('2022-10-01'))->modify('first day of this month');
                        $end      = (new DateTime('2025-01-01'))->modify('first day of next month');
                        $interval = DateInterval::createFromDateString('1 month');
                        $period   = new DatePeriod($start, $interval, $end);

                        foreach ($period as $dt) {
                            ?>
                            <option value="<?=$dt->format("m-Y")?>"><?=$dt->format("m-Y")?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <input class="plan" hidden type="text" name="type" value="1">
                <div>
                    <input type="submit" name="submit" class="common_button" value="Показать Фин. отчет" />
                </div>
            </form>
        </div>

        <div id="report_block_2" class="add_plan_main">
            <form action="final_report_main_page.php" method="post">
                <div>
                    <?php
                    $sql_final_report = "SELECT `id`, `year`, `month` FROM `final_report`";
                    $select_final_report = mysqli_query($connect, $sql_final_report);
                    ?>
                    <div>
                        С
                        <select class="plan_data" name="data" id="data">
                            <?php
                            $start    = (new DateTime('2022-10-01'))->modify('first day of this month');
                            $end      = (new DateTime('2025-01-01'))->modify('first day of next month');
                            $interval = DateInterval::createFromDateString('1 month');
                            $period   = new DatePeriod($start, $interval, $end);

                            foreach ($period as $dt) {
                                ?>
                                <option value="<?=$dt->format("Y-m")?>"><?=$dt->format("m-Y")?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        По
                        <select class="plan_data" name="data_2" id="data_2">
                            <?php
                            $start    = (new DateTime('2022-10-01'))->modify('first day of this month');
                            $end      = (new DateTime('2025-01-01'))->modify('first day of next month');
                            $interval = DateInterval::createFromDateString('1 month');
                            $period   = new DatePeriod($start, $interval, $end);

                            foreach ($period as $dt) {
                                ?>
                                <option value="<?=$dt->format("Y-m")?>"><?=$dt->format("m-Y")?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <input class="plan" hidden type="text" name="type" value="2">
                <div>
                    <input type="submit" name="submit" class="common_button" value="Показать Фин. отчет" />
                </div>
            </form>
        </div>






        <?php

        if(@!empty($_POST['type'])) {
            if($_POST['type'] == 1) {

                $iteration = 1;

                $data_report[] = $_POST['data'];
                $data_for_1 = explode('-', $_POST['data']);
                $month[] = intval($data_for_1[0]);
                $year[] = $data_for_1[1];


                $arr_for_plan = [];


                $sql_final_report = "SELECT * FROM `final_report` WHERE `month` = '$data_for_1[0]' and `year` = '$data_for_1[1]'";
                $select_final_report = mysqli_query($connect, $sql_final_report);
                while($select_final_report_while = mysqli_fetch_assoc($select_final_report)){
                    $arr_for_plan[0][$select_final_report_while['id_performer']] = ['plan' => [$select_final_report_while['plan']]];
                    $arr_for_plan[0][$select_final_report_while['id_performer']] += ['salon' => []];
                    $arr_for_plan[0][$select_final_report_while['id_performer']] += ['workshop' => []];
                }


                // необходимо для проверки в orders_date
                if ($month[0] < 10) {
                    $new_month[] = "0" . $month[0];
                } else {
                    $new_month[] = $month[0];
                }
                $new_date[0] = $year[0] . "-" . $new_month[0];
                $date_for_sql[] = $new_date[0];


            }else if($_POST['type'] == 2){

                $data_start = $_POST['data'];
                $data_end = $_POST['data_2'];



                $start    = (new DateTime($data_start . '-01'))->modify('first day of this month');
                $end      = (new DateTime($data_end . '-01'))->modify('first day of next month');
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($start, $interval, $end);


                $iter = 0;
                $arr_for_plan = [];
                foreach ($period as $dt) {

                    $data_for_1 = explode('-', $dt->format("m-Y"));



                    $month[] = intval($data_for_1[0]);
                    $year[] = $data_for_1[1];



                    $sql_final_report = "SELECT * FROM `final_report` WHERE `month` = '$month[$iter]' and `year` = '$year[$iter]'";
                    $select_final_report = mysqli_query($connect, $sql_final_report);
                    while($select_final_report_while = mysqli_fetch_assoc($select_final_report)){
                        $arr_for_plan[$iter][$select_final_report_while['id_performer']] = ['plan' => [$select_final_report_while['plan']]];
                        $arr_for_plan[$iter][$select_final_report_while['id_performer']] += ['salon' => []];
                        $arr_for_plan[$iter][$select_final_report_while['id_performer']] += ['workshop' => []];
                    }



                    // необходимо для проверки в orders_date
                    if ($month[$iter] < 10) {
                        $new_month[$iter] = "0" . $month[$iter];
                    } else {
                        $new_month[$iter] = $month[$iter];
                    }
                    $date_for_sql[$iter] = $year[$iter] . "-" . $new_month[$iter];

                    $iter++;
                }



                $iteration = count($month);



            }



            $global_salon = 0;
            $global_workshop = 0;


            for($i = 0; $i < $iteration; $i++){
                // достаем информацию об исполнителях
                $sql_for_all_performers = "SELECT * FROM `performers` WHERE `type` != 'partner'";
                $select_for_all_performers = mysqli_query($connect, $sql_for_all_performers);


                $sum_total_cost = 0;
                ?>


                <h3>Финансовый отчет</h3>
                <h4>Месяц - <?=getRusMonthName($month[$i])?> | Год - <?=$year[$i]?></h4>
                <table>

                    <!--            шапка таблицы-->
                    <tr>
                        <th rowspan="3">№ Заказа</th>
                        <th rowspan="3">Принят<br>в салон</th>
                        <th rowspan="3">Стоимость<br>пошива салон</th>
                        <th colspan="<?=($select_for_all_performers->num_rows * 2) + 2?>">Исполнители</th>
                        <th rowspan="3">Готов</th>
                    </tr>

                    <tr>
                        <?php
                        $performers_id = []; // хранит все id исполнителей кроме партнеров
                        while($select_for_all_performers_while = mysqli_fetch_assoc($select_for_all_performers)){
                            $performers_id[] = $select_for_all_performers_while['id'];
                            ?>
                            <td colspan="2" style="text-align: center"><?=$select_for_all_performers_while['name']?></td>
                                <?php
                        }
                        ?>

                        <td colspan="2" style="text-align: center">Партнеры</td>
                    </tr>
                    <tr>
                        <?php
                        for($num_c = 0; $num_c < ($select_for_all_performers->num_rows); $num_c++){
                            ?>
                            <td class="first">Салон</td>
                            <td class="first">Дилеры</td>
                                <?php
                        }
                        ?>
                        <td class="first">Салон</td>
                        <td class="first">Дилеры</td>
                    </tr>


                    <!--            Основная информация-->
                    <?php
                    $sql_for_table = "SELECT * FROM `orders_main_info` INNER JOIN `orders_date` ON `orders_main_info`.`id_pink_order` = `orders_date`.`id_order` INNER JOIN `turnover_table` ON `orders_date`.`id_order` = `turnover_table`.`id_order` WHERE `orders_main_info`.`pink_state` = 'Завершен' AND `orders_date`.`date_create` LIKE '$date_for_sql[$i]%';";
                    $select_for_table = mysqli_query($connect, $sql_for_table);


                    // общая сумма за месяц
                    for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++) {
                        $arr_for_plan[$i][$performers_id[$num_per_id]]['salon_sum'] = 0;
                        $arr_for_plan[$i][$performers_id[$num_per_id]]['workshop_sum'] = 0;
                    }
                    $arr_for_plan[$i][-1]['salon_sum'] = 0;
                    $arr_for_plan[$i][-1]['workshop_sum'] = 0;




                    while($select_for_table_while = mysqli_fetch_assoc($select_for_table)){

                        $sum_total_cost += calculate_the_cost_of_sewing($connect, $select_for_table_while['id_pink_order']);
                        ?>
                        <tr>
                            <td><?=$select_for_table_while['id_order']?></td>
                            <td><?=$select_for_table_while['date_create']?></td>
                            <td><?=calculate_the_cost_of_sewing($connect, $select_for_table_while['id_pink_order'])?></td>
                            <?php
                            $executor_id = $select_for_table_while['executor_id'];
                            $sql_for_executor = "SELECT `state` FROM `users` WHERE `id` = '$executor_id'";
                            $select_for_executor = mysqli_query($connect, $sql_for_executor);
                            $select_for_executor = mysqli_fetch_assoc($select_for_executor);
                            $state = $select_for_executor['state'];






                            for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++){
                                // для закройщиков
                                $arr_for_plan[$i][$performers_id[$num_per_id]]['salon'][$select_for_table_while['id_order']] = -1;
                                $arr_for_plan[$i][$performers_id[$num_per_id]]['workshop'][$select_for_table_while['id_order']] = -1;

                                if($select_for_table_while['performer'] != "" and in_array($select_for_table_while['performer'], $performers_id)){
                                    if($performers_id[$num_per_id] == $select_for_table_while['performer']){
                                        //$select_for_table_while['id_order']
                                        if($state == 'designer' or $state == 'admin'){
                                            $arr_for_plan[$i][$select_for_table_while['performer']]['salon'][$select_for_table_while['id_order']] = $select_for_table_while['workshop_cost_sewing'];
                                            $arr_for_plan[$i][$select_for_table_while['performer']]['workshop'][$select_for_table_while['id_order']] = 0;
                                            // прибавляем к общей сумме
                                            $arr_for_plan[$i][$performers_id[$num_per_id]]['salon_sum'] += $select_for_table_while['workshop_cost_sewing'];
                                            ?>
                                            <?php
                                        }else if($state == 'workshop'){
                                            $arr_for_plan[$i][$select_for_table_while['performer']]['salon'][$select_for_table_while['id_order']] = 0;
                                            $arr_for_plan[$i][$select_for_table_while['performer']]['workshop'][$select_for_table_while['id_order']] = $select_for_table_while['workshop_cost_sewing'];
                                            // прибавляем к общей сумме
                                            $arr_for_plan[$i][$performers_id[$num_per_id]]['workshop_sum'] += $select_for_table_while['workshop_cost_sewing'];
                                            ?>
                                            <?php
                                        }
                                    }
                                }
                                //var_dump($arr_for_plan[$performers_id[$num_per_id]]['plan'][$i]);
                            }

                            // для партнеров
                            $arr_for_plan[$i][-1]['salon'][$select_for_table_while['id_order']] = -1;
                            $arr_for_plan[$i][-1]['workshop'][$select_for_table_while['id_order']] = -1;
                            if($select_for_table_while['performer'] != "" and !in_array($select_for_table_while['performer'], $performers_id)){
                                if($state == 'designer' or $state == 'admin'){
                                    $arr_for_plan[$i][-1]['salon'][$select_for_table_while['id_order']] = $select_for_table_while['workshop_cost_sewing'];
                                    $arr_for_plan[$i][-1]['workshop'][$select_for_table_while['id_order']] = 0;
                                    // прибавляем к общей сумме
                                    $arr_for_plan[$i][-1]['salon_sum'] += $select_for_table_while['workshop_cost_sewing'];
                                    ?>
                                    <?php
                                }else if($state == 'workshop'){
                                    $arr_for_plan[$i][-1]['salon'][$select_for_table_while['id_order']] = 0;
                                    $arr_for_plan[$i][-1]['workshop'][$select_for_table_while['id_order']] = $select_for_table_while['workshop_cost_sewing'];
                                    // прибавляем к общей сумме
                                    $arr_for_plan[$i][-1]['workshop_sum'] += $select_for_table_while['workshop_cost_sewing'];
                                    ?>
                                    <?php
                                }
                            }



                        // вывод информации в таблицу
                        if($select_for_table_while['performer'] != "" and in_array($select_for_table_while['performer'], $performers_id)) {
                            for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++){
                                if($arr_for_plan[$i][$performers_id[$num_per_id]]['salon'][$select_for_table_while['id_order']] == -1){
                                    ?>
                                    <td>0</td>
                                    <?php
                                }else{
                                    ?>
                                    <td><?=$arr_for_plan[$i][$performers_id[$num_per_id]]['salon'][$select_for_table_while['id_order']]?></td>
                                    <?php
                                }

                                if($arr_for_plan[$i][$performers_id[$num_per_id]]['workshop'][$select_for_table_while['id_order']] == -1){
                                    ?>
                                    <td>0</td>
                                    <?php
                                }else{
                                    ?>
                                    <td><?=$arr_for_plan[$i][$performers_id[$num_per_id]]['workshop'][$select_for_table_while['id_order']]?></td>
                                    <?php
                                }
                            }
                            ?>
                            <td>0</td>
                            <td>0</td>
                            <?php
                        } else if($select_for_table_while['performer'] != "" and !in_array($select_for_table_while['performer'], $performers_id)){
                            for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++){
                                ?>
                                <td>0</td>
                                <td>0</td>
                                <?php
                            }
                            if($arr_for_plan[$i][-1]['salon'][$select_for_table_while['id_order']] == -1){
                                ?>
                                <td>0</td>
                                <?php
                            }else{
                                ?>
                                <td><?=$arr_for_plan[$i][-1]['salon'][$select_for_table_while['id_order']]?></td>
                                <?php
                            }

                            if($arr_for_plan[$i][-1]['workshop'][$select_for_table_while['id_order']] == -1){
                                ?>
                                <td>0</td>
                                <?php
                            }else{
                                ?>
                                <td><?=$arr_for_plan[$i][-1]['workshop'][$select_for_table_while['id_order']]?></td>
                                <?php
                            }
                        }
                        ?>
                            <td><?=$select_for_table_while['data_final_end']?></td>
                        </tr>
                        <?php
                    }
                        ?>


                    <tr>
                        <td rowspan="3" colspan="2">ИТОГ</td>
                        <td rowspan="3"><?=$sum_total_cost?></td>

                        <?php
                        for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++){
                            //$arr_for_plan[$performers_id[$num_per_id]]['workshop'][$select_for_table_while['id_order']];
                            ?>
                            <td style="text-align: center"><?=$arr_for_plan[$i][$performers_id[$num_per_id]]['salon_sum']?></td>
                            <td style="text-align: center"><?=$arr_for_plan[$i][$performers_id[$num_per_id]]['workshop_sum']?></td>
                                <?php
                        }
                        ?>
                        <td style="text-align: center"><?=$arr_for_plan[$i][-1]['salon_sum']?></td>
                        <td style="text-align: center"><?=$arr_for_plan[$i][-1]['workshop_sum']?></td>
                    </tr>



                    <tr>
                        <?php
                        for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++){
                            ?>
                            <td>План</td>
                            <?php
                            if(isset($arr_for_plan[$i][$performers_id[$num_per_id]]['plan'][$i])) {
                                ?>
                                <td><?=$arr_for_plan[$i][$performers_id[$num_per_id]]['plan'][$i]?></td>
                                <?php
                            }else{
                                ?>
                                <td>0</td>
                                <?php
                            }
                        }
                        ?>
                        <td>План</td>
                        <?php
                        if(isset($arr_for_plan[$i][-1]['plan'][$i])) {
                            ?>
                            <td><?=$arr_for_plan[$i][-1]['plan'][$i]?></td>
                            <?php
                        }else{
                            ?>
                            <td>0</td>
                            <?php
                        }
                        ?>

                    </tr>
                    <tr>
                        <?php
                        for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++){
                            ?>
                            <td>Факт</td>
                            <td><?=$arr_for_plan[$i][$performers_id[$num_per_id]]['salon_sum'] + $arr_for_plan[$i][$performers_id[$num_per_id]]['workshop_sum']?></td>
                            <?php
                        }
                        ?>
                        <td>Факт</td>
                        <td><?=$arr_for_plan[$i][-1]['salon_sum'] + $arr_for_plan[$i][-1]['workshop_sum']?></td>

                    </tr>
                </table>

                <dib>
                    Итоги:
                    <br>
                    <?php
                    $salon_sum = 0;
                    $workshop_sum = 0;
                    for($num_per_id = 0; $num_per_id < count($performers_id); $num_per_id++){
                        $salon_sum += $arr_for_plan[$i][$performers_id[$num_per_id]]['salon_sum'];
                        $workshop_sum += $arr_for_plan[$i][$performers_id[$num_per_id]]['workshop_sum'];
                    }
                    $salon_sum += $arr_for_plan[$i][-1]['salon_sum'];
                    $workshop_sum += $arr_for_plan[$i][-1]['workshop_sum'];
                    ?>
                    Салон - <?=$salon_sum?>
                    <?php $global_salon += $salon_sum ?>
                    <br>
                    Дилеры - <?=$workshop_sum?>
                    <?php $global_workshop += $workshop_sum?>
                    <br>
                    Итог - <?=$salon_sum + $workshop_sum?>
                </dib>





        <?php
            } // конец цикла for

            if($_POST['type'] == 2){
                ?>
                <dib>
                    <h1>Общий итог по периоду <?=$data_start?> - <?=$data_end?></h1>
                    <br>
                    Салон - <?=$global_salon?>
                    <br>
                    Дилеры - <?=$global_workshop?>
                    <br>
                    Итог - <?=$global_salon+$global_workshop?>
                </dib>
        <?php
            }
        }

        ?>

    </div>



</div>

</div>
</body>
</html>

