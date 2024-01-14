<?php
    include('../includes.php');
    function _inputs($values=[]){
        $ret='
            <div>
                <input class="id" value="'.($_POST['id']??'').'" hidden=true>
            </div>
        ';
        foreach ($_POST['cols'] as $maybeCol => $maybeParams) {
            if(is_int($maybeCol)){
                $col=$maybeParams;
                $col_params=[];
            }else{
                $col=$maybeCol;$col_params=$maybeParams;
            }
            if(count($values)){
                $value=$values[$col];
            }else{
                $value='';
            }
            if(isset($col_params['label'])){
                $label=$col_params['label'];
            }else{
                $label=$col;
            }
            switch ($col_params['type']??'') {
                case 'int':
                    $ret.='
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="addon-wrapping">'.$label.'</span>
                            <input type="int" class="form-control '.$col.'" placeholder="'.$label.'" aria-label="'.$label.'" aria-describedby="basic-addon1" value="'.$value.'">
                        </div>
                    ';
                    break;
                case 'double':
                    $ret.='
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="addon-wrapping">'.$label.'</span>
                            <input type="text" class="form-control '.$col.'" placeholder="'.$label.'" aria-label="'.$label.'" aria-describedby="basic-addon1" value="'.number_format((int)($value??0.00), 2, '.', '').'">
                        </div>
                    ';
                    break;
                default:
                    $ret.='
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="addon-wrapping">'.$label.'</span>
                            <input type="text" class="form-control '.$col.'" placeholder="'.$label.'" aria-label="'.$label.'" aria-describedby="basic-addon1" value="'.$value.'">
                        </div>
                    ';
                    break;
            }
        }
        return $ret;
    }
    switch ($_POST['operation']){
        case 'add-form':
            echo _inputs();
            break;
        case 'edit-form':
            $bill=SQL()->getRow("SELECT * FROM {$_POST['table']} WHERE id={$_POST['id']}");
            echo _inputs($bill);
            break;
        case 'insert':
            echo SQL()->insert($_POST['table'], $_POST['values']);
            break;
        case 'update':
            echo SQL()->update($_POST['table'], $_POST['values'], $_POST['where']);
            break;   
        case 'delete':         
            echo SQL()->delete($_POST['table'], "id={$_POST['id']}");
            break;                
    }

    exit();
?>