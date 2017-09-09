<?php 
include('../config.php');
require_once(PATH_LIBRARIES.'/classes/DBConn.php');
$db = new DBConn();
include(PATH_INCLUDE.'/header.php');
//error_reporting(0);
	
$reservationId = base64_decode($_REQUEST['id']);

$res=$db->ExecuteQuery("SELECT * FROM `tbl_transactions` WHERE `Reservation_Id`='".$reservationId."' ");

?>

<div class="content-wrapper">
  <div class="content">
    <div class="row">
      <div class="col-sm-8 col-sm-offset-2">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Payment Success </h3>
            
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-sm-12">
                <h2 class="text-center"> <i class="glyphicon glyphicon-ok-circle" style="color:#84AE2D; font-size:56px; vertical-align:middle;"></i> Your transaction successfully completed. </h2>
              </div>
              <div class="col-sm-6 col-sm-offset-3">
                <div style="background:#fff; padding:10px; box-shadow:0px 0px 2px #B7B7B7; margin-bottom:20px;">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                      </thead>
                      <tbody>
                         <tr>
                          <td style="width:170px;"><strong> Payment ID :</strong> <br /></td>
                          <td><?php echo $res[1]['Payment_Id'];?> <small>(Your future reference)</small></td>
                        </tr>
                      <td style="width:170px;"><strong> Transaction ID :</strong> <br /></td>
                          <td><?php echo $res[1]['Transaction_No'];?> </td>
                      </tr>
                      <td style="width:170px;"><strong> Amount : </strong></td>
                        <td>Rs. <?php echo $res[1]['Paid_Amt'];?></td>
                      </tr>
                      <!--<tr>
                        <td colspan="2"  style="padding-top:15px;"><h4>Click here to view your order detail  &nbsp; &nbsp;<a href="<?php echo LINK_ROOT.'/index.php'?>" class="btn btn-primary">Click Here</a> </h4></td>
                      </tr>-->
                        </tbody>
                      
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 <?php include(PATH_INCLUDE.'/footer.php');?>