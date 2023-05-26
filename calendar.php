<?php
session_start();
function build_calendar($month, $year) {
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    
    $daysOfWeek =array('Lunedì','Martedì','Mercoledì','Giovedì','Venerdì','Sabato','Domenica');
    $firstDayOfMonth=mktime(0,0,0,$month,1,$year);
    $numberDays=date('t', $firstDayOfMonth);
    $dateComponents=getdate($firstDayOfMonth);
    $monthName=$dateComponents['month'];
    $dayOfWeek=$dateComponents['wday']-1;
    $dateToday=date('Y-m-d');
    $email2=$_GET['email'];
    $stmt = $mysqli->prepare("select * from users where email = ?");
    $stmt->bind_param('s', $email2);
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            if($row = $result->fetch_assoc()){
                $user2 = $row['username'];
            }
            $stmt->close();
        }
    }
    $prev_month=date('m',mktime(0,0,0,$month-1,1,$year));
    $prev_year=date('Y',mktime(0,0,0,$month-1,1,$year));
    $next_month=date('m',mktime(0,0,0,$month+1,1,$year));
    $next_year=date('Y',mktime(0,0,0,$month+1,1,$year));
    $calendar="<center><h2>Disponibilità $monthName $year per $user2</h2>";
    $calendar.="<a class='btn btn-primary btn-xs' href='?month=".$prev_month."&year=".$prev_year."&email=".$email2."'>Mese Precedente</a>";
    $calendar.="<a class='btn btn-primary btn-xs' href='?month=".date('m')."&year=".date('Y')."&email=".$email2."'>Mese Corrente</a>";
    $calendar.="<a class='btn btn-primary btn-xs' href='?month=".$next_month."&year=".$next_year."&email=".$email2."'>Mese Successivo</a></center>";
    $calendar.="<table class='table table-bordered'>";
    $calendar.="<tr>";
    foreach($daysOfWeek as $day){
        $calendar.="<th class='header'>$day</th>";
    }

    $calendar.="<tr></tr>";
    if($dayOfWeek<0){$dayOfWeek=$dayOfWeek+7;}

    if($dayOfWeek>0){
        for($k=0;$k<$dayOfWeek;$k++){
            $calendar.="<td class='empty'></td>";
        }
    }

    $currentDay=1;
    $month=str_pad($month,2,"0",STR_PAD_LEFT);
    while($currentDay<=$numberDays){
        if($dayOfWeek==7){
            $dayOfWeek=0;
            $calendar.="</tr><tr>";
        }
        $currentDayRel=str_pad($currentDay,2,"0",STR_PAD_LEFT);
        $date="$year-$month-$currentDayRel";
        $dayName=strtolower(date('l',strtotime($date)));
        $today=$date==date('Y-m-d')?'today':'';
        if($date<date('Y-m-d')){
            $calendar.="<td class='$today'><h4>$currentDayRel</h4><a  class='btn btn-danger btn-xs'>Non disponibile</a></td>";
        }else{
            $calendar.="<td class='$today'><h4>$currentDay</h4><a href='book.php?date=".$date."&email=".$email2."'class='btn btn-success btn-xs'>Prenota</a></td>";
        }
        $currentDay++;
        $dayOfWeek++;
    }
    if($dayOfWeek!=7){
        $remainingDays=7-$dayOfWeek;
        for($i=0;$i<$remainingDays;$i++){
            $calendar.="<td class='empty></td>";
        }
    }
    $calendar.="</tr></table>";
    return $calendar;

}

?>


<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <style>
            @media only screen and (max-width:760px),
            (min-device-width:802px) and (max-device-width:1020px){
                table,thead,tbody,th,td,
                tr{display:block;}
                .empty{display:none;}
                th{
                    position:absolute;
                    top:-9999px;
                    left:-9999px;
                }
                tr{
                    border:1px solid #ccc;
                }
                td{
                    border:none;
                    border-bottom:1px solid #eee;
                    position:relative;
                    padding-left:50%;
                }
                td:nth-of-type(1):before{content:"L";}
                td:nth-of-type(2):before{content:"M";}
                td:nth-of-type(3):before{content:"M";}
                td:nth-of-type(4):before{content:"G";}
                td:nth-of-type(5):before{content:"V";}
                td:nth-of-type(6):before{content:"S";}
                td:nth-of-type(7):before{content:"D";}
            }
            @media (min-width:641px){
                table{
                    table-layout:fixed;
                }
                td{
                    width:33%;
                }
            }
            .row{
                margin-top:20px;
            }
            .today{
                background:yellow;
            }
        </style>
    </head>

<body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $dateComponents=getdate();
                    if(isset($_GET['month'])&&isset($_GET['year'])){
                        $month=$_GET['month'];
                        $year=$_GET['year'];
                    }else{
                        $month=$dateComponents['mon'];
                        $year=$dateComponents['year'];
                    }
                    echo build_calendar($month,$year);
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>