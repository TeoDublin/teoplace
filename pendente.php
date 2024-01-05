<?php 
require_once('includes.php');?>
<html lang="en"><head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style> 
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap'); 
    .blue{ 
      font-family: 'Quicksand', sans-serif; 
      background: linear-gradient( to right, lightgray 0%, white 100%); 
      color: white; 
    } 
    .tw{
      color:white!important;
    }
    div.bl-row{
      font-size: 25px; 
      background-color: white; 
      border: 0.5px solid black; 
      border-radius: 5px; 
      margin: 0.5px 0.5px 5px; 
      padding: 5px; 
      color: black; 
      text-align: center;
    }
    div.bl-row:hover{
        background-color: #8193b3;
    }
    div.bl-blue{
      background-color:#a5b5cf;
      border:0.5px solid #a5b5cf;
      border-radius:3px;    
    }
    div.bl-label{
      font-size:14px; 
      padding-top:5px;    
    }
  </style> 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
      <title>My Bills</title>
    </head>
  <body class="blue" cz-shortcut-listen="true">
    <div id="app">
      <?php 
        $today=date("d");
        $limit=23;
        
        $where="(`day` > {$today} OR `day` <= {$limit})";
        
        $headValue=SQL()->get("SELECT SUM(cost) as sum FROM `bills` WHERE {$where}")->sum;
        $headDisplay="Pendente:$".$headValue;
        require_once("templates/menu.php");
        $bills = SQL()->get("SELECT * FROM bills WHERE {$where}  ORDER BY `day` ASC");
      ?>
      <div class="container" id="container" style="margin-top:70px">
        <?php
            foreach($bills as $bill){?>
                <div class="row fade-right bl-row" row-id="<?php echo $bill->id;?>" row-value=<?php echo $bill->cost;?>>
                    <div class="col-4 bl-blue"><?php echo bills_day($bill->day);?></div>
                    <div class="col-8"><?php echo '$'.$bill->cost;?></div>
                    <div class="container">
                        <div class="row bl-label"><?php echo $bill->name;?></div>
                    </div>
                </div>
            <?php
            }
        ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script>
        jQuery(document).on('click','div.bl-row',function(e){
            var headValue = $(document).find('a.navbar-brand').attr('head-value');
            var rowValue=$(this).attr('row-value');
            var result =(headValue-rowValue);
            result = result.toFixed(2);
            $(document).find('a.navbar-brand').attr('head-value',result);
            $(document).find('a.navbar-brand').text("Pendente:$"+result);
            $(this).remove();
        });
    </script>
    </body>
</html>