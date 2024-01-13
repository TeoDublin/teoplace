<?php
    include('../includes.php');
    switch ($_POST['operation']){
        case 'update':
            SQL()->delete('payment_date','id=1');
            SQL()->insert('payment_date', ['id'=>1,'date'=>$_POST['date']]);
            break;
    }
?>