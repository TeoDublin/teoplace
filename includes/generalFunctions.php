<?php
    function is_dev(){
        return preg_match("#C:#", ABSROOTPATH);
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
            $ret=DEVPATH."/".PROJECT.'/'.$path;
        }else{
            $ret= PRODPATH."/{$path}";
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
    function now_diff($date){
        $now = new DateTime();
        $date2 = new DateTime($date);
        $interval = $date2->diff($now);
        return (int)$interval->format('%a');        
    }
    function add_days_working_end($days, $date='', $format='Y-m-d'){
        $originalDate = new DateTime($date);
        $modifiedDate = clone $originalDate;
        $modifiedDate->modify("+{$days} days");
        $weekday = $modifiedDate->format('N');
        switch ($weekday) {
            case 6:
                $modifiedDate->modify("+2 days");
                break;
            case 7:
                $modifiedDate->modify("+1 days");
                break;
        }
        return $modifiedDate->format($format);
    }
    function repoPrint($element, $fragment, $params=[]){
        require(ABSROOTPATH."/repository/{$element}/{$fragment}.php");
    }
    function repoPrintRecursive($repo){
        foreach ($repo as $element => $fragments) {     
            if(is_array($fragments)){
                foreach ($fragments as $maybeFragment => $maybeParams) {
                    if(is_int($maybeFragment)){
                        $fragment = $maybeParams; $params = [];
                    }else{
                        $fragment = $maybeFragment; $params = $maybeParams;
                    }
                    repoPrint($element, $fragment, $params);
                }
            }else{
                repoPrint($element, $fragments);
            }   
        }
    }
?>