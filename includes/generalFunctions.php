<?php
    function is_dev(){
        return false;
    }

    function site_url(){
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
?>