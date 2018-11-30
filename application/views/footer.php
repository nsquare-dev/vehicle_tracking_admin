</div>
<!-- END CONTENT BODY -->
</div>
<!-- END CONTENT -->

<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner"> 2017-<?=date("Y")?> &copy; <?=WEB_ADD?>
        <!--<a target="_blank" href="">Pune</a> &nbsp;|&nbsp;-->

    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
</div>

<div id="logout" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 class="modal-title"> Warning!</h3>
            </div>
            <div class="modal-body">                
                <div class="text-left">
                    <h4>Are you sure want to Logout?</h4>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" type="button" class="btn btn-success btn-lg confirmAction">Yes <span class="actionEvent"></span></a>
                <button type="button" data-dismiss="modal" class="btn btn-danger btn-lg">No</button>
            </div>
        </div>
    </div>
</div>
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->

<!-- BEGIN CORE PLUGINS -->

<script src="<?=base_url('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/js.cookie.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="<?=base_url('assets/global/plugins/moment.min.js'); ?>" type="text/javascript"></script>
<!--<script src="<?php //echo base_url('assets/global/plugins/jquery-repeater/jquery.repeater.js'); ?>" type="text/javascript"></script> comment-->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?=base_url('assets/global/scripts/datatable.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/datatables/datatables.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?=base_url('assets/global/scripts/app.min.js'); ?>" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?=base_url('assets/pages/scripts/table-datatables-managed.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/pages/scripts/components-date-time-pickers.min.js'); ?>" type="text/javascript"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->

<script src="<?=base_url('assets/layouts/layout/scripts/layout.min.js'); ?>" type="text/javascript"></script>
<script src="<?=base_url('assets/layouts/global/scripts/quick-sidebar.min.js'); ?>" type="text/javascript"></script>
<!--<script type="text/javascript" src="https://www.datejs.com/build/date.js"></script>-->

<!-- END THEME LAYOUT SCRIPTS -->
<script src="<?=base_url("assets/global/plugins/jquery-validation/js/jquery.validate.min.js"); ?>" type="text/javascript"></script>
<!--<script src="<?=base_url("assets/custom/js/form-validation.js"); ?>" type="text/javascript"></script>
<script src="<?=base_url("assets/apps/scripts/managapp.js"); ?>" type="text/javascript"></script>-->
<script>
    /* API method to get paging information */
    'use strict';
    var $ = jQuery;
    $.getScript("<?=base_url("assets/global/plugins/datatables.min.js"); ?>", function () {

        $('#example, #example2').DataTable({
            "paging": true,
            "ordering": true,
            "info": false
        });
    });
    /*    view popup data   */
    $(document).on('click', '.details', function () {
        var id = $(this).attr('data-id');        
        $.ajax({
            type: "POST",
            url: "<?=base_url("manageUser/view_detail/"); ?>" + id,
            dataType:    "json",
            success:    function    (data)
            {
                $('#scooterNumber').html(data.scooterNumber);
                $('#startLocation').html(data.startLocation);
                $('#endLocation').html(data.endLocation);
                if(data.startDate  !== null)    {
                    var  msec  = Date.parse(data.startDate.toString());
                    var  d    =    new Date(msec);
                    $('#startDate'). html( d.getDate( ) +  "- " + d.getMonth() +    "-" +    d.getFullYear());
                 }

                    if(data.startTime !==null)    {
                    $('#startTime').html(timeFormat(data.startTime));
                 }

                    if(data.endTime !==    null)    {
                    $('#endTime').html(timeFormat(data.endTime));
                }

                $('#totalBill').html('$'+data.totalBill +    ' SGD');
                $('#rating').html(data.rating);
                $('#comment').html(data.comment);
                $('#img1').attr('src',    data.image1);
                $('#img2').attr('src',    data.image2);
                $('#img3').attr('src',    data.image3);
                $('#img4').attr('src',    data.image4);
                $('#details').modal('show');
            }
        });
    });

    $(document).on('click',    '.maintananceDetails',    function    ()    {
        var    id    =    $(this).attr('data-id');
        // $('#details').modal('show');
        $.ajax({
            type:    "POST",
            url:    "<?=base_url("manageMaintenance/view_detail/"); ?>" + id,
            dataType: "json",
            success: function (data)
            {

                $('#scooterNumber').html(data.scooterNumber);
                $('#scooterLocation').html(data.scooterLocation);
                if (data.startDate !== null) {
                    var msec = Date.parse(data.startDate.toString());
                    var d = new Date(msec);
                    $('#startDate').html(d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear());
                }

                if (data.startTime !== null) {
                    $('#startTime').html(timeFormat(data.startTime));
                }
                if (data.endTime !== null) {
                    $('#endTime').html(timeFormat(data.endTime));
                }
                if (data.timeSpent !== null) {
                    $('#timeSpent').html(data.timeSpent);
                }
                $('#issueTitle').html(data.issueTitle);
                $('#comment').html(data.comment);
                $('#img1').attr('src', data.image1);
                $('#img2').attr('src', data.image2);
                $('#img3').attr('src', data.image3);
                $('#img4').attr('src', data.image4);
                $('#details').modal('show');
            }
        });
    });

    $(document).on('click', '.userList', function () {

        var scooteNumber = $(this).attr('data-scooteNumber');
        var userId = $(this).attr('data-userId');
        //alert(scooteNumber);
        var str = '';
        var j = 1;
        var k = 1;
        $.ajax({
            type: "POST",
            url: "<?=base_url("manageMaintenance/userList/"); ?>" + scooteNumber,
            dataType: "json",
            success: function (data)
            {
                if (data.status == 400) {
                    $('#erroractionModal span.actionEventTitle').html($(this).attr('data-action-title'));
                    $('#erroractionModal span.actionEventDesc').html(data.message);
                    $('#erroractionModal span.actionEvent').html($(this).attr('data-action'));
                    //$('a.confirmAction').attr('href', $(this).attr('data-url') + '/' + $(this).attr('data-id') + '/' + $(this).attr('data-value'));
                    $('#erroractionModal').modal('show');
                } else {
                    var userlist = data['userlist'];
                    var categorylist = data['categorylist'];
                    for (var i = 0; i < userlist.length; i++) {
                        if(userId!=userlist[i]['id']){
                        str += '<tr><td >' + j + '</td><td > ' + userlist[i]['userName'] + ' </td><td > ' + userlist[i]['mobile'] + ' </td><td > ' + userlist[i]['email'] + ' </td><td > ' + userlist[i]['count'] + ' </td><td ><a href="#assigntask" data-id=' + userlist[i]['id'] + ' data-scooteNumber=' + scooteNumber + ' data-toggle="modal" class="btn btn-sm btn-success selectCatgory" title="Assign"><i class="fa fa-retweet"></i> </a> </td></tr>'
                       }
                        j++;
                    }
                    for (var r = 0; r < categorylist.length; r++) {

                        $(".cat_" + k).html(categorylist[r]['categoryName']);
                        $(".category_" + k).attr('value', categorylist[r]['id']);
                        k++;
                    }
                    $("#userdata").html(str);
                    $('#userlist').modal('show');
                }
            }
        });
    });

    $(document).on('click', '.selectCatgory', function () {
        var id = $(this).attr('data-id');
        var scooteNumber = $(this).attr('data-scooteNumber');
        $("#userId").attr('value', id);
        $("#scooteNumber").attr('value', scooteNumber);
        $('#assigntask').modal('show');
    });

    $(document).on('click', '.scooterDetails', function () {
        var id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            url: "<?=base_url("manageScooter/view_detail/"); ?>" + id,
            dataType: "json",
            success: function (data)
            {
                $('#scooterNumber').html(data.scooterNumber);
                $('#startLocation').html(data.startLocation);
                $('#endLocation').html(data.endLocation);

                if (data.startDate !== null) {
                    var msec = Date.parse(data.startDate.toString());
                    var d = new Date(msec);
                    $('#startDate').html(d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear());
                }
                if (data.startTime !== null) {
                    $('#startTime').html(timeFormat(data.startTime));
                }
                if (data.endTime !== null) {
                    $('#endTime').html(timeFormat(data.endTime));
                }
                $('#details').modal('show');
            }
        });
    });
    $(document).on('click', '.reportDetails', function () {
        var id = $(this).attr('data-id');
        // $('#details').modal('show');
        $.ajax({
            type: "POST",
            url: "<?=base_url("ManageReport/getDetails/"); ?>"   + id,
            dataType: "json",
            success: function (data)
            {

                $('#scooterNumber').html(data.scooterNumber);
                $('#scooterLocation').html(data.scooterLocation);
                if (data.startDate !== null) {
                    var msec = Date.parse(data.startDate.toString());
                    var d = new Date(msec);
                    $('#startDate').html(d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear());
                }
                if (data.startTime !== null) {
                    $('#startTime').html(timeFormat(data.startTime));
                }
                if (data.endTime !== null) {
                    $('#endTime').html(timeFormat(data.endTime));
                }
                $('#issueTitle').html(data.issueTitle);
                $('#timeSpent').html(data.timeSpent);
                $('#comment').html(data.comment);
                $('#img1').attr('src', data.image1);
                $('#img2').attr('src', data.image2);
                $('#img3').attr('src', data.image3);
                $('#img4').attr('src', data.image4);
                $('#details').modal('show');
            }
        });
    });
    /*   form popup   */
    $(document).on('click', '.actionModal', function () {
        var desc = $(this).attr('data-action-desc');
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        var value = $(this).attr('data-value');
        var completeUrl = url + '/' + id + '/' + value;
        $.ajax({
            type: "POST",
            url: '<?=base_url('manageScooter/chkScooterStatus/'); ?>' + id,
            dataType: "json",
            success: function (data)
            {
                if (data.status == 400) {
                    $('#erroractionModal span.actionEventTitle').html($(this).attr('data-action-title'));
                    $('#erroractionModal span.actionEventDesc').html(data.message);
                    $('#erroractionModal span.actionEvent').html($(this).attr('data-action'));
                    // $('a.confirmAction').attr('href', $(this).attr('data-url') + '/' + $(this).attr('data-id') + '/' + $(this).attr('data-value'));
                    $('#erroractionModal').modal('show');
                } else {
                    $('#actionModal span.actionEventTitle').html($(this).attr('data-action-title'));
                    $('#actionModal span.actionEventDesc').html(desc);
                    $('#actionModal span.actionEvent').html($(this).attr('data-action'));
                    //$('a.confirmAction').attr('href', url + '/' + id + '/' + value);
                    $('.confirmAction').attr('onclick', "window.location.href = '"+ completeUrl+"'");
                    $('#actionModal').modal('show');
                }
            }
        });
        
    });

    $(document).on('click', '.actionModalConfirm', function () {
        var desc = $(this).attr('data-action-desc');
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        var value = $(this).attr('data-value');
        var completeUrl = url + '/' + id + '/' + value;
        $('#actionModal span.actionEventTitle').html($(this).attr('data-action-title'));
        $('#actionModal span.actionEventDesc').html(desc);
        $('#actionModal span.actionEvent').html($(this).attr('data-action'));
        $('.confirmAction').attr('onclick', "window.location.href = '"+ completeUrl+"'");
        $('#actionModal').modal('show');
    });

    $(document).on('click', '.actionModalRemove', function () {
        var desc = $(this).attr('data-action-desc');
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        var value = $(this).attr('data-value');
        $('#actionModal span.actionEventTitle').html($(this).attr('data-action-title'));
        $('#actionModal span.actionEventDesc').html(desc);
        $('#actionModal span.actionEvent').html($(this).attr('data-action'));
        $('a.confirmAction').attr('href', url + '/' + id + '/' + value);
        $('#actionModal').modal('show');
    });

    $(document).on('click', '.actionModal2', function () {
        $('#actionModal span.actionEventTitle').html($(this).attr('data-action-title'));
        $('#actionModal span.actionEventDesc').html($(this).attr('data-action-desc'));
        $('#actionModal span.actionEvent').html($(this).attr('data-action'));
        $('a.confirmAction').attr('href', $(this).attr('data-url') + '/' + $(this).attr('data-id') + '/' + $(this).attr('data-value'));
        $('#actionModal').modal('show');
    });

    $(document).on('click', '.userStatus', function () {
        var desc = $(this).attr('data-action-desc');
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        var value = $(this).attr('data-value');
        $.ajax({
            type: "POST",
            url: '<?=base_url(); ?>' + 'ManageUser/chkUserStatus/' + id,
            dataType: "json",
            success: function (data)
            {
                //alert(data.status);
                if (data.status == 400) {
                    $('#erroractionModal span.actionEventTitle').html($(this).attr('data-action-title'));
                    $('#erroractionModal span.actionEventDesc').html(data.message);
                    $('#erroractionModal span.actionEvent').html($(this).attr('data-action'));
                    //                    $('a.confirmAction').attr('href', $(this).attr('data-url') + '/' + $(this).attr('data-id') + '/' + $(this).attr('data-value'));
                    $('#erroractionModal').modal('show');
                } else {
                    $('#actionModal span.actionEventTitle').html($(this).attr('data-action-title'));
                    $('#actionModal span.actionEventDesc').html(desc);
                    $('#actionModal span.actionEvent').html($(this).attr('data-action'));
                    $('a.confirmAction').attr('href', url + '/' + id + '/' + value);
                    $('#actionModal').modal('show');
                }
            }
        });
    });

    $(document).on('click', '.logout', function () {
        $('#actionModal span.actionEventTitle').html($(this).attr('data-action-title'));
        $('#actionModal span.actionEventDesc').html($(this).attr('data-action-desc'));
        $('#actionModal span.actionEvent').html($(this).attr('data-action'));
        $('a.confirmAction').attr('href', $(this).attr('data-url'));
        $('#logout').modal('show');
    });

    $(document).on('click', '.deletemanuals', function () {
        $('#actionModal span.actionEventTitle').html($(this).attr('data-action-title'));
        $('#actionModal span.actionEventDesc').html($(this).attr('data-action-desc'));
        $('#actionModal span.actionEvent').html($(this).attr('data-action'));
        $('a.confirmAction').attr('href', $(this).attr('data-url'));
        $('#logout').modal('show');
    });

    function timeFormat(time24) {
        var tmpArr = time24.split(':'), time12;
        if (+tmpArr[0] == '12') {
            time12 = tmpArr[0] + ':' + tmpArr[1] + ' pm';
        } else {
            if (+tmpArr[0] == '00') {
                time12 = '12:' + tmpArr[1] + ' am';
            } else {
                if (+tmpArr[0] > 12) {
                    time12 = (+tmpArr[0] - 12) + ':' + tmpArr[1] + ' pm';
                } else {
                    time12 = (+tmpArr[0]) + ':' + tmpArr[1] + ' am';
                }
            }
        }
        return time12;
    }

    $(document).on('click', '#stopRide', function () {
        $('a.confirmAction').attr('href', $(this).attr('data-url'));
        $('#confirmStopRide').modal('show');
    });

    //ONLY NUMBERF
    $(document).on("keypress", '.only_number', function (e) {
        //e.preventDefault();   
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        } else if (this.value.length == 0 && e.which == 48) {
            return false;
        }
    });
    //ONLY LETTERS
    $(document).on("keypress", '.only_letter', function (e) {
        //e.preventDefault();   
        //if the letter is not digit then display error and don't type anything
        if ((e.which > 47 && e.which < 58) && (e.which != 32)) {
            return false;
        }
    });

    //space notalowed LETTERS
    $(document).on("keypress", '.no_space', function (e) {
//       $('input.nospace').keydown(function(e) {
        if (e.keyCode == 32) {
            return false;
        }
        //});
    });

    //SUBMIT FORM
    $(document).on("click", '.addscooter', function (e) {
        var durl = $(this).attr('data-url');
        var $form = $("#addscooter").serialize();
        $.ajax({
            type: "POST",
            url: durl,
            data: $("#addscooter").serialize(),
            dataType: "json",
            beforeSend: function (xhr) {
                $(".addscooter").addClass("disabled");
            },
            success: function (data) {
                if (data.status == 400) {
                    $('#errormsg').html(data.message);
                    $('#errormsg').show();
                    setTimeout(function () {
                        $('#errormsg').hide();
                    }, 3000);
                } else {

                    $("#successmsg").html(data.message);
                    $('#successmsg').show();
                    setTimeout(function () {
                        $('.modal').modal('hide');
                        $('#successmsg').hide();
                        window.location.reload();
                    }, 3000);

                    // return true;
                }
            },
            complete: function (xhr) {
                $(".addscooter").removeClass("disabled");
            },
            error: function (response)
            {
                var errors = $.parseJSON(response.responseText);
                resetModalFormErrors();
                associate_errors(errors, $form);
            }
        });
    });
    $(document).on("click", '.adduser', function (e) {

        var durl = $(this).attr('data-url');
        $.ajax({
            type: "POST",
            url: durl,
            data: $("#adduser").serialize(),
            dataType: "json",
            success: function (data) {
                if (data.status == 400) {
                    $('#errormsg').html(data.message);
                    $('#errormsg').show();
                    setTimeout(function () {
                        $('#errormsg').hide();
                    }, 3000);
                } else {

                    $("#successmsg").html(data.message);
                    $('#successmsg').show();
                    setTimeout(function () {
                        $('#successmsg').hide();
                        window.location.reload();
                    }, 3000);

                    // return true;
                }
            },
            error: function (response)
            {
                var errors = $.parseJSON(response.responseText);
                resetModalFormErrors();
                associate_errors(errors, $form);
            }
        });
    });

    $(document).on("click", '.addparking', function (e) {

        var durl = $(this).attr('data-url');
        $.ajax({
            type: "POST",
            url: durl,
            data: $("#addparking").serialize(),
            dataType: "json",
            beforeSend: function (xhr) {
                $(".addparking").attr('disabled', true);
            },
            success: function (data) {
                if (data.status == 400) {
                    $('#errormsg').html(data.message);
                    $('#errormsg').show();
                    setTimeout(function () {
                        $('#errormsg').hide();
                    }, 3000);
                } else {

                    $("#successmsg").html(data.message);
                    $('#successmsg').show();
                    setTimeout(function () {
                        $('#successmsg').hide();
                        $(".modal").modal("hide");
                        window.location.reload();
                    }, 3000);

                    // return true;
                }
            },
            error: function (response)
            {
                var errors = $.parseJSON(response.responseText);
                resetModalFormErrors();
                associate_errors(errors, $form);
            },
            complete: function (data) {
                $(".addparking").attr('disabled', false);
            }
        });
    });
    $(document).on("click", '.changepass', function (e) {

        var durl = $(this).attr('data-url');
        $.ajax({
            type: "POST",
            url: durl,
            data: $("#changepass").serialize(),
            dataType: "json",
            success: function (data) {
                if (data.status == 400) {
                    if (data.type == 'oldpassword') {
                        $('#currentpassword_error').html(data.message);
                        return false;
                    } else if (data.type == 'newpassword') {
                        $('#newpassword_error').html(data.message);
                        return false;
                    } else if (data.type == 'rpassword') {
                        $('#cpassword_error').html(data.message);
                        return false;
                    } else {
                        $('#errormsg').html(data.message);
                        return false;
                    }
                } else {
                    window.location.href = 'profile/#tab_1_3';
                }
            },
            error: function (response)
            {
                var errors = $.parseJSON(response.responseText);
                resetModalFormErrors();
                associate_errors(errors, $form);
            }
        });
    });

    $(document).ready(function () {
        
        
        /*$('.datetimepicker').datetimepicker({
            locale: 'ru'
        });*/
        
        
        $('#errormsg').hide();
        $('#successmsg').hide();
        $('#errormsg_edit').hide();
        $('#successmsg_edit').hide();
        
        setTimeout(function () {
            $('.alert').hide();
        }, 3000);
                        
        $('#formsubmit').on('submit', function (e) {
            var url = $('#url').val();
            e.preventDefault();
            $.ajax({
                url: url,
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (data)
                {
                    if (data.status == 400) {
                        $('#errormsg').html(data.message);
                        $('#errormsg').show();
                        setTimeout(function () {
                            $('#errormsg').hide();
                        }, 3000);
                    } else {

                        $("#successmsg").html(data.message);
                        $('#successmsg').show();
                        setTimeout(function () {
                            $('#successmsg').hide();
                            window.location.reload();
                        }, 3000);

                        // return true;
                    }
                }
            });
        });

        $('.frmUpdateRideCharges').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: "<?=base_url("AppManagement/updateRideAmount"); ?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (data)
                {
                    if (data.status == 400)
                    {
                        if (data.type == 'name') {
                            return false;
                        } else {
                            $('#errormsg').html(data.message);
                            return false;
                        }
                    } else {
                        window.location.reload();
                    }
                }
            });
        });
        
        $('.formdatasubmit').on('submit', function (e) {            
            e.preventDefault();
            $.ajax({
                url: "<?=base_url("AppManagement/updateDepositAmount"); ?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (data)
                {
                    if (data.status == 400)
                    {
                        if (data.type == 'name') {
                            return false;
                        } else {
                            $('#errormsg').html(data.message);
                            return false;
                        }
                    } else {
                        window.location.reload();
                    }
                }
            });
        });

        $('#frmsubmitpanelty').on('submit', function (e) {             
            e.preventDefault();
            $.ajax({
                url: "<?=base_url("AppManagement/updatePaneltyCharges"); ?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                beforeSend:function(xhr){
                    $(".btn-success").attr('disabled', true);
                },
                success: function (data)
                {
                    if (data.status == 400) {
                        if (data.type == 'name') {
                            return false;
                        } else {                            
                            $('#errormsg').removeClass("alert-success").addClass("alert-danger").html(data.message);
                            $('#errormsg').show();
                            return false;
                        }
                    } else {
                        $('#errormsg').removeClass("alert-danger").addClass("alert-success").html(data.message);
                        $('#errormsg').show();
                        setTimeout(function () {
                            $('#errormsg').hide();
                            $('.modal').modal("hide");
                            window.location.reload();
                        }, 3000);
                    }
                },
                complete:function(xhr){
                    $(".btn-success").attr('disabled', false);
                }
            });
        });
        
        $('#frmtopup').on('submit', function (e) {             
            e.preventDefault();
            $.ajax({
                url: $(this).attr("action"),
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                beforeSend:function(xhr){
                    $(".btn-success").attr('disabled', true);
                },
                success: function (data)
                {
                    if (data.status == 400) {
                        if (data.type == 'name') {
                            return false;
                        } else {                            
                            $('#errormsg_topup').removeClass("alert-success").addClass("alert-danger").html(data.message);
                            $('#errormsg_topup').show();
                            return false;
                        }
                    } else {
                        $('#errormsg_topup').removeClass("alert-danger").addClass("alert-success").html(data.message);
                        $('#errormsg_topup').show();
                        setTimeout(function () {
                            $('#errormsg_topup').hide();
                            $('.modal').modal("hide");
                            window.location.reload();
                        }, 3000);
                    }
                },
                complete:function(xhr){
                    $(".btn-success").attr('disabled', false);
                }
            });
        });

        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector             
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = '<div class="tt"><div class="row"> <div class="col-md-6"> <div class="form-group"> <input type="hidden" name="id[]" class="form-control only_number" ><input type="text" name="price[]" class="form-control only_number" placeholder="Enter  Plan Amount" required><span id="parkingName _error " sty le="co lor:red"></span> </div></div><div class="col-md-6"><div class="form-group"><input type="text" name="bonus[] " class ="form-control only_number" placeholder="Enter bounus amount "></div></div></div><a href="javascript:void(0);" class="btn btn-link pull-right remove_button" title="Remove field"><i class="fa fa-minus"></i> Remove</a><br></div>'; //New input field html 
        var x = 1; //Initial field counter is 1
        $(addButton).click(function () { //O  nc e a dd button is clicked
            if (x < maxField) { //Check maximum number of input fields
                x++; //Increment fie ld   count er
                $(wrapper).append(fieldHTML); // Add field html
            }
        });

        $(wrapper).on('click', '.remove_button', function (e) { // Once  remove  butto n  is c licked
            e.preventDefault();
            $(this).parent('.tt').remove(); //Remove field html
            x--; //Dec rement field counter
        });

        $('.formSubmit').on('click ', function (e) {

            var durl = $("#cnfForm").attr("action");
            $.ajax({
                type: "POST",
                url: durl,
                data: $("#cnfForm").serialize(),
                beforeSend: function () {
                    $("#errormsg_alert").removeClass('alert-danger').addClass('alert-success');
                    $("#errormsg_alert").html(" Please wait...");
                    $("#errormsg_alert").fadeIn(500);
                    $(".formSubmit").attr('disabled', true);
                },
                success: function (data) {
                    var obj = jQuery.parseJSON(data);
                    if (obj.status == 400) {
                        $('#errormsg_alert').removeClass("alert-success").addClass("alert-danger");
                        $('#errormsg_a lert').html(obj.message);
                        $('#errormsg_alert').fadeIn(100).fadeOut(3000);
                        return false;
                    } else {
                        $('#errormsg_alert').html(obj.message);
                        $('#errormsg_alert').removeClass("alert-danger").addClass("alert-success");
                        $('#errormsg_ alert').fadeIn(100).fadeOut(3000);
                        setTimeout(function () {
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function (response)
                {
                    $('#errormsg_alert').fadeIn(100).fadeOut(3000);
                    $('#errormsg_alert').removeClass("alert-success").addClass();
                    $('#errormsg_alert').html("Something went wrong. Please try later!");
                },
                complete: function (data) {
                    $(".formSubmit").attr('disabled', false);
                },
            });
        });


        $('#btn_cmd').click(function () {
            $('#addCommand').find("input[type=text], textarea").val("");
            $('#errormsg').html('');
            $(".modal-title").html("<i class='fa fa-plus-square'></i> New Command");
            $('#txt_key').attr('readonly', false);
            $('#txt_syntax').attr('readonly', false);
            $('#txt_example').attr('readonly', false);
        });
        $('.btn_cmd').click(function () {
            $(".modal-title").html("<i class='fa fa-pencil-square'></i> Edit Command");
            var id = $(this).attr('data-value');
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
                            $('#id').val(data.id);
                            $('#txt_title').val(data.title);
                            $('#txt_description').val(data.description);
                            $('#txt_command').val(data.command);
                            $('#txt_syntax').val(data.syntax);
                            $('#txt_example').val(data.example);
                            $('#txt_key').val(data.code);
                            $('#txt_syntax').attr('readonly', 'true');
                            $('#txt_example').attr('readonly', 'true');
                            $('#txt_key').attr('readonly', 'true');
                            $('#addCommand').modal();
                        }
                    },
                    error: function () {
                        alert("Error Handling Here !");
                        return false;
                    }
                });
            }
        });

        $(".edit-scooter").click(function () {
            var scooterDeatails = JSON.parse($(this).attr('data-value'));
            $("#edit_scooteNumber").val(scooterDeatails.scooterNumber);
            $("#edit_tarckId").val(scooterDeatails.tarckId);
            $("#edit_location").val(scooterDeatails.location);
            $("#edit_id").val(scooterDeatails.id);
            $("#editModal").modal("show");
        });
        
        $("#editScooter").submit(function (event) {
            event.preventDefault();
            var form = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "<?=base_url("manageScooter/editScooter")?>",
                data: form,
                dataType: "json",
                beforeSend: function (xhr) {
                    $(".editScooterSubmit").addClass("disabled");
                },
                success: function (data) {
                    if (data.status == 400) {
                        $('#errormsg_edit').html(data.message);
                        $('#errormsg_edit').show();                        
                        setTimeout(function () {
                            $('#errormsg_edit').hide();
                        }, 3000);
                    } else { 
                        $("#successmsg_edit").html(data.message);
                        $('#successmsg_edit').show();
                        setTimeout(function () {
                            $('.modal').modal('hide');
                            $('#successmsg_edit').hide();
                            window.location.reload();
                        }, 3000);

                        // return true;
                    }
                },
                complete: function (xhr) {
                    $(".editScooterSubmit").removeClass("disabled");
                },
                error: function (response)
                {
                    var errors = $.parseJSON(response.responseText);
                    resetModalFormErrors();
                    associate_errors(errors, $form);
                }
            });
        });
        //Update configuration
        $("#edit_config").click(function () {
            var configData = JSON.parse($(this).attr('data-value'));
            $("#field_confId").val(configData.id);
            $("#field_deposite_amount").val(configData.depositAmount);
            $("#field_radious").val(configData.scooterRadius);
            $("#field_cancel_min").val(configData.scooterCancelTime);
            $("#field_charges_per_min").val(configData.scooterPerMinChrages);
            //$("#field_base_fare").val(configData.scooterBaseFair);
            $("#field_own_refferal_amount").val(configData.ownRefferralAmount);
            $("#field_other_refferal_amount").val(configData.anotherRefferralAmount);            
            $("#updateConfig").modal("show");
        });
        
        $("#frm_config").submit(function (event) {
            event.preventDefault();
            var form = $(this).serialize();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: form,
                dataType: "json",
                beforeSend: function (xhr) {
                    $(".submitConfig").addClass("disabled");
                },
                success: function (data) {
                    if (data.status == 400) {
                        $('#errormsg_edit').html(data.message);
                        $('#errormsg_edit').show();                        
                        setTimeout(function () {
                            $('#errormsg_edit').hide();
                        }, 3000);
                    } else { 
                        $("#successmsg_edit").html(data.message);
                        $('#successmsg_edit').show();
                        setTimeout(function () {
                            $('.modal').modal('hide');
                            $('#successmsg_edit').hide();
                            window.location.reload();
                        }, 3000);

                        // return true;
                    }
                },
                complete: function (xhr) {
                    $(".submitConfig").removeClass("disabled");
                },
                error: function (response)
                {
                    var errors = $.parseJSON(response.responseText);
                    resetModalFormErrors();
                    associate_errors(errors, $form);
                }
            });
        });
        
        $(".edit-parking").click(function(){
            var editParking = JSON.parse($(this).attr('data-value'));
            $("#edit_id").val(editParking.id);
            $("#edit_parkingName").val(editParking.parkingName);
            $("#edit_parkingLocation").val(editParking.parkingLocation);
            $("#editModal").modal("show");
        });
        
        $("#editParking").submit(function(event){
            event.preventDefault();
            var form = $(this).serialize();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: form,
                dataType: "json",
                beforeSend: function (xhr) {
                    $(".btn-success").addClass("disabled");
                },
                success: function (data) {
                    if (data.status == 400) {
                        $('#errormsg_edit').html(data.message);
                        $('#errormsg_edit').show();                        
                        setTimeout(function () {
                            $('#errormsg_edit').hide();
                        }, 3000);
                    } else { 
                        $("#successmsg_edit").html(data.message);
                        $('#successmsg_edit').show();
                        setTimeout(function () {
                            $('.modal').modal('hide');
                            $('#successmsg_edit').hide();
                            window.location.reload();
                        }, 3000);

                        // return true;
                    }
                },
                complete: function (xhr) {
                    $(".btn-success").removeClass("disabled");
                },
                error: function (response)
                {
                    var errors = $.parseJSON(response.responseText);
                    resetModalFormErrors();
                    associate_errors(errors, $form);
                }
            });
        });
        
        
        $(".edit-restricted-parking").click(function(){
            var editParking = JSON.parse($(this).attr('data-value'));
            $("#edit_id").val(editParking.id);
            $("#edit_parkingName").val(editParking.name);
            $("#edit_parkingLocation").val(editParking.location);
            $("#editArea").modal("show");
        });
        
        $("#editRestrictedParking").submit(function(event){
            event.preventDefault();
            var form = $(this).serialize();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: form,
                dataType: "json",
                beforeSend: function (xhr) {
                    $(".btn-success").addClass("disabled");
                },
                success: function (data) {
                    if (data.status == 400) {
                        $('#errormsg_edit').html(data.message);
                        $('#errormsg_edit').show();                        
                        setTimeout(function () {
                            $('#errormsg_edit').hide();
                        }, 3000);
                    } else { 
                        $("#successmsg_edit").html(data.message);
                        $('#successmsg_edit').show();
                        setTimeout(function () {
                            $('.modal').modal('hide');
                            $('#successmsg_edit').hide();
                            window.location.reload();
                        }, 3000);

                        // return true;
                    }
                },
                complete: function (xhr) {
                    $(".btn-success").removeClass("disabled");
                },
                error: function (response)
                {
                    var errors = $.parseJSON(response.responseText);
                    resetModalFormErrors();
                    associate_errors(errors, $form);
                }
            });
        });
        
        
        //submit add Proomo code form
        $("#addPromoCode").submit(function(event, data){
            event.preventDefault();
            
            var field_image = document.getElementById("field_image");
            // the file is the first element in the files property
            var file_image = field_image.files[0];
            var field_banner = document.getElementById("field_banner");
            // the file is the first element in the files property
            var file_banner = field_banner.files[0];
            var form = $('form')[0];
            // Serialize the form data
            var formData = new FormData(form);
            // You should sterilise the file names
            formData.append("field_image", file_image);
            formData.append("field_banner", file_banner);

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                dataType: "json",
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                beforeSend: function (xhr) {
                    $(".btn-success").addClass("disabled");
                },
                success: function (data) {
                    if (data.status == 400) {
                        $('#errormsg').html(data.message);
                        $('#errormsg').show();                        
                        setTimeout(function () {
                            $('#errormsg').hide();
                        }, 3000);
                    } else { 
                        $("#successmsg").html(data.message);
                        $('#successmsg').show();
                        setTimeout(function () {
                            $('.modal').modal('hide');
                            $('#successmsg').hide();
                            window.location.reload();
                        }, 3000);

                        // return true;
                    }
                },
                complete: function (xhr) {
                    $(".btn-success").removeClass("disabled");
                },
                error: function (response)
                {
                    var errors = $.parseJSON(response.responseText);
                    resetModalFormErrors();
                    associate_errors(errors, $form);
                }
            });
        });
        
        
        $(".edit-promo").click(function () {
            var promoDetails = JSON.parse($(this).attr('data-value'));
            $("#field_edit_title").val(promoDetails.offerTitle);
            $("#field_edit_desc").val(promoDetails.offerDesc);
            $("#field_edit_price").val(promoDetails.offerPrice);
            $("#field_edit_code").val(promoDetails.promoCode);
            $("#field_edit_startDate").val(promoDetails.startDate);
            $("#field_edit_endDate").val(promoDetails.endDate);
            $("#field_edit_image_src").attr("src",promoDetails.offerImage);
            $("#field_edit_banner_src").attr("src",promoDetails.offerBannerImage);
            $("#edit_id").val(promoDetails.id);
            $("#editModal").modal("show");
        });
        
        $("#editPromoCode").submit(function(event){
            event.preventDefault();
            var form = $(this).serialize();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: form,
                dataType: "json",
                beforeSend: function (xhr) {
                    $(".btn-success").addClass("disabled");
                },
                success: function (data) {
                    if (data.status == 400) {
                        $('#errormsg_edit').html(data.message);
                        $('#errormsg_edit').show();                        
                        setTimeout(function () {
                            $('#errormsg_edit').hide();
                        }, 3000);
                    } else { 
                        $("#successmsg_edit").html(data.message);
                        $('#successmsg_edit').show();
                        setTimeout(function () {
                            $('.modal').modal('hide');
                            $('#successmsg_edit').hide();
                            window.location.reload();
                        }, 3000);

                        // return true;
                    }
                },
                complete: function (xhr) {
                    $(".btn-success").removeClass("disabled");
                },
                error: function (response)
                {
                    var errors = $.parseJSON(response.responseText);
                    resetModalFormErrors();
                    associate_errors(errors, $form);
                }
            });
        });
         
        $('.modal').on('hidden.bs.modal', function (e) {
            $(this).find("input,textarea,select")
                 .val('')
                 .end();
              //.find("input[type=checkbox], input[type=radio]")
                 //.prop("checked", "")
                 //.end();
          })
    });
    
    $(document).on('click', '.getdata', function () {

        var model = $(this).attr('model-name');
        var url = $(this).attr('data-url');
        $.ajax({
            type: "POST",
            url: "<?=base_url(); ?>" + url,
            dataType: "json",
            success: function (data)
            {
                if (model == 'topup') {
                    // $(".inputfield").remove();
                    $(".inputfield").empty();
                    for (var i = 0; i < data.length; i++) {
                        $('.inputfield').append('<input type="hidden" name="id[]" value="' + data[i]['id'] + '"><div class="tt"><div class="row"> <div class="col-md-6"> <div class="form-group"> <input type="text" name="price[]" class="form-control only_number" value="' + data[i]['price'] + '" placeholder="Enter Plan Amount" required><span id="parkingName_error" style="color:red"></span> </div></div><div class="col-md-6"><div class="form-group"><input type="text" name="bonus[]" value="' + data[i]['bonus'] + '" class="form-control only_number" placeholder="Enter bounus amount"></div></div></div><a href="javascript:void(0);" class="btn btn-link pull-right  remove_button" title="Remove field"><i class="fa fa-minus"></i> Remove</a><br></div>');
                    }
                    $('#' + model).modal('show');
                } else if (model == 'penaltyCharges') {
                    $('#' + model + ' input[name=illegalParking]').val(data['illgelParking']);
                    $('#' + model + ' input[name=limitViolation]').val(data['limitViolation']);
                    $('#' + model + ' input[name=rashDriving]').val(data['rashDriving']);
                    $('#' + model).modal('show');
                } else if (model == 'rideAmount'){
                    $('#' + model + ' input[name=rideAmount]').val(data['scooterBaseFair']);
                    $('#' + model).modal('show');
                }else {
                    $('#' + model + ' input[name=depositAmount]').val(data['depositAmount']);
                    $('#' + model).modal('show');
                }
            }
        });
    });

    $(document).on('click', '#fetchGloablConf', function () {
        var model = $(this).attr('model-name');
        $('#' + model).modal('show');
    });

