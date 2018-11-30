<div class="row">
    <div class="col-md-12">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="<?= base_url("admin"); ?>">Home</a>
                    <i class="fa fa-circle"></i>
                </li>  
                <li>
                    <span>Notifications</span>
                </li>
            </ul> 
        </div>
        <br/>
        <!-- BEGIN PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-green">
                    <i class="icon-bell"></i>
                    <span class="bold uppercase">Notifications</span>
                </div> 
            </div>

            <div class="container-fluid text-center">  
                <table id="example" class="display table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th> Sr.No </th>
                            <th> Scooter </th>
                            <th> Tracker </th>
                            <th> Message </th>
                            <th> Received By </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(count($notifications)){
                            foreach($notifications as $key => $notification){
                            ?>
                            <td> <?=$key + 1;?> </td>
                            <td> <?=($notification->reservedScooter)?strtoupper($notification->reservedScooter):"--";?></td>
                            <td> <?=($notification->reservedTracker)?strtoupper($notification->reservedTracker):"--";?> </td>
                            <td> <?= ucwords($notification->message);?> </td>
                            <td> <?= $notification->createdOn; ?></td>
                            <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>    
            </div>

        </div>
    </div>
</div>