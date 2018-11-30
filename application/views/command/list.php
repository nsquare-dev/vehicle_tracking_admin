
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-black"></i>
                    <span class="bold uppercase">Scooto Command Listing</span>
                </div>
                <div class="actions">
                    <div class="btn-group">

                    </div>
                </div>
            </div>

            <div class="container-fluid text-center">    
                <div class="col-sm-4 col-md-4 col-lg-4">                
                    <div class="panel panel-info">
                        <div  class="panel-heading">
                            <h4>Send Commands to controller</h4>
                        </div>
                        <div class="panel-body form-horizontal payment-form">
                            <?php
                            // Limit the columns per row
                            //Columns must be a factor of 12 (1,2,3,4,6,12)
                            $numOfCols = 2;
                            $rowCount = 0;
                            $bootstrapColWidth = 12 / $numOfCols;
                            ?>
                            <div class="row">
                                <?php
                                foreach ($command_list as $command) {
                                    ?>
                                    <div class="col-md-<?php echo $bootstrapColWidth; ?>">
                                        <div class="form-group">
                                            <label class="control-label col-md-6" for="pwd"><?php echo $command['title'] ?>:</label>
                                            <div class="col-md-6"> 
                                                <input type="button" value="Send" name="off" class="command btn btn-success" data-id="<?php echo $command['command']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $rowCount++;
                                    if ($rowCount % $numOfCols == 0)
                                        echo '</div><div class="row">';
                                }
                                ?>
                            </div>
                        </div>
                         
                        <div class="panel-footer">
                            <div class="bg-default">
                                <button onclick="startScript(this)" class="btn btn-success"> Start</button>
                                <button onclick="stopScript(this)" class="btn btn-danger"> Stop</button>						
                            </div>
                            <div class="form-group">
                                <span class="bg-info" id="informer"></span><br/>                        
                                <span class="bg-default" id="informer_div"></span>
                            </div>
                        </div>
                    </div> 
                </div> <!-- / panel preview -->

                <div class="col-sm-4 col-md-4 col-lg-4">
                    <div class="panel panel-success">
                        <div  class="panel-heading">
                            <h4>Command Response</h4>
                        </div>
                        <div class="panel-body form-horizontal payment-form">
                            <div class="form-group">
                                <textarea cols="40" rows="45" id="jumbotron_cmd" class="form-control"></textarea>
                            </div>

                        </div>
                    </div> 
                </div>

                <div class="col-sm-4 col-md-4 col-lg-4">
                    <div class="panel panel-info">
                        <div  class="panel-heading">
                            <h4>Continuous  Response</h4>
                        </div>
                        <div class="panel-body form-horizontal payment-form">
                            <div class="form-group">
                                <textarea cols="40" rows="45" id="jumbotron" class="form-control"></textarea>
                            </div>

                        </div>
                    </div> 
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    
    $(document).ready(function(){

        
        setInterval( function(){ callInterval() } , 2000); 
        
        function callInterval(){
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('command/read_data'); ?>",
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,
                    async: true,
                    beforeSend:function(){ 
                        $('#informer').empty();
                    },
                    success: function (data) {
                        $("#informer").html("Responce OK");
                        $("#jumbotron").prepend(data + "\n");
                    },
                    complete: function() {
                        // Schedule the next request when the current one's complete 
                    },
                    timeout: 3000 
                });
        } 
              
    }); 
    <!-- End Socket Script--> 
                
    $(document).on('click', '.command', function () {
        var    command    =    $(this).attr('data-id');
        data    =    new    FormData();
        data.append('command',    command);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('command/execute'); ?>",
            data: data,
            enctype: 'multipart/form-data',
            processData: false, // tell jQuery not to process the data
            contentType: false,
            async: true,
            beforeSend:function(){
                $("#informer_div").html("Sending command, Please wait"); 
            },
            success: function (data) {
                var data =   JSON.parse(data)
            
                if(data.status =='200' ){
                    $("#informer_div").removeClass("bg-default").addClass("bg-success");
                    $("#informer_div").html("Responce: "+ data.message);
                }else{
                     $("#informer_div").removeClass("bg-success").addClass("bg-default");
                     $("#informer_div").html("Responce: "+ data.message);
                }
               
                if (data.data == null){
                    $("#jumbotron_cmd").prepend("Responce: "+ data.message+ "\nData : No Data\n");
                }else{
                    var row  = data.data;
                    var html ="";
                    if(row.isSent){
                        html += "Command: "+row.command+"\n";
                        html += "Date and Time: "+row.sent_date_time+"\n";
                    }                    
                    html += "Response: "+row.ack+"\n";
                    html += "Date and Time: "+row.received_date_time+"\n";
                    html += "-----------------------------------------\n";
                    
                    $("#jumbotron_cmd").prepend("Status: "+ data.message+ "\n "+ html + "\n");
                }

                
            },
            complete: function (data){
                $("#informer_div").empty();
            },
            timeout: 11000 
        });

    });
                                                        
    function startScript(e){
        $.ajax({
            type: "GET",
            url: "<?php echo base_url('server-code/index.php'); ?>",
            processData: false, // tell jQuery not to process the data
            contentType: false,
            beforeSend:function(){
                $("#informer").html("Script : started");
            },
            complete: function (data) {
                $("#informer").html("Script : Stoped");
                //$('.btn-success').prop('disabled', true);    
                $(e).html('started');
                //$('.btn-success').css('background-color','green');
            },
            timeout: 3000 
        });
    }

    function stopScript(e){ 
     
        data    =    new    FormData();
        data.append('command',  "shutdown");
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('command/execute'); ?>",
            data: data,
            processData: false, // tell jQuery not to process the data
            contentType: false,                    
            async: true,
            beforeSend:function(){
                    //$("#informer").html("Script : started");
                    //$('#jumbotron').empty();
            },
            complete: function (data) {
                    $("#informer").html("Script : Stoped");
                    //$("#jumbotron").html(data);
                    //$(e).html('started');
                    //$('.btn-success').css('background-color','');
                    $('.btn-success').text('Start');
                    //$('.btn-success').prop('disabled', false);
                   // $('.btn-danger').prop('disabled', true);
            },
            timeout: 3000 
        });
    }

</script>

