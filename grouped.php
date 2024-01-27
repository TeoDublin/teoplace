<?php require_once('includes.php');?>
<html lang="en">
    <head>
        <?php repoPrintRecursive(['heads'=>['default','highcharts']]);?>
        <style> 
            @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap'); 
            .highcharts-figure,
            .highcharts-data-table table {
                min-width: 320px;
                max-width: 800px;
                margin: 1em auto;
            }

            .highcharts-data-table table {
                font-family: Verdana, sans-serif;
                border-collapse: collapse;
                border: 1px solid #ebebeb;
                margin: 10px auto;
                text-align: center;
                width: 100%;
                max-width: 500px;
            }

            .highcharts-data-table caption {
                padding: 1em 0;
                font-size: 1.2em;
                color: #555;
            }

            .highcharts-data-table th {
                font-weight: 600;
                padding: 0.5em;
            }

            .highcharts-data-table td,
            .highcharts-data-table th,
            .highcharts-data-table caption {
                padding: 0.5em;
            }

            .highcharts-data-table thead tr,
            .highcharts-data-table tr:nth-child(even) {
                background: #f8f8f8;
            }

            .highcharts-data-table tr:hover {
                background: #f1f7ff;
            }

            input[type="number"] {
                min-width: 50px;
            }
        </style> 
    </head>
    <body class="blue" cz-shortcut-listen="true">
        <div id="app">
            <?php 
                $headValue=SQL()->getCol("SELECT SUM(cost) as sum FROM `bills`", 'sum');
                $headDisplay="Total:$".$headValue;
                require_once("templates/menu.php");
                $bills = SQL()->get("SELECT * FROM bills");
            ?>
            <div class="container" style="margin-top:70px">
                <figure class="highcharts-figure">
                    <div id="container"></div>
                </figure>
            </div>
            <?php repoPrint('footers','default');?>
            <script>
                function recalcTotal(e){
                    var headValue = $(document).find('a.navbar-brand').attr('head-value');
                    var rowValue=e.y;
                    var result =(headValue-rowValue);
                    result = result.toFixed(2);
                    $(document).find('a.navbar-brand').attr('head-value',result);
                    $(document).find('a.navbar-brand').text("Total:$"+result);
                    $(this).remove();                 
                }
                Highcharts.chart('container', {
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true,
                            point: {
                                events: {
                                    click: function () {
                                        recalcTotal(this);
                                        this.remove();
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        align: 'middle',
                        verticalAlign: 'bottom',
                        layout: 'horizontal',
                        itemMarginTop: 10,
                        itemMarginBottom: 0,
                        itemStyle: {
                            fontSize: '14px',
                            fontWeight: 'normal'
                        },
                        labelFormatter: function () {
                            return this.name + ': ' + this.y + '(' + Highcharts.numberFormat(this.percentage, 1) + '%)';
                        }
                    },
                    series: [{
                        data: 
                            <?php
                                $result=SQL()->getArray("SELECT `billsGroup` as `name`, SUM(`cost`) as `y` FROM `bills` GROUP BY `billsGroup` ORDER BY SUM(`cost`) DESC");
                                echo json_encode($result);
                            ?>
                        
                    }]
                });   
            </script>
        </div>
    </body>
</html>          
