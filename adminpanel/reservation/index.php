<?php 
include('../../config.php');
require_once(PATH_LIBRARIES.'/classes/DBConn.php');
$db = new DBConn();

//****************************************************************
//using for SQL Query ////////////////////////////////////////////
//****************************************************************
$checkindate = date("Y-m-d");
$checkoutdate = date("Y-m-d",strtotime($checkindate.'+1 day'));//It will adding 1 day
//****************************************************************
//using for Calendar /////////////////////////////////////////////
//****************************************************************
$checkin = date("d-m-Y");
$checkout = date("d-m-Y",strtotime($checkin.'+1 day'));//It will add 1 day

/////////////////////////////
//Get Total No of Nights
/////////////////////////////
$date1 = new DateTime($checkindate);
$date2 = new DateTime($checkoutdate);

///////////////////////////////////////////////////////////////////////////////////////
// this calculates the diff between two dates, which is the number of nights
///////////////////////////////////////////////////////////////////////////////////////
$numberOfNights= $date2->diff($date1)->format("%a"); 
///////////////////////////////////////////////////////////////////////////////////////

//**********************************************************************************
// Get all the Room Types which are having rooms availble for reservation //////////
//**********************************************************************************
$res = $db->ExecuteQuery("SELECT R_Category_Id, R_Category_Name, R_Capacity, Base_Fare FROM tbl_rooms_category

WHERE R_Category_Id IN (SELECT R_Category_Id FROM tbl_room_master 
WHERE Room_id NOT IN (SELECT Room_Id FROM tbl_reserved_rooms WHERE Check_In_Date <= '".$checkindate."' AND Check_Out_Date > '".$checkindate."' AND Reservation_Status<>3 AND Reservation_Status<>4 AND Reservation_Status<>5 ))");
////////////////////////////////////////////////////////////////////////////////////

require_once(PATH_ADMIN_INCLUDE.'/header.php');

?>

<script type="text/javascript" src="reservation.js"></script>

<div id="loading">
    <div class="loader-block"><i class="fa-li fa fa-spinner fa-spin spinloader"></i></div>
</div>


<div>

  <div class="page-title">
    <div class="title_left">
      <h3>New Reservation</h3>
    </div>
  </div>

  <div class="clearfix"></div>

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <form class="form-horizontal" role="form" id="searchRoomsFrm" method="post">
        
             <div class="col-sm-2 col-xs-6 padding-left-zero">
                  <div class="input-group date" data-provide="datepicker">
                      <input type="text" id="chckin" name="chckin" class="form-control input-sm datetimepicker2" placeholder="check-in" value="<?php echo $checkin; ?>">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar" aria-hidden="true"></i>
                      </div>
                  </div>
              </div>
              
              <div class="col-sm-2 col-xs-6">
                  <div class="input-group date" data-provide="datepicker">
                      <input type="text" id="chckout" name="chckout" class="form-control input-sm datetimepicker3" placeholder="check-out" value="<?php echo $checkout; ?>">
                      <div class="input-group-addon">
                          <i class="fa fa-calendar" aria-hidden="true"></i>
                      </div>
                  </div>
              </div>
              <div class="col-sm-2 col-xs-12 text-left text-center-xs"><button type="button" id="searchRoomsBtn" class="btn btn-sm btn-info"><i class="fa fa-search" aria-hidden="true"></i> SEARCH</button></div>
              
              <div class="clearfix"></div>
          
          </form>
          
        </div>
        <div class="x_content">
          
          <div>
          <?php foreach($res as $val){ ?>
            <div class="col-md-4 col-sm-4 col-xs-12 animated fadeInDown">
              <div class="well profile_view">
                <div class="col-sm-12">
                  <div class="text-center">
                    <h2><?php echo $val['R_Category_Name']; ?></h2>
                    <p><strong>Capacity: </strong> <?php echo $val['R_Capacity']; ?></p>

                    <?php $roomCount = $db->ExecuteQuery("SELECT COUNT(Room_Id) AS RID FROM tbl_room_master 
WHERE R_Category_Id=".$val['R_Category_Id']." AND Room_id NOT IN (SELECT Room_Id FROM tbl_reserved_rooms WHERE Check_In_Date <= '".$checkindate."' AND Check_Out_Date > '".$checkindate."' AND Reservation_Status<>3 AND Reservation_Status<>4 AND Reservation_Status<>5)"); ?>

                    <p><strong>Rooms Left:</strong> <span class="badge"><?php echo $roomCount[1]['RID']; ?></span></p>
                    <p class="room-price"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $val['Base_Fare']; ?></p>
                  </div>
                  
                </div>
                <div class="col-xs-12 bottom-btn text-center">
                  <div>
                    <button type="button" id="<?php echo $val['R_Category_Id']; ?>" class="btn btn-success btn-sm selectRoomBtn"> <i class="fa fa-plus-circle" aria-hidden="true"></i> Select Rooms </button>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>

            <div class="clearfix"></div>

          </div>
          <hr>

          <div id="roomData">
            <table class="table table-hover table-stripped">
              <thead>
                <tr class="bg-info">
                  <th>Room No.</th>
                  <th>Adult</th>
                  <th>Child</th>
                  <th>Extra Guest</th>
                  <th>Base Fare</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>


          
        </div>
      </div>
    </div>
    
    
    <?php foreach($res as $value){ ?>
    <!-- Modal POPUP -->
    <div id="roomInfo-<?php echo $value['R_Category_Id']; ?>" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" style="display: none;" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document">
          
          <div class="modal-content" id="usersignin">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  <h4 class="modal-title" id="gridModalLabel">Select Rooms</h4>
              </div>
              <div class="modal-body">
                <ul>          
                <?php $rooms = $db->ExecuteQuery("SELECT Room_Id, Room_Name FROM tbl_room_master 
WHERE R_Category_Id=".$value['R_Category_Id']." AND Room_id NOT IN (SELECT Room_Id FROM tbl_reserved_rooms WHERE Check_In_Date <= '".$checkindate."' AND Check_Out_Date > '".$checkindate."' AND Reservation_Status<>3 AND Reservation_Status<>4 AND Reservation_Status<>5)"); 
                
                foreach($rooms as $roomsVal){ ?>
                  <li><input class="roomid" type="checkbox" value="<?php echo $roomsVal['Room_Id']; ?>"><?php echo $roomsVal['Room_Name']; ?></li>
                <?php }
?>
                </ul>
              </div>
              <div class="clearfix"></div>
          </div>
        </div>
    </div>
    <!-- EOF Modal POPUP -->
    <?php } ?>

</div>
<?php require_once(PATH_ADMIN_INCLUDE.'/footer.php'); ?>