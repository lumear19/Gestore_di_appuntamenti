<?php

require_once('db_conn.php');


if(isset($_POST['email']) &&isset($_POST['password']) &&isset($_POST['username'])){
    if($stmt=$con->prepare('SELECT id, password FROM users WHERE email= ?')){
        $stmt->bind_param('s',$_POST['email']);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows>0){
            echo 'Email esistente. Riprovare';
        }
        else{
            if($stmt =$con->prepare('INSERT INTO users(username, email,password) VALUES(?,?,?)')){
                $password=password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bind_param('sss', $_POST['username'],$_POST['email'],$password);
                $stmt->execute();
                echo 'Registrazione effettuata';
            }
            else{
                echo 'Errore';
            }
        }
    $stmt->close();
    }
    else{
        echo('Errore');
    }
}
$con->close();
?>