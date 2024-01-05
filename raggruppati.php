<?php require_once('includes.php');?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <title>My Bills</title>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    </head>
    <body class="blue" cz-shortcut-listen="true">
        <div id="app">
            <?php 
                $headValue=SQL()->get("SELECT SUM(cost) as sum FROM `bills`")->sum;
                $headDisplay="Totale:$".$headValue;
                require_once("templates/menu.php");
                $bills = SQL()->get("SELECT * FROM bills");
            ?>
            <div class="container" style="margin-top:70px">
                <figure class="highcharts-figure">
                <div id="container"></div>

                </figure>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
            <script>
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
                        this.remove();
                    }
                }
            }
        }
    },
    legend: {
        align: 'right',
        verticalAlign: 'middle',
        layout: 'vertical',
        itemMarginTop: 10,
        itemMarginBottom: 10,
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
});            </script>
        </div>
    </body>
</html>          
