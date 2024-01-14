
<?php repoPrint('btn','add');?>
<style> 
    div.form-default{
        width: 100%;
    }
    div.form-default-inside{
        padding: 50px 30px 20px 30px;
    }
    div.form-default-close{
        position: absolute;
        right: 0;
        top: 0;        
        color: black;
        padding: 15px;
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
<script>
    var ajax_url = "<?php echo root_path("ajax_requests/defaultForm.php");?>";
    function _values(){
        <?php 
            $cols=[];
            foreach ($params['cols'] as $maybeCol =>$maybeParams) {
                if(is_array($maybeParams)){
                    $col_params=$maybeParams;
                    $col=$maybeCol;
                }else{
                    $col=$maybeParams;
                    $col_params=[];
                }
                $cols[]="'{$col}': $('input.$col').val()";
            }
            echo "return {".implode(',',$cols).'}';
        ?>           
    }
    function _cols(){
        return <?php echo json_encode($params['cols']);?>;
    }
    jQuery(document).on('click', 'div.close-form', function(){
        $(document).find('div.form-cover').attr('hidden', true);
    });
</script>

<!-- edit-form -->
    <div class="cover-background form-cover" hidden=true id='form-edit'>
        <div class="cover-inside">
            <div class="form-default">
                <div class="form-default-inside">
                    <div class="ajax-callback"></div>
                    <div class="row">
                        <div class="col col-6"><?php repoPrint('btn','delete');?></div>
                        <div class="col col-6"><?php repoPrint('btn','update');?></div>                      
                    </div>
                </div>
                <div class="form-default-close close-form"><i class="bi bi-x-lg"></i></div>
            </div>
        </div>
    </div>
    <script>
        function editForm(id){
            loadingGif();
            $.ajax({
                type: 'POST',
                dataType: 'text',
                data: { id: id, operation: 'edit-form', table:'<?php echo $params['table'];?>', cols:_cols()},
                url: ajax_url
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
            var values = _values();
            $.ajax({
                type: 'POST',
                dataType: 'text',
                data: { operation: 'update', values: values, where: where, table:'<?php echo $params['table'];?>'},
                url: ajax_url
            }).done(function(data){
                window.location.reload();
                $(document).find('div#form-edit').attr('hidden', true);
            }).always(function(){
                loadingGifClose();
            });
        }             
        function _delete(id){
            loadingGif();
            $.ajax({
                type: 'POST',
                dataType: 'text',
                data: { operation: 'delete', id: id, table:'<?php echo $params['table'];?>'},
                url: ajax_url
            }).done(function(data){
                window.location.reload();
            }).always(function(){
                loadingGifClose();
            });
        }
        jQuery(document).on('click', '.editable', function(){
            editForm($(this).find('.id').html());
            $(document).find('div#form-edit').attr('hidden', false);
        }).on('click', 'button.btn-delete', function(){
            _delete($('input.id').val());                    
        }).on('click', 'button.btn-update', function(){
            _update();
        });
    </script>

<!-- add-form -->
    <div class="cover-background form-cover" hidden=true id='add-form'>
        <div class="cover-inside">
            <div class="form-default">
                <div class="form-default-inside">
                    <div class="ajax-callback"></div>
                    <?php repoPrint('btn','insert');?> 
                </div>
                <div class="form-default-close close-form"><i class="bi bi-x-lg"></i></div>
            </div>  
        </div>             
    </div>
    <script>
        function addForm(){
            loadingGif();
            $.ajax({
                type: 'POST',
                dataType: 'text',
                data: { operation: 'add-form', cols:_cols()},
                url: ajax_url
            }).done(function(data){
                var div = $(document).find('div#add-form').find('div.ajax-callback');
                $(div).find('div').each(function(){
                    $(this).remove();
                });
                $(div).append(data);
            }).always(function(){
                loadingGifClose();
            });
        }       
        function _insert(){
            loadingGif();
            var values = _values();
            $.ajax({
                type: 'POST',
                dataType: 'text',
                data: { operation: 'insert', values: values, table:'<?php echo $params['table'];?>'},
                url: ajax_url
            }).done(function(data){
                window.location.reload();
                $(document).find('div#add-form').attr('hidden', true);
            }).always(function(){
                loadingGifClose();
            });
        }                                
        jQuery(document).on('click', 'button.btn-insert', function(){
            _insert();
        }).on('click', 'div.btn-add', function(){
            addForm();
            $(document).find('div#add-form').attr('hidden', false);
        }).on('click', 'div.close-insert', function(){
            $(document).find('div#add-form').attr('hidden', true);
        });
    </script>