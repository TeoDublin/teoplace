<?php require_once('includes.php');?>
<html lang="en">
    <head>
        <?php repoPrintRecursive(['heads'=>['default','highcharts']]);?>
        <script src="js/generalFunctions.js"></script>
        <style> 
            @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@500&display=swap'); 
            .highcharts-subtitle{
                text-align: center;
            }
        </style> 
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
            <div class="container" style="margin-top:70px;background-color:white">
                <figure class="highcharts-figure"><div id="container"></div></figure>
                <div class="container p-1">
                    <div class="date-input"> <label for="datepicker" class="form-label">Select a Date:</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control date" id="datepicker" name="datepicker" value="<?php echo $date;?>">
                        <button type="button" class="btn btn-primary btn-lg update">Update</button></div>
                    </div>
                </div>
                
            </div>
            <?php repoPrint('footers','default');?>
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
                    ],
                    tooltip: {
                        enabled: false // Disable tooltip
                    },
                    accessibility: {
                        enabled: false // Disable accessibility module
                    },
                    exporting: {
                        enabled: false, // Disable exporting module
                        buttons: {
                            contextButton: {
                                enabled: false // Disable context button
                            }
                        }
                    }                    
                });
                function _update(){
                    loadingGif();
                    var date =  $('input.date').val();
                    console.log(date);
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
