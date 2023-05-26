<?php
$DATABASE_HOST='p:localhost';
$DATABASE_USER='root';
$DATABASE_PASS='';
$DATABASE_NAME='bookingcalendar';

$con=new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME );
if($con===false){
    die('Errore di connessione al database'.$con->connect_error());
}
?>