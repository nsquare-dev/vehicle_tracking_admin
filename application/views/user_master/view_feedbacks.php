
<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>User Feedbacks</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-list"></i>
                    <span class="bold uppercase">User Feedbacks</span>
                </div>
            </div>

            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="body">
                <div class="table-responsive">
                     
               <table id="example" class="table table-striped table-bordered table-hover table-checkable">
                        <thead>
                            <tr>
                                <th> Sr.No </th>
                                <th> Comment </th>
                                <th> Created Date </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($feedbacks as $key => $feedback) {
                                ?>
                                <tr>
                                    <td > <?php echo $i++; ?> </td>
                                    <td > <?php echo ucwords($feedback->comment); ?> </td>
                                    <td > <?php echo date("Y-m-d h:i:sa", strtotime($feedback->createdDate)); ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END SAMPLE TABLE PORTLET-->   
        </div>
    </div>
</div>
