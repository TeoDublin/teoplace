<?php require_once('includes.php');?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style> 
            @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap'); 
            .highcharts-subtitle{
                text-align: center;
            }

        </style> 

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <title>My Bills</title>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="js/generalFunctions.js"></script>
    </head>
    <body class="blue" cz-shortcut-listen="true">
        <div id="app">
            <?php 
                $headValue="Payment Days";
                $headDisplay="Payment Days";
                require_once("templates/menu.php");
                $bills = SQL()->get("SELECT * FROM bills");
                $date=SQL()->getCol("SELECT * FROM payment_date", 'date');
                $passingDays=now_diff($date);
                $missingDays=30-$passingDays;
            ?>
            <div class="container" style="margin-top:70px">
                <figure class="highcharts-figure"><div id="container"></div></figure>
                <div class="container mt-5">
                    <div class="date-input"> <label for="datepicker" class="form-label">Select a Date:</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control date" id="datepicker" name="datepicker" value="<?php echo $date;?>">
                        <button type="button" class="btn btn-primary btn-lg update">Update</button></div>
                    </div>
                </div>
                
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
            <script>
                const missingDays = <?php echo $missingDays;?>;
                const passingDays = <?php echo $passingDays;?>;

                function getSubtitle(){
                    return `
                        <span style="font-size: 80px;color:<?php echo COLORPRIMARY;?>">${passingDays}</span>
                        <br>
                        <span style="font-size: 22px;">Missing:<b>${missingDays}</b></span>
                    `;
                }
                function getData(){
                    return [missingDays, passingDays];
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
                    subtitle: {
                        useHTML: true,
                        text: getSubtitle(),
                        floating: true,
                        verticalAlign: 'middle',
                        y: 30
                    },
                    legend: {
                        enabled: false
                    },

                    plotOptions: {
                        series: {
                            borderWidth: 0,
                            colorByPoint: true,
                            type: 'pie',
                            size: '100%',
                            innerSize: '80%',
                            dataLabels: {
                                enabled: false,
                            }
                        }
                    },
                    colors: [<?php echo "'".COLORSECONDARY."','".COLORPRIMARY."'";?>],
                    series: [
                        {
                            type: 'pie',
                            name: 1,
                            data: getData()
                        }
                    ]
                });
                function _update(){
                    loadingGif();
                    var date =  $('input.date').val();
                    $.ajax({
                        type: 'POST',
                        dataType: 'text',
                        data: { operation: 'update', date:date},
                        url: "<?php echo root_path("ajax_request/paymentCount.php");?>"
                    }).done(function(data){
                        window.location.reload();
                    }).always(function(){
                        loadingGifClose();
                    });
                }
                jQuery(document).on('click', 'button.update', function(){
                    _update();
                });
                                        
            </script>
        </div>
    </body>
</html>          
