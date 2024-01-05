<?php require_once('includes.php');?>
<html lang="en">
<head>
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
    div.form-edit{
        width: 100%;
    }
    div.form-edit-inside{
        padding: 50px 30px 20px 30px;
    }
    div.form-edit-close{
        position: absolute;
        right: 0;
        top: 0;        
        color: black;
        padding: 15px;
    }
    button.btn-lg{
        width: 100%;
    }
    .floating-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #ccc;
        border: none;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;

    }
    .floating-button i {
        color: white;       
    }    
    div.cover-background{
        position: absolute;
        left: 0;
        top: 0;
        z-index: 9999;
        width: 100%;
        height: 100%;
        background-color: white;
    }
    div.cover-inside{
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }
    </style> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="js/generalFunctions.js"></script>
    <title>My Bills</title>
</head>
<body class="blue" cz-shortcut-listen="true">
    <div id="app">
        <?php require_once("templates/menu.php");?>
        <div class="container" id="container" style="margin-top:70px">
            <?php $bills = SQL()->get("SELECT * FROM bills  ORDER BY `day` ASC");?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">day</th>
                        <th scope="col">name</th>
                        <th scope="col">billsGroup</th>
                        <th scope="col">cost</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody><?php
                    foreach($bills as $row){
                        echo "<tr>
                                <th scope='row'>{$row->id}</th>
                                <td>{$row->day}</td>
                                <td>{$row->name}</td>
                                <td>{$row->billsGroup}</td>
                                <td>{$row->cost}</td>
                                <td><i class='bi bi-pencil-fill edit'></i></td>
                                <td><i class='bi bi-x-square delete'></i></td>
                            </tr>
                        ";
                    }?>
                </tbody>
            </table>
            <div class="cover-background edit-cover" hidden=true>
                <div class="cover-inside">
                    <div class="form-edit">
                        <div class="form-edit-inside">
                            <div class="ajax-callback"></div>
                            <button type="button" class="btn btn-primary btn-lg update">Update</button>
                        </div>
                        <div class="form-edit-close close-edit"><i class="bi bi-x-lg"></i></div>
                    </div>
                </div>
            </div>
            <div class="cover-background add-form" hidden=true>
                <div class="cover-inside">
                    <div class="form-edit">
                        <div class="form-edit-close close-insert"><i class="bi bi-x-lg"></i></div>                    
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="addon-wrapping">day</span>
                            <input type="int" class="form-control day" placeholder="day" aria-label="day" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="addon-wrapping">name</span>
                            <input type="text" class="form-control name" placeholder="name" aria-label="name" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="addon-wrapping">group</span>
                            <input type="text" class="form-control billsGroup" placeholder="billsGroup" aria-label="billsGroup" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$cost</span>
                            <input type="text" class="form-control cost" aria-label="cost" value="0.00">
                        </div> 
                        <button type="button" class="btn btn-primary btn-lg insert">Insert</button>
                    </div>      
                </div>             
            </div>            
            <div class="add">
                <button class="btn btn-primary floating-button" data-toggle="tooltip" data-placement="left" title="Add">
                    <i class="bi bi-database-add">Add</i>
                </button>
            </div>
            
            <script>          
                function editForm(id){
                    loadingGif();
                    $.ajax({
                        type: 'POST',
                        dataType: 'text',
                        data: { id: id, operation: 'edit-form'},
                        url: "<?php echo root_path("ajax_request/input.php");?>"
                    }).done(function(data){
                        var div = $(document).find('div.ajax-callback');
                        $(div).find('div').each(function(){
                            $(this).remove();
                        });
                        $(div).append(data);
                    }).always(function(){
                        loadingGifClose();
                    });
                }
                function _update(){
                    loadingGif();
                    var where ="id="+$('input.id').val();
                    var set = {
                        'day': $('input.day').val(),
                        'name': $('input.name').val(),
                        'billsGroup': $('input.billsGroup').val(),
                        'cost': $('input.cost').val()
                    };
                    $.ajax({
                        type: 'POST',
                        dataType: 'text',
                        data: { operation: 'update', set: set, where: where},
                        url: "<?php echo root_path("ajax_request/input.php");?>"
                    }).done(function(data){
                        window.location.reload();
                        $(document).find('div.edit-cover').attr('hidden', true);
                    }).always(function(){
                        loadingGifClose();
                    });
                }
                function _insert(){
                    loadingGif();
                    var values = {
                        'day': $('input.day').val(),
                        'name': $('input.name').val(),
                        'billsGroup': $('input.billsGroup').val(),
                        'cost': $('input.cost').val()
                    };
                    $.ajax({
                        type: 'POST',
                        dataType: 'text',
                        data: { operation: 'insert', values: values},
                        url: "<?php echo root_path("ajax_request/input.php");?>"
                    }).done(function(data){
                        window.location.reload();
                        $(document).find('div.add-form').attr('hidden', true);
                    }).always(function(){
                        loadingGifClose();
                    });
                }                
                function _delete(id){
                    loadingGif();
                    $.ajax({
                        type: 'POST',
                        dataType: 'text',
                        data: { operation: 'delete', id: id},
                        url: "<?php echo root_path("ajax_request/input.php");?>"
                    }).done(function(data){
                        window.location.reload();
                    }).always(function(){
                        loadingGifClose();
                    });
                }                
                jQuery(document).on('click', 'div.close-edit', function(){
                    $(document).find('div.edit-cover').attr('hidden', true);
                }).on('click', 'td > i.edit', function(){
                    editForm($(this).closest('tr').find('th').html());
                    $(document).find('div.edit-cover').attr('hidden', false);
                }).on('click', 'td > i.delete', function(){
                    _delete($(this).closest('tr').find('th').html());                    
                }).on('click', 'button.update', function(){
                    _update();
                }).on('click', 'button.insert', function(){
                    _insert();
                }).on('click', 'div.add', function(){
                    $(document).find('div.add-form').attr('hidden', false);
                }).on('click', 'div.close-insert', function(){
                    $(document).find('div.add-form').attr('hidden', true);
                });

            </script>
        </div>
    </div>
</body>
</html>