<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    
if( isset($_GET['date']) &&isset($_GET['email'])){
    $email1 = $_SESSION['email'];
    $email2 = $_GET['email'];
    $date = $_GET['date'];
    $stmt = $mysqli->prepare("select * from bookings where date = ? and email2= ?");
    $stmt->bind_param('ss', $date, $email2);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[] = $row['timeslot'];
            }
            
            $stmt->close();
        }
    }
}

if(isset($_POST['submit'])){
    $timeslot = $_POST['timeslot'];
    $stmt = $mysqli->prepare("select * from bookings where date = ? AND timeslot = ? AND email2=?");
    $stmt->bind_param('sss', $date, $timeslot, $email2);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            $msg = "<div class='alert alert-danger'>Già Prenotato</div>";
        }else{
            $stmt = $mysqli->prepare("INSERT INTO bookings (email1, email2, date, timeslot) VALUES (?,?,?,?)");
            $stmt->bind_param('ssss', $email1, $email2, $date, $timeslot);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Prenotazione avvenuta</div>";
            $bookings[]=$timeslot;
            $stmt->close();
            $mysqli->close();
        }
    }
}
$duration=30;
$cleanup=0;
$start="09:00";
$end="19:00";
function timeslots($duration,$cleanup, $start, $end){
    $start=new DateTime($start);
    $end=new DateTime($end);
    $interval=new DateInterval("PT".$duration."M");
    $cleanupInterval=new DateInterval("PT".$cleanup."M");
    $slots=array();
    for($intStart=$start;$intStart<$end;$intStart->add($interval)->add($cleanupInterval)){
        $endPeriod=clone $intStart;
        $endPeriod->add($interval);
        if($endPeriod>$end){
            break;
        }
        $slots[]=$intStart->format("H:i")."-".$endPeriod->format("H:i");
    }
    return $slots;
}

?>
<!doctype html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title></title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
  </head>

    <body>
        <div class="container">
            <h1 class="text-center">Prenotazioni per il giorno <?php echo date('d/m/Y', strtotime($date)); ?></h1><hr>
            <div class="row">
                <div class="col-md-12">
                    <?php echo isset($msg)?$msg:"";?>
                </div>
                <?php $timeslots=timeslots($duration,$cleanup, $start, $end);
                foreach($timeslots as $ts){ ?>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php if(in_array($ts,$bookings)){ ?>
                                    <button class="btn btn-danger"><?php echo $ts;?></button>

                                    <?php }else{ ?>
                            
                                    <button class="btn btn-success book" data-timeslot="<?php echo $ts;?>"><?php echo $ts;?></button>
                        
                                    <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
                    
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Prenotare: <span id="slot"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Timeslot</label>
                                    <input required type="text" readonly name="timeslot" id="timeslot">
                                </div>
                                <div class="form-group pull-right">
                                    <button class="btn btn-primary" type="submit" name="submit">Conferma</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script>
            $(".book").click(function(){
                var timeslot=$(this).attr('data-timeslot');

                $("#slot").html(timeslot);
                $("#timeslot").val(timeslot);
                $("#myModal").modal("show");
                }
            )
        </script>
    </body>
</html>