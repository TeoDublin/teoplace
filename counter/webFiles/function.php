<?php
    function is_dev(){
        return $_SERVER['SERVER_NAME']==="localhost";
    }
    function site_url(){
        $ret = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return $ret;
    }
    function redirect($url){
        header("Location: http://{$url}");
        exit;
    }
    function template($template){
        require_once("templates/{$template}");
    }
    function page($page){
        $webPage="pages/{$page}/{$page}.php";
        if(!$page||!file_exists($webPage)){
            return false;
        }else{
            return require_once($webPage);
        }
    }
    function isValidSet($arr=[], $key=''){
        if(!isset($arr[$key])){
            return false;
        }else{
            if(!isset($arr[$key])){
                return false;
            }else{
                if($arr[$key]==''){
                    return false;
                }else{
                    return true;
                }
            }
        }
    }
    function datetimes_to_sql($sqldatetime,$params=[]) {
        return date_wrap("Y-m-d H:i:s", datetime_to_time(datetime_to_sql($sqldatetime,$params),$params));
    }    
    function date_wrap($format,$time=-1,$params=[]) {
        if(isValidSet($params,'empty_date')){
            $format='0000-00-00 H:i:s';		
        }
        else if ($time<=0) return '';
        return date($format,$time);
    }
    function datetime_to_time($datetime,$params=[],$skip=false) {
        if(!$skip)
            $datetime=datetime_to_sql($datetime);
        $time = strtotime($datetime);
        if (isset($params['+days']))
            $time+=(int)$params['+days']*24*60*60;
            return $time;
    }  
    function datetime_to_sql($datetime,$end=false, $params=[]) {
        $regex_string='#^(-|\+)?([0-9]+)\s*(second|minute|hour|month|day|year)[s]?\s*(end|begin)?\s*(time_end|time_begin|time_now|year_begin|year_end)?$#i';
        if (is_array($end)) {
            $params=$end;
            $end=isset($params['end']);
            if (isset($params[0]))
                $params=['op'=>$params];
        }
        else if (is_string($end)) {
            $params['op']=$end;
            $end=false;
        }
        $date = null;
        $time = null;
        $ops = [];
        if (is_string($datetime)) {
            switch ($datetime) {
                case 'now':
                case 'now_begin':
                case 'now_end':
                case 'ref':
                    $ops[]=$datetime;
                    break;
                default:
                    if (preg_match($regex_string,$datetime)) {
                        $ops[]=$datetime;
                        $datetime=null;
                    }
                    break;
            }
        }
        if (!count($ops)) {
            if (is_int($datetime)) {
                $date = date('Y-m-d H:i:s',$datetime);
            }
            else if(is_a($datetime,"DateTime")) {
                $date = date('Y-m-d H:i:s',$datetime->getTimestamp());
            }
            else if (preg_match('#([0-9]{4})([0-9]{2})([0-9]{2})\s*([0-9]{2}:[0-9]{2}(:[0-9]{2})?)?#',$datetime,$m) && isset($params['compact'])) {
                if($params['compact']=='from'){
                    if (!$m[4]) $m[4] = ($end?'23:59':'00:00');
                    if (!$m[5]) $m[4].= ($end?':59':':00');
                    $date = $m[1].'-'.$m[2].'-'.$m[3].' '.$m[4];
                }
            }
            else if (preg_match('#([0-9]{4})-([0-9]{2})-([0-9]{2})T([0-9]{2}:[0-9]{2}(:[0-9]{2})?Z)?#',$datetime,$m)) {
                $date = date('Y-m-d H:i:s',strtotime($datetime));
            }
            else if (preg_match('#([0-9]{4})-([0-9]{2})-([0-9]{2})(?:T|\s*)([0-9]{2}:[0-9]{2}(:[0-9]{2})?)?#',$datetime,$m)) {
                if (!$m[4]) $m[4] = ($end?'23:59':'00:00');
                if (!$m[5]) $m[4].= ($end?':59':':00');
                $date = $m[1].'-'.$m[2].'-'.$m[3].' '.$m[4];
            }
            else if (preg_match('#([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})\s*([0-9]{1,2})[:\.]([0-9]{1,2})([:\.]([0-9]{1,2}))?#',$datetime,$m)) {
                if (!$m[7]) $m[7] = '00';
                if (!$m[5]) $m[5] = ($end?'59':'00');
                if (!$m[4]) $m[4] = ($end?'23':'00');
                $date = $m[3].'-'.str_pad($m[2],2,"0",STR_PAD_LEFT).'-'.str_pad($m[1],2,"0",STR_PAD_LEFT).' '.str_pad($m[4],2,"0",STR_PAD_LEFT).':'.str_pad($m[5],2,"0",STR_PAD_LEFT).':'.($end?'59':$m[7]);
            }
            else if (preg_match('#([0-9]{2})/([0-9]{2})/([0-9]{4})\s*([0-9]{2}:[0-9]{2})?(:[0-9]{2})?#',$datetime,$m)) {
                if (!$m[5]) $m[5] = ':00';
                if (!$m[4]) $m[4] = ($end?'23:59':'00:00');
                $date = $m[3].'-'.$m[2].'-'.$m[1].' '.$m[4].($end?':59':$m[5]);
            }
            else if (preg_match('#([0-9]{2})/([0-9]{2})/([0-9]{2})\s*([0-9]{2}:[0-9]{2})?(:[0-9]{2})?#',$datetime,$m)) {
                if (!$m[5]) $m[5] = ':00';
                if (!$m[4]) $m[4] = ($end?'23:59':'00:00');
                $date = '20'.$m[3].'-'.$m[2].'-'.$m[1].' '.$m[4].($end?':59':$m[5]);
            }
        }
        if (isset($params['op'])) {
            if (!is_array($params['op']))
                $params['op'] = [$params['op']];
                $ops = array_merge($ops,$params['op']);
        }
        if (count($ops)) {
            if (!$time) {
                if ($date)
                    $time = datetime_to_time($date,[],true);
                else if ($datetime)
                    $time = datetime_to_time($datetime,[],true);
            }
            if (!$time)
                $time=time();
                foreach ($ops as $op=>$opv) {
                    if (is_int($op))
                        $op=$opv;
                        if ($op=='now') {
                            $time = datetime_to_time(date('Y-m-d H:i:s'),[],true);
                        }else if ($op=='empty_date') {
                            $time = datetime_to_time(date('Y-m-d H:i:s',$time),[],true);
                            $params['empty_date']=1;
                        }else if ($op=='to_time') {
                            $time = datetime_to_time(date('Y-m-d '.$opv,$time),[],true);
                        }else if ($op=='time_begin') {
                            $time = datetime_to_time(date('Y-m-d 00:00:00',$time),[],true);
                        }else if ($op=='time_end') {
                            $time = datetime_to_time(date('Y-m-d 23:59:59',$time),[],true);
                        }else if ($op=='year_begin') {
                            $time = datetime_to_time(date('Y-01-01 H:i:s',$time),[],true);
                        }else if ($op=='year_end') {
                            $time = datetime_to_time(date('Y-12-31 H:i:s',$time),[],true);
                        }else if ($op=='next_time_begin') {
                            $time = strtotime("+1 days", $time);
                            $time = datetime_to_time(date('Y-m-d 00:00:00',$time),[],true);
                        }else if ($op=='begin') {
                            $time = datetime_to_time(date('Y-m-01 H:i:s',$time),[],true);
                        }else if ($op=='end') {
                            $time = datetime_to_time(date('Y-m-t H:i:s',$time),[],true);
                        }else if ($op=='next_working') {
                            $time = timestamp_next_working($time);
                        }else if ($op=='now_begin') {
                            $time = datetime_to_time(date('Y-m-d 00:00:00'),[],true);
                        }else if ($op=='now_end') {
                            $time = datetime_to_time(date('Y-m-d 23:59:59'),[],true);
                        }else if ($op=='ref') {
                            $time = datetime_to_time('2010-10-10 10:10:10',[],true);
                        }else if (is_string($op)&&preg_match($regex_string,$op,$m)) {
                            $m[2]=(int)$m[2];
                            switch ($m[3]) {
                                case 'second':
                                    $time = strtotime("{$m[1]}{$m[2]} seconds", $time);
                                    break;
                                case 'minute':
                                    $time = strtotime("{$m[1]}{$m[2]} minutes", $time);
                                    break;
                                case 'hour':
                                    $time = strtotime("{$m[1]}{$m[2]} hours", $time);
                                    break;
                                case 'day':
                                    $time = strtotime("{$m[1]}{$m[2]} days", $time);
                                    break;
                                case 'month':
                                    $ftime=strtotime("first day of {$m[1]}{$m[2]} months", $time);
                                    $ltime=strtotime("last day of +0 months", $ftime);
                                    $tmld=date('d',strtotime("last day of 0 months", $time));
                                    $tmtd=date('d',$time);
                                    if (date('d',$time)>date('d',$ltime) || (int)$tmld===(int)$tmtd) {
                                        $time=$ltime;
                                    }
                                    else {
                                        $time = strtotime((date('d',$time)-1)." days", $ftime);
                                    }
                                    break;
                                case 'year':
                                    $ftime=strtotime("first day of +0 months", $time);
                                    $ftime=strtotime("{$m[1]}{$m[2]} year",$ftime);
                                    $ltime=strtotime("last day of +0 months", $ftime);
                                    $tmld=date('d',strtotime("last day of 0 months", $time));
                                    $tmtd=date('d',$time);
                                    if (date('d',$time)>date('d',$ltime) || (int)$tmld===(int)$tmtd) {
                                        $time=$ltime;
                                    }else {
                                        $time = strtotime((date('d',$time)-1)." days", $ftime);
                                    }
                                    break;
                            }
                            $df = 'Y-m-d';
                            $tf = 'H:i:s';
                            if ($m[4] == 'begin') {
                                $df = 'Y-m-01';
                                $tf = '00:00:00';
                            }else if ($m[4] == 'end') {
                                $df = 'Y-m-t';
                                $tf = '23:59:59';
                            }
                            if ($m[5] == 'time_begin') {
                                $tf = '00:00:00';
                            }else if ($m[5] == 'time_end') {
                                $tf = '23:59:59';
                            }else if ($m[5] == 'time_now') {
                                $tf = 'H:i:s';
                            }
                            $time = datetime_to_time(date("{$df} {$tf}",$time),[],true);
                        }
                }
                $date = date('Y-m-d H:i:s',$time);
        }
        return date_wrap('Y-m-d H:i:s',datetime_to_time($date,$params,true),$params);
    }      
    function timestamp_next_working($time=null,$params=[]) {
        if ($time===null) $time = time();
        $feste = [
            "01-01"=>"Capodanno",
            "06-01"=>"Epifania",
            date("d-m", easter_date((int)date("Y",$time))+60*60*24)=>'Pasquetta',
            "25-04"=>"Liberazione",
            "01-05"=>"Festa Lavoratori",
            "02-06"=>"Festa della Repubblica",
            "15-08"=>"Ferragosto",
            "01-11"=>"Tutti Santi",
            "08-12"=>"Immacolata",
            "25-12"=>"Natale",
            "26-12"=>"St. Stefano",
        ];
        do {
            $time+=60*60*24;
        } while (isset($feste[date('d-m',$time)])||(int)date('N',$time)===7||(isset($params['working_saturday'])&&(int)date('N',$time)===6));
        return $time;
    }
    function current_user_id(){
        return 1;
    }
    function scan($dir){
        return array_diff(scandir($dir), ['.','..']);
    }
    function scanFile($dir){$ret=[];
        foreach (scan($dir) as $file) {
            if(is_file("{$dir}/{$file}")){
                $ret[]=$file;
            }
        }
        return $ret;
    }
?>