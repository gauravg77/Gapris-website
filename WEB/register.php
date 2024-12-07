<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, intial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <!-- <link rel="stylesheet" href="../Assets/style.css">  -->
</head>
<body>
<div class="container">
    <h1 class="form-title">Register</h1>
    <form method="POST" action="">
        <div>
            <i class="fas fa-user"></i>
            <input type="text" name="name" id="name" placeholder="name">
        </div>
        <div>
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="email">
        </div>
        <div>
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password"placeholder="password"required>
            <i id="eye" class="fas fa-eye"></i>
        </div>
        <div>
            <i class="fas fa-lock"></i>
            <input type="confirmPassword" name="confirmPassword" id="confirmPassword"placeholder="confirm password"required>
        </div>
        <div>
            <input type="submit" value="submit">
        </div>          
        <div>
        <i class= "fab fa-google"></i>
        <i class="fab fa-facebook"></i>
        </div>
        <p> already have an account?</p>
        <a href="login.php">Sign In </a>
        <script src="script.js"></script>

</form>           
</div>
</body>
</html>
