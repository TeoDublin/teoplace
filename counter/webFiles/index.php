<?php
    require_once('function.php');
    
    foreach (scanFile('class') as $class) {
        require_once("class/{$class}");
    }
    foreach (scanFile('includes') as $include) {
        require_once("includes/{$include}");
    }
    
    page('login');

    if(isValidSet($_REQUEST, 'page')){
        page($_REQUEST['page']);
    }elseif(isValidSet($_REQUEST, 'url')){
        require_once($_REQEST['url']);
    }else{
        page('home');
    }
    
?>