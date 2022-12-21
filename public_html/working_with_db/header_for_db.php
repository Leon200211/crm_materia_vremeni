<div class="room_list">
    <a class="common_button_room_list" href="../../working_with_db/data_base_accountant.php?tb=0">База данных товаров</a>
    <a class="common_button_room_list" href="../../working_with_db/db_accountant_orders.php">База данных заказов</a>
    <a class="common_button_room_list" href="../../working_with_db/db_turnover_table.php">База данных текучки</a>
    <?php
    if($_SESSION['state'] == 'admin'){
        ?>
        <a class="common_button_room_list" href="../../working_with_db/final_report/final_report_main_page.php">Фин отчет</a>
        <a class="common_button_room_list" href="../../working_with_db/work_with_mail/show_all_mail.php">Почта</a>
        <?php
    }
    ?>
</div>