<?php
    function is_dev(){
        return true;
    }
    function current_url(){
        $ret = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return $ret;
    }
    function redirect($url){
        header("Location: http://{$url}");
        exit;
    }
    function bills_day($day){
        $ret=str_pad($day,2,0,STR_PAD_LEFT).date('M');
        return $ret;
    }
    function root_path($path='') {
        if(is_dev()){
            $ret="http://localhost/".PROJECT.'/'.$path;
        }else{
            $ret= str_replace('\\','/',ABSROOTPATH.'/'.$path);
        }
        return $ret;
    }
    function _is_double($val){$ret=false;
        if (is_numeric($val)) {
            $floatValue = (float)$val;
            if (is_float($floatValue)) {
                $ret=true;
            }
        }
        return $ret;
    }
?>