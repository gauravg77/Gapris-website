<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intial-scale=1.0">
        <title>Login Page</title>
        <link rel="stylesheet" href="../Assets/stylee.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

</head>
<body>
    <div class="container">
        <h1 class="form-title">Sign In</h1>
       
    <form method="POST" action="shop.php">
    <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type ="email" name="email" id="email" placeholder="email or username">
    </div>
    <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type ="password" name="password" id="password" placeholder="password" required>
        <!-- <i class="fa fa-eye"></i>  -->
    </div> 
    
    <input type="submit" class="btn" value="Sign In" name="Sign In">
    <p class="links">
        <a href="#">Recover Password<a>
    </p>
    <div class="icons">
        <!-- <i class="fab fa-google"></i> -->
        <!-- <i class="fab fa-facebook"></i> -->
    </div>
    <br>
    <div class="links">
    <p>Don't Have Account yet?</p>
          <a href="registerstruc.php">Register Now</a>
        </div>


 
</div>
</form>
</body>
</html>

