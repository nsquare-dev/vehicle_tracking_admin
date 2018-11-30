
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />

<link href="<?php echo base_url(); ?>assets/pages/css/profile.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            My Profile | Account
        </li>

    </ul>

</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile-sidebar">
            <!-- PORTLET MAIN -->
            <div class="portlet light profile-sidebar-portlet ">
                <!-- SIDEBAR USERPIC -->
                <div class="profile-userpic">
                    <?php if ($this->session->image != "") { ?>
                        <img src="<?php echo base_url("profile-img/"); ?><?php echo $this->session->image; ?>" class="img-responsive" alt="Profile image" onerror="this.src='<?php echo base_url("resource/default/default_profile.png"); ?>'">
                    <?php } else { ?>
                        <img src="<?php echo base_url("assets/pages/media/profile/profile_user.jpg"); ?>"  class="img-responsive" alt="Profile image">
                    <?php } ?>
                </div>
                <!-- END SIDEBAR USERPIC -->
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">  <?php echo $this->session->name; ?>  </div>
                    <div class="profile-usertitle-job ">  <?php echo $this->session->address; ?> </div>
                </div>
                <!-- END SIDEBAR USER TITLE --> 

            </div>
            <!-- END PORTLET MAIN -->

        </div>
        <!-- END BEGIN PROFILE SIDEBAR -->
        <!-- BEGIN PROFILE CONTENT -->
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">


                    <div class="portlet light ">
                        <div class="portlet-title tabbable-line"> 
                            <ul class="nav nav-tabs" style="float: left">
                                <li class="active">
                                    <a href="#tab_1_1" data-toggle="tab"><span class="caption-subject font-black-madison bold">My Profile</span></a>
                                </li>
                                <li>
                                    <a href="#tab_1_1_1" data-toggle="tab" style="display: none">Edit Profile</a>
                                </li>
                                <li>
                                    <a href="#tab_1_2" data-toggle="tab"><span class="caption-subject font-black-madison bold">Change Profile</span></a>
                                </li>
                                <li>
                                    <a href="#tab_1_3" data-toggle="tab"><span class="caption-subject font-black-madison bold">Change Password</span></a>
                                </li>

                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">

                                <!-- PERSONAL INFO TAB -->
                                <div class="tab-pane active" id="tab_1_1">
                                    <!--<form class="register-form" role="form" action=" <?php echo base_url(); ?>Admin/editprofile" method="post">-->
                                    <div class="form-group">
                                        <label class="control-label">Full Name</label>
                                        <input type="text" placeholder="Full name" name="name" value="<?php echo $this->session->name; ?> " class="form-control only_letter" readonly /> </div>
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <input type="email" placeholder="Email" name="email" value="<?php echo $this->session->email; ?> " class="form-control" readonly required/> </div>

                                    <div class="form-group">
                                        <label class="control-label">Mobile Number</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">+65</span>
                                            <input type="text" name="phone" placeholder="Enter mobile number" value="<?php echo $this->session->phone; ?> " class="form-control only_number" maxlength="8" readonly required>
                                            <span id="number_error" style="color:red"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Address</label><br>
                                        <input  name="address" class="form-control" type="text" value="<?php echo $this->session->address; ?>" placeholder="Enter a location" readonly>
                                        <span id="location_error" style="color:red"></span>
                                    </div>

                                    <div class="margiv-top-10"> 
                                        <a href="#tab_1_1_1" class="btn green" data-toggle="tab">Edit Profile</a> 
                                    </div>
                                    <!--</form>-->
                                </div>
                                <!--Edittable form-->
                                <div class="tab-pane" id="tab_1_1_1">
                                    <form class="register-form" role="form" action=" <?php echo base_url("Admin/editprofile"); ?>" method="post">
                                        <div class="form-group">
                                            <label class="control-label">Full Name</label>
                                            <input type="text"  name="name" value="<?php echo $this->session->name; ?>" class="form-control only_letter" required /> </div>
                                        <div class="form-group">
                                            <label class="control-label">Email</label>
                                            <input type="email" name="email" value="<?php echo $this->session->email; ?>" class="form-control" required/> </div>
                                        <div class="form-group">
                                            <label class="control-label">Mobile Number</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">+65</span>
                                                <input type="text" name="phone" value="<?php echo $this->session->phone; ?>" class="form-control only_number no_space" maxlength="8" required>
                                                <span id="number_error" style="color:red"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Address</label><br>
                                            <input id="searchInput" name="address" class="form-control" type="text" value="<?php echo $this->session->address; ?>">
                                            <span id="location_error" style="color:red"></span>
                                        </div>

                                        <div class="margiv-top-10">
                                            <input type="submit" value="Update profile " class="btn green"> 
                                            <a href="#tab_1_1" class="btn default" data-toggle="tab"> Cancel </a>
                                        </div>
                                    </form>
                                </div>
                                <!-- END PERSONAL INFO TAB -->
                                <!-- CHANGE AVATAR TAB -->
                                <div class="tab-pane" id="tab_1_2">
                                    <p>Update your profile photo. </p>
                                    <form  action=" <?php echo base_url(); ?>Admin/chnageprofile" method="post" role="form" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                                <div>
                                                    <span class="btn default btn-file">
                                                        <span class="fileinput-new"> Select image </span>
                                                        <span class="fileinput-exists"> Change </span>
                                                        <input type="file" name="userfile" required> </span>
                                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="margin-top-10">

                                            <input type="submit" value="Submit" class="btn green"> 
                                             
                                        </div>
                                    </form>
                                </div>
                                <!-- END CHANGE AVATAR TAB -->
                                <!-- CHANGE PASSWORD TAB -->
                                <div class="tab-pane" id="tab_1_3">
                                    <form  action="#" id="changepass" method="post">
                                        <div class="form-group"><span id="errormsg" style="color:red"></span></div>
                                        <div class="form-group">
                                            <label class="control-label">Old Password</label>
                                            <input type="password" name="password_old" class="form-control" /> 
                                            <span id="currentpassword_error" style="color:red"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">New Password</label>
                                            <input type="password" class="form-control" autocomplete="off" id="register_password"  name="password" />
                                            <span id="newpassword_error" style="color:red"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Confirm New Password</label>
                                            <input type="password" class="form-control" autocomplete="off"  name="rpassword"/> 
                                            <span id="cpassword_error" style="color:red"></span>
                                        </div>

                                        <div class="margin-top-10">
                                            <input type="button" value="Change Password" data-url="<?php echo base_url(); ?>Admin/changepassword" class="btn green changepass"> 
                                             
                                        </div>
                                    </form>
                                </div>
                                <!-- END CHANGE PASSWORD TAB -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PROFILE CONTENT -->
    </div>
</div>

<script src="<?php echo base_url("assets/global/plugins/jquery.min.js"); ?>" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url("assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js"); ?>" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="<?php echo base_url("assets/pages/scripts/profile.min.js"); ?>" type="text/javascript"></script>
<script type="text/javascript">
    function initAutocomplete() {
        var input = document.getElementById('searchInput');
        var searchBox = new google.maps.places.SearchBox(input);
    }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrrhfZecpWI6v4_IaRqoQFUIOzw5WGSCs&libraries=places&callback=initAutocomplete"></script>

<!-- END PAGE LEVEL SCRIPTS -->
