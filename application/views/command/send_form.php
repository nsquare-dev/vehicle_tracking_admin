<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>  
                <li>
                    <span>Configure Devices</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption  font-green">
                    <i class="icon-settings"></i>
                    <span class="bold uppercase">Configure Devices</span>
                </div> 
            </div>

            <div class="container-fluid text-center">  

                <div class="col-md-6">

                    <!-- BEGIN BLOCK BUTTONS PORTLET-->
                    <div class="portlet light bordered">
                        <div class="caption">
                            <span class="caption-subject font-green bold uppercase">Scooter Global Options</span>
                        </div>
                        <div class="portlet-body"> 
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th  class="text-center">Speed Limit </th>
                                        <th  class="text-center">Battery Voltage</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td> <?= ($globalCnf['speedLimit']) ? $globalCnf['speedLimit'] : 0; ?> Km/Hr </td>
                                        <td> <?= ($globalCnf['batteryVoltage']) ? $globalCnf['batteryVoltage'] : 0; ?> V</td>

                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <a href="#" id="fetchGloablConf" data-toggle="modal" model-name="defaultModel" data-url="command/getConfigAll" class="btn sbold btn-success btn-lg" >
                                    <i class="fa fa-pencil-square"></i> Edit
                                </a>
                            </div>

                        </div>
                    </div>
                    <!-- END BLOCK BUTTONS PORTLET-->

                    <div class="portlet light bordered">

                        <div class="caption">
                            <span class="caption-subject font-green bold uppercase">Single Scooter Setting</span>
                        </div> 

                        <?php
                        $attributes = array('class' => 'form-horizontal', 'id' => 'myform', 'method' => 'post');
                        echo form_open('command/sendCMD', $attributes);
                        ?>

                        <div class="form-group">
                            <?php
                            $attributes = array(
                                'class' => 'control-label col-sm-3',
                            );
                            echo form_label('Select Action :', 'field_cmd', $attributes);
                            ?>
                            <div class="col-sm-9">
                                <?php
                                $js = 'id="field_cmd" class="form-control "';
                                echo form_dropdown('field_cmd', $command_list, '', $js);
                                ?> 
                            </div> 
                        </div> 


                        <div class="form-group">       
                            <?php
                            $attributes = array(
                                'class' => 'control-label col-sm-3',
                                'id' => 'label_syntax'
                            );
                            echo form_label('Syntax :', 'label_syntax', $attributes);
                            ?>
                            <div class="col-sm-9">
                                <div id="div_syntax" class="text-left"></div>
                            </div>
                        </div>
                        <div class="form-group">       
                            <?php
                            $attributes = array(
                                'class' => 'control-label col-sm-3',
                                'id' => 'label_example'
                            );
                            echo form_label('Example :', 'label_example', $attributes);
                            ?>
                            <div class="col-sm-9">
                                <div id="div_example" class="text-left"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?php
                            $attributes = array(
                                'class' => 'control-label col-sm-3',
                            );
                            echo form_label('Edit Value :', 'field_action', $attributes);
                            ?>
                            <div class="col-sm-9">
                                <?php
                                $data = array(
                                    'name' => 'field_action',
                                    'id' => 'field_action',
                                    'class' => 'form-control',
                                        //'required'      => true,
                                );

                                echo form_input($data);
                                ?> 
                            </div> 
                        </div>

                        <div class="form-group">
                            <?php
                            $attributes = array(
                                'class' => 'control-label col-sm-3',
                            );
                            echo form_label('Select Scooter :', 'field_tracker', $attributes);
                            ?>
                            <div class="col-sm-9">
                                <?php
                                $js = 'id="field_tracker" class="form-control"';
                                echo form_dropdown('field_tracker', $trackers, '', $js);
                                ?> 
                            </div> 
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-offset-2 col-sm-9 text-center">
                                <button type="submit" id="submit" class="btn btn-success sbold btn-lg"> <i class="fa fa-save"></i> Submit</button>
                            </div>
                        </div>
                        </form> 
                        <div class="alert" id="alert-box">

                        </div>
                    </div>

                </div>
                <div class="col-md-6">
                    <h3 class="text-left"></h3>

                    <div class="portlet light bordered">

                        <div class="caption">
                            <span class="caption-subject font-green bold uppercase">
                                Responses: <small>Refreshing every 2 sec</small>
                            </span>
                        </div> 
                    </div>
                    <div class="form-group">
                        <textarea cols="40" rows="20" id="res_informer" class="form-control" readonly="true"></textarea>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>

