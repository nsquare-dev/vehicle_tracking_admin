                  

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-eye "></i>
                    <span class="caption-subject  bold uppercase">Staff Details</span>
                </div>

            </div>
            <div class="row">

                <div class="col-md-4 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">


                        <div class="portlet-body ">
                            <div class="profile-sidebar-portlet">
                                <!-- SIDEBAR USERPIC -->
                                <div class="profile-userpic">
                                    <img src="<?php echo $userDetails['profileImage']; ?>" class="img-responsive" style="height:100px; width:100px"  alt=""> </div>

                                <div class="profile-usertitle">
                                    <div class="profile-usertitle-name"> <?php echo $userDetails['userName'];?>  </div>
                                </div>

                            </div>
                            <!-- END PORTLET MAIN -->
                            <!-- PORTLET MAIN -->

                            <!-- STAT -->
                           
                <table id="example" class="table table-striped table-bordered table-hover table-checkable">

                                <tbody>
                                    <tr>

                                        <td class="numeric"> Email </td>
                                        <td class="numeric"><?php echo $userDetails['email'];?> </td>

                                    </tr>
                                    <tr>

                                        <td class="numeric"> Mobile </td>
                                        <td class="numeric"><?php echo $userDetails['mobile'];?> </td>

                                    </tr>
                                   
                                    <tr>

                                        <td class="numeric"> Status</td>
                                        <td class="numeric"><?php if($userDetails['status']==ACTIVE){ echo 'Active'; }else{ echo 'Dactive'; } ?> </td>

                                    </tr>
                                   <tr>
                                        <td class="numeric"> Completed Task </td>
                                        <td class="numeric"><?php echo $userDetails['userCompletedTaskDetails']; ?> </td>
                                    </tr>
                                     <tr>
                                        <td class="numeric"> Incompleted Task </td>
                                        <td class="numeric"><?php echo $userDetails['userUncompleteTaskDetails']; ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> Completed Time Spent</td>
                                        <td class="numeric"><?php echo $userDetails['timeComplet']; ?> </td>
                                    </tr>
                                    <tr>
                                        <td class="numeric"> Incompleted Time Spent</td>
                                        <td class="numeric"><?php echo $userDetails['timeUncomplet']; ?> </td>
                                    </tr>
                                </tbody>
                            </table>





                            <div class="row list-separated profile-stat">
                                <div class="form-group" style="text-align: center;">
                                    <!--<input type="submit" class="btn green submit" value="Asign Task">-->
                                    <input type="submit" class="btn btn-success submit" value="Block user">
                                </div>
                               
                            </div>


                        </div>

                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
                <div class="col-md-8 col-sm-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class=" icon-list font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">Task History</span>
                            </div>

                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_5">
                                <thead>
                                    <tr>
                                        <th class="table-checkbox">
                                            Sr.No
                                        </th>
                                        <th> Date</th>
                                        <th> ScooterId </th>
                                        <th> Task </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach($userDetails['tsakDetails'] as $key=>$tsak){?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $key + 1; ?></td>
                                        <td><?php echo $tsak['assignDate'] ?></td>
                                        <td> <?php echo $tsak['scooterNumber'] ?></td>
                                        <td> <?php echo  $tsak['issueTitle'] ?></td>
                                        
                                        <td >  <a href="javascript:;" data-repeater-create class="btn btn-success mt-repeater-add">View</a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>
        </div>
    </div>
</div>


