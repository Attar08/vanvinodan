<?php
include('../config.php');
require_once(PATH_LIBRARIES.'/classes/DBConn.php');
$db = new DBConn();
include('../header.php');
//error_reporting(0);
		 
	$status=$_POST["status"];
	$firstname=$_POST["firstname"];
	$amount=$_POST["amount"];
	$txnid=$_POST["txnid"];
	$posted_hash=$_POST["hash"];
	$key=$_POST["key"];
	$productinfo=$_POST["productinfo"];
	$email=$_POST["email"];
	$salt="Zu6mupmKDd";
	$mode=$_POST['mode'];
	$payuMoneyId=$_POST['payuMoneyId'];

	if(isset($_POST["additionalCharges"]))
	 {
		 $additionalCharges=$_POST["additionalCharges"];
		 $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
	 }
	 else 
	 {	  
		 $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
	 }

		$hash = hash("sha512", $retHashSeq);
 
		 if ($hash != $posted_hash)
		 {
			 echo "Invalid Transaction. Please try again";
			 //throw new Exception('0');
		 }
		 
		 else 
		 {

			 $Id = base64_decode($txnid);
			///********** for database (starts) *****************************///   

			///////////////////////////////////
			///// Query for payment status( not reload page)
			////////////////////////////////////					
			
			$paymentCheck=$db->ExecuteQuery("SELECT Grand_Total_Amt FROM tbl_reservation WHERE Reservation_Status=5 AND Reservation_Id=".$Id);
			
			//echo "SELECT Grand_Total_Amt FROM tbl_reservation WHERE Reservation_Status=5 AND Reservation_Id=".$Id;
			
			if(count($paymentCheck)>0)
			{
				
				///////////////////////////////////
				///// insert transaction detail after transaction
				////////////////////////////////////	
				
				/*$res=mysql_query("UPDATE orders SET Transaction_Id='".$txnid."', Transaction_Date=TIMESTAMPADD(MINUTE, 330,NOW()), Payment_Id='".$payuMoneyId."', Pay_Status='".$status."', Payment_Mode='".$mode."', Payment_Status=1  WHERE Order_Id=".$Id[0]." AND User_Id=".$Id[1]."");*/
				
				$date = date('Y-m-d H:i:s');
				
				/*$res=mysql_query("INSERT INTO tbl_transactions ('Transaction_Date','Transaction_No','Reservation_Id','Paid_Amt','Payment_Mode','Pay_Status','Payment_Id') 			

VALUES ('".$date."', ".$txnid.", ".$Id.", ".$amount.", '".$mode."', '".$status."', '".$payuMoneyId."')");
				
				echo "INSERT INTO tbl_transactions ('Transaction_Date','Transaction_No','Reservation_Id','Paid_Amt','Payment_Mode','Pay_Status','Payment_Id') 			

VALUES ('".$date."', ".$txnid.", ".$Id.", ".$amount.", '".$mode."', '".$status."', '".$payuMoneyId."')";*/


				$tblname = "tbl_transactions";
				$tblfield=array('Transaction_Date', 'Transaction_No', 'Reservation_Id', 'Paid_Amt', 'Payment_Mode', 'Pay_Status', 'Payment_Id');
				$tblvalues=array($date, $txnid, $Id, $amount, $mode, $status, $payuMoneyId);
				$res=$db->valInsert($tblname, $tblfield, $tblvalues);
				
				/*echo '<script type="text/javascript">window.location.href="success-report.php?id='.$txnid.'";</script>';*/
				
				 
				  
			} //if closing (payment status for transection and no reload page )
			else{
				echo 'Reservation_Id='.$Id;
			}
			
		 }

?>
            
            
            
<script type="text/javascript">
$(window).load(function() {
	$(".loader").fadeOut("slow");
	$('.try').show();
})
</script>
<!--<style type="text/css">
.loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('<?php echo PATH_IMAGE?>/loading.gif') 50% 50% no-repeat rgb(249,249,249);
}
</style>-->
<body style="background:#fff;">
<?php //$_SESSION['CustomerId']=$Id[1];?>
<div style="margin:50px auto; width:150px; padding-bottom:30px;"> <a class="navbar-brand"  style="padding-top:4px" href='<?php echo LINK_ROOT."/index.php"?>'>Van Vinodan<!--<img src="<?php //echo PATH_IMAGE ?>/logo.png"  />--></a></div>
<br />
<div class="container">
  <div class="row">
  <div class="loader">
    <div class="col-sm-12 " align="center">
    <img src="<?php echo PATH_IMAGE?>/loading.gif" style="width:200px">
      <h4 class="text-center"> Please wait while your transaction is processing, and do not refresh or close the page until your transaction completed</h4>
    </div>
    </div> 
  </div>
</div>
</body>


<?php include('../footer.php'); ?>