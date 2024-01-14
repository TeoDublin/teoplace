<?php require_once('includes.php');?>
<html lang="en">
<head>
    <?php repoPrint('heads','default');?>
</head>
<body class="blue" cz-shortcut-listen="true">
    <div id="app">
        <?php 
            $headValue = $headDisplay = "Change bills";
            require_once("templates/menu.php");
        ?>
        <div class="container" id="container" style="margin-top:70px">
            <?php $bills = SQL()->get("SELECT * FROM bills  ORDER BY `day` ASC");?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" hidden=true>id</th>
                        <th scope="col">day</th>
                        <th scope="col">name</th>
                        <th scope="col">billsGroup</th>
                        <th scope="col">cost</th>
                    </tr>
                </thead>
                <tbody><?php
                    foreach($bills as $row){
                        echo "<tr class='editable'>
                                <th scope='row' hidden=true class='id'>{$row->id}</th>
                                <td>{$row->day}</td>
                                <td>{$row->name}</td>
                                <td>{$row->billsGroup}</td>
                                <td>{$row->cost}</td>
                            </tr>
                        ";
                    }?>
                </tbody>
            </table>
            <?php repoPrintRecursive([
                'forms'=>[
                    'default'=>[
                        'table'=>'bills',
                        'cols'=>[
                            'day'=>['type'=>'int'],
                            'name',
                            'billsGroup'=>['label'=>'group'],
                            'cost'=>['type'=>'double', 'label'=>'$cost']
                        ]
                    ]
                ],
                'footers'=>'default'
            ]);?>
        </div>
    </div>
</body>
</html>