
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../Assets/stylee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    <div class="container" id="signup">
        <h1 class="form-title">Register</h1>
        
        <form method="POST" action="register.php"  Autocomplete="off">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Name"  Autocomplete="off"  required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" Autocomplete="off" required>
             
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" Autocomplete="off"  >
                <i id="eye-password" class=""></i>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password"  Autocomplete="off"  required>
                <i id="eye-confirmpassword" class=""></i>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signup">
        </form>
        <!-- <p class="or">OR</p> -->
       
        <div class="icons">
            <!-- <i class="fab fa-google"></i> -->
            <!-- <i class="fab fa-facebook"></i> -->
        </div>
        <div class="links">
            <p>Already Have Account ?</p>
            <a href="loginstruc.php">Sign In</a>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
