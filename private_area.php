<?php

session_start();
if(!isset($_SESSION['loggedIn'])||$_SESSION['loggedIn']!==true){
    header('location:login.php');
    exit;
}
function build_table_usernames(){
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $stmt = $mysqli->prepare("select * from users");
    if($stmt->execute()){
        $nchoice=0;
        $counter=0;
        $result = $stmt->get_result();
        $usersList="<center><h2>Visualizza i calendari dgli appuntamenti di:</h2>"; 
        $usersList.="<table class='table table-bordered'>";
        $usersList.="<tr></tr>";

        while($row = $result->fetch_assoc()){
            if($nchoice==1){
                $nchoice=0;
                $usersList.="</tr><tr>";
            }
            $newuser = $row['username'];
            $newEmail = $row['email'];
            $email[] = $row['email'];
            $nchoice++;
            $usersList.="<td><a href='calendar.php?email=".$newEmail."'class='btn btn-success btn-xs'> $newuser ($newEmail) </a></td>";
        }
        $usersList.="</tr></table>";
        return $usersList;
    }    
}
function build_table_bookings1(){
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $stmt = $mysqli->prepare("select * from bookings WHERE email2 = ?");
    $stmt->bind_param('s', $_SESSION["email"]);
    if($stmt->execute()){
        $nchoice=0;
        $counter=0;
        $result = $stmt->get_result();
        $bookedList="<center><h2>Gli appuntamenti a te richiesti:</h2>"; 
        $bookedList.="<table class='table table-bordered'>";
        $bookedList.="<tr></tr>";

        while($row = $result->fetch_assoc()){
            if($nchoice==1){
                $nchoice=0;
                $bookedList.="</tr><tr>";
            }
            $timeslot = $row['timeslot'];
            $date = $row['date'];
            $newEmail = $row['email1'];
            $bookedList.="<td> $newEmail $date $timeslot </a></td>";
            $nchoice++;
        }
        $bookedList.="</tr></table>";
        return $bookedList;
    }    
}
function build_table_bookings2(){
    $mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');
    $stmt = $mysqli->prepare("select * from bookings WHERE email1 = ?");
    $stmt->bind_param('s', $_SESSION["email"]);
    if($stmt->execute()){
        $nchoice=0;
        $counter=0;
        $result = $stmt->get_result();
        $bookedList="<center><h2>Gli appuntamenti da te richiesti:</h2>"; 
        $bookedList.="<table class='table table-bordered'>";
        $bookedList.="<tr></tr>";

        while($row = $result->fetch_assoc()){
            if($nchoice==1){
                $nchoice=0;
                $bookedList.="</tr><tr>";
            }
            $timeslot = $row['timeslot'];
            $date = $row['date'];
            $newEmail = $row['email2'];
            $bookedList.="<td> $newEmail $date $timeslot </a></td>";
            $nchoice++;
        }
        $bookedList.="</tr></table>";
        return $bookedList;
    }    
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="=IE=edge">
        <meta name="viewport" content=""width=device-width, initial-scale="1.0">
        <title>Area Privata </title> 
    </head>
    <body>
        <h2><?php echo "Ciao " .$_SESSION["username"];?></h2>
        <?php
            echo build_table_usernames();
            echo build_table_bookings1();
            echo build_table_bookings2();
            ?>
        <p><a href="logout.php">Disconnetti</a>
    </body>
</html> 