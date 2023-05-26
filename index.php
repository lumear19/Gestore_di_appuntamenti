<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="=IE=edge">
        <meta name="viewport" content=""width=device-width, initial-scale="1.0">
        <title>
            Registrati
        </title> 
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
        <form action="register.php" method="POST">
            <h2>Registrati</h2>

            <label> User Name</label>
            <input required type="text" name="username" placeholder="Nome Utente"></br>
            
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Email" id="email" required>

            <label> Password</label>
            <input required type="password" name="password" placeholder="Password"></br>
        
                    
            <button type="submit" value="invia">Registrati</button>
            <p>Hai già un account? <a href="login.php">Accedi</a> 
        </form>
    </body>
</html>