<!--add default model-->
<div id="defaultModel" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="alert" id="errormsg_alert"></div>

            <?php
            $attributes = array("class" => "form-horizontal", "name" => "cnfForm", "id" => "cnfForm");
            echo form_open_multipart('command/updateConfig', $attributes);
            ?>

            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title"><i class="fa fa-gear"></i> Update Global Configuration</h4>
            </div> 
            <div class="modal-body">
                <div class="form-group">

                    <?php
                    $data = array(
                        'type' => 'hidden',
                        'name' => 'field_confId',
                        'id' => 'field_confId',
                        'value' => encode($globalCnf['id']),
                    );

                    echo form_input($data);
                    ?>
                    <?php
                    $attributes = array(
                        'class' => 'control-label col-sm-4',
                        'id' => 'label_speed'
                    );
                    echo form_label('Max Speed (Km/Hr):', 'label_speed', $attributes);
                    ?>
                    <div class="col-sm-8">
                        <?php
                        $data = array(
                            'type' => 'text',
                            'name' => 'field_speed',
                            'id' => 'field_speed',
                            'value' => ($globalCnf['speedLimit']) ? $globalCnf['speedLimit'] : 0,
                            'class' => 'form-control',
                            'max' => 999,
                            'min' => 00,
                            'required' => true,
                        );

                        echo form_input($data);
                        ?>
                    </div>
                </div>
                <div class="form-group">

                    <?php
                    $attributes = array(
                        'class' => 'control-label col-sm-4',
                        'id' => 'label_voltage'
                    );
                    echo form_label('Min Voltage (V):', 'label_voltage', $attributes);
                    ?>
                    <div class="col-sm-8">
                        <?php
                        $data = array(
                            'type' => 'text',
                            'name' => 'field_voltage',
                            'id' => 'field_voltage',
                            'value' => ($globalCnf['batteryVoltage']) ? $globalCnf['batteryVoltage'] : 0,
                            'class' => 'form-control',
                            'max' => 100.00,
                            'min' => 00.00,
                            'required' => true,
                        );

                        echo form_input($data);
                        ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success formSubmit">Update</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger">Close</button>

            </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#alert-box").hide();
        $('#label_syntax').hide();
        $('#label_example').hide();


        setInterval(function () {
            callInterval()
        }, 2000);

        function callInterval() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('command/readResponce'); ?>",
                processData: false, // tell jQuery not to process the data
                contentType: false,
                beforeSend: function () {
                },
                success: function (data) {
                    $("#res_informer").prepend(data + "\n");
                },
                complete: function () {
                    // Schedule the next request when the current one's complete 
                },
                timeout: 3000
            });
        }

        $("#myform").submit(function (event) {
            var x = $(this).serializeArray();
            var formData = new FormData();
            $.each(x, function (i, field) {
                formData.append(field.name, field.value);
            });
            $.ajax({
                type: "post",
                url: "<?= base_url('command/sendCMD'); ?>",
                data: formData,
                enctype: 'multipart/form-data',
                processData: false, // tell jQuery not to process the data
                contentType: false,
                beforeSend: function () {
                    $("#alert-box").removeClass('alert-danger').addClass('alert-success');
                    $("#alert-box").html("Please wait...");
                    $("#alert-box").fadeIn(500);
                    $("#submit").attr('disabled', true);
                },
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status == 200) {
                        $("#alert-box").removeClass('alert-danger').addClass('alert-success');
                    } else {
                        $("#alert-box").removeClass('alert-success').addClass('alert-danger');
                    }
                    if ($.isEmptyObject(obj.error)) {
                        $("#alert-box").html(obj.message);
                    } else {
                        $("#alert-box").html(obj.error);
                    }

                    $("#alert-box").fadeIn(500).fadeOut(5000);

                },
                complete: function (data) {
                    $("#field_cmd").val('');
                    $('#label_syntax').html('');
                    $('#label_example').html('');
                    $("#submit").attr('disabled', false);
                },
                timeout: 15000
            });
            event.preventDefault();
        });


        $("#field_cmd").change(function () {

            var id = $(this).val();
            if (id)
            {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('command/edit'); ?>",
                    data: {'id': id},
                    dataType: 'json',
                    success: function (data) {
                        if (data)
                        {
                            $('#div_syntax').html(data.syntax);
                            $('#div_example').html(data.example);
                            $('#field_action').val(data.command);
                            $('#label_syntax').show();
                            $('#label_example').show()
                        }
                    },
                    error: function () {
                        alert("Error Handling Here !");
                        return false;
                    }
                });
            } else {
                $('#label_syntax').hide();
                $('#label_example').hide();
                $('#div_syntax').html('');
                $('#div_example').html('');
            }

        });


    });

<!-- End Read Script-->

</script>