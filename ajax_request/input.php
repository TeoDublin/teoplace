<?php
    include('../includes.php');
    switch ($_POST['operation']){
        case 'edit-form':
            $bill=SQL()->getRow("SELECT * FROM bills WHERE id={$_POST['id']}");
            echo '
                <div>
                    <input class="id" value="'.$_POST['id'].'" hidden=true>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="addon-wrapping">day</span>
                    <input type="int" class="form-control day" placeholder="day" aria-label="day" aria-describedby="basic-addon1" value="'.$bill['day'].'">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="addon-wrapping">name</span>
                    <input type="text" class="form-control name" placeholder="name" aria-label="name" aria-describedby="basic-addon1" value="'.$bill['name'].'">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="addon-wrapping">group</span>
                    <input type="text" class="form-control billsGroup" placeholder="billsGroup" aria-label="billsGroup" aria-describedby="basic-addon1" value="'.$bill['billsGroup'].'">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">$cost</span>
                    <input type="text" class="form-control cost" aria-label="cost" value="'.number_format($bill['cost'], 2, '.', '').'">
                </div>
            ';
            break;
        case 'insert':
            echo SQL()->insert('bills', $_POST['values']);
            break;
        case 'update':
            echo SQL()->update('bills', $_POST['set'], $_POST['where']);
            break;   
        case 'delete':         
            echo SQL()->delete('bills', "id={$_POST['id']}");
            break;                
    }

    exit();
?>