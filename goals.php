<?php 
    require_once('includes.php');
    function _chart($i,$goal){
        return "
            Highcharts.chart('chart{$i}', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: null
                },
                xAxis: {
                    visible: false
                },
                yAxis: {
                    visible: false
                },
                plotOptions: {
                    column: {
                        grouping: false,
                        dataLabels: {
                            enabled: true,
                            inside: true
                        }
                    }
                },
                series: [{
                    name: 'Total',
                    data: [{
                        y: {$goal['total']},
                        dataLabels: {
                            enabled: true,
                            inside: false,
                            align: 'center',
                            verticalAlign: 'bottom',
                            style: {
                                fontWeight: 'bold'
                            }
                        }
                    }],
                    zIndex: 1,
                    pointWidth: 100
                }, {
                    name: 'Pending',
                    data: [{
                        y: {$goal['current']},
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            align: 'center',
                            verticalAlign: 'top',
                            style: {
                                fontWeight: 'bold'
                            }
                        }
                    }],
                    zIndex: 2,
                    pointWidth: 80
                }],
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    enabled: false
                },
                accessibility: {
                    enabled: false
                },
                exporting: {
                    enabled: false,
                    buttons: {
                        contextButton: {
                            enabled: false
                        }
                    }
                }                    
            });        
        ";
    }
    function _col($class,$i,$goal){
        return '
            <div class="col-sm-'.$class.' mb-3 editable">
                <span class="id" hidden=true>'.$goal['id'].'</span>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">'.$goal['title'].'</h5>
                        <div class="chart" id="chart'.$i.'"></div>
                    </div>
                </div>
            </div>
        ';
    }
    function _countCols($countGoals){
        switch ($countGoals) {
            case 1:
            case 2:
                $ret=$countGoals;
                break;
            default:
                $ret=3;
                break;
        }
        return $ret;
    }
?>
<html lang="en">
    <head>
        <?php repoPrintRecursive(['heads'=>['default','highcharts']]);?>
        <style>
            div.chart{
                max-width: 300px;
                max-height: 300px;
                margin: auto;   
            }
        </style>

    </head>
    <body class="blue" cz-shortcut-listen="true">
        <div id="app">
            <?php 
                $headValue=$headDisplay="Goals";
                require_once("templates/menu.php");
                $goals = SQL()->getArray("SELECT * FROM goals");
                $countGoals=(($goals) ? count($goals) : 0);
                $countCols=_countCols($countGoals);
                $countRows = ceil($countGoals/3);
                $count = 0;
            ?>
            <div class="container text-center" style="margin-top:70px">
                <?php  
                    if($countGoals==0){
                        echo "
                            <div style='height:50%; display: flex; align-items: center; justify-content: center;'>
                                <h1 class='text-center text-gray' style='font-size:30px;font-style:italic'>Add some goal</h1>
                            </div>
                        ";
                    }else{
                        for ($i=0; $i < $countRows; $i++) { 
                            echo '<div class="row">';
                                for ($j=0; $j < $countCols; $j++) { 
                                    echo _col(12/$countCols,$count,$goals[$count]);$count++;
                                }
                            echo '</div>';
                        }
                    }
                ?>                     
                <?php repoPrint('forms','default',['table'=>'goals','cols'=>['title','total','current']]);?>
            </div>
            <?php repoPrint('footers','default');?>                           
            <script>
                <?php
                    foreach ($goals as $i => $goal) {
                        echo _chart($i, $goal);
                    }
                ?>
            </script>
        </div>
    </body>
</html>          