<!-- Read Script --> 
    getNotifications();
    setInterval(function () {
        getNotifications()
    }, 2000);
    var old_not_cnt = 0;
    function getNotifications() {
        $.ajax({
            type: "GET",
            url: "<?= base_url('admin/getLatestNotifications'); ?>",
            processData: false, // tell jQuery not to process the data
            contentType: false,
            beforeSend: function () {
            },
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                if (old_not_cnt != obj.tota_count) {
                    $("#noti_cntr").html(obj.tota_count);
                    $("#noti_cnt").html(obj.tota_count + " pending");
                    var html_content = "";
                    $.each(obj.data, function (key, val) {
                        html_content += '<li>';
                        html_content += '<a href="javascript:void(0);">';
                        html_content += '<span class="time">' + val.createdOn + '</span>';
                        html_content += '<span class="details">';
                        html_content += '<span class="label label-sm label-icon label-danger">';
                        html_content += '<i class="fa fa-bell-o"></i>';
                        html_content += '</span>' + val.message + '</span>';
                        html_content += '</a>';
                        html_content += '</li>';
                    });
                    $("#noti_content").html(html_content);
                    old_not_cnt = obj.tota_count;
                }
            },
            complete: function () {
                // Schedule the next request when the current one's complete 
            },
            timeout: 3000
        });
    }
    
    
</script>

</body>