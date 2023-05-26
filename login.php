<?php
session_start();
include "db_conn.php";
if(isset($_POST['email']) &&isset($_POST['password'])){
    $email=$con->real_escape_string($_POST['email']);
    $pass=$con->real_escape_string($_POST['password']);
    //$hashed_pass=password_hash($pass, PASSWORD_DEFAULT);

    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $sql="SELECT * FROM users WHERE email= '$email'";
        if($result=$con->query($sql)){
            if($result->num_rows==1){
                $row=$result->fetch_array(MYSQLI_ASSOC);
                if(password_verify($pass,$row['password'])){
                    session_start();

                    $_SESSION['loggedIn']=true;
                    $_SESSION['id']=$row['id'];
                    $_SESSION['username']=$row['username'];
                    $_SESSION['email']=$row['email'];
                    header("location: private_area.php");

                }else{
                    echo"Password non corretta";
                }
            
            }else{
                echo"Nessun account con quella mail";
            }
        }else{
            echo "Errore in fase di login";
        }

    }
}
$con->close();

?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="=IE=edge">
        <meta name="viewport" content=""width=device-width, initial-scale="1.0">
        <title>Log In</title> 
        <style>
            body{
                display:flex;
                justify-content:center;
            }
            form{
                display:flex;
                flex-direction:column;
                width:300px;
            }
            form >input{
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
    <form method="POST">
        <h2>Log in</h2>
    
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Email" id="email" required></br>

        <label> Password</label>
        <input required type="password" name="password" placeholder="Password"></br>
                
        <button type="submit" value="invia">Login</button>
        <p>Non hai ancora un account? <a href="index.php">Registrati</a>
    </form>
    </body>
</html> 