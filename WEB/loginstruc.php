<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../Assets/stylee.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 class="form-title">Log In</h1>
        <form method="POST" action="login.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email or Username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn" name="Sign In">Sign In</button>
            <p class="links">
                <a href="forgotpasswordstruc.php">Recover Password</a>
            </p>
            <div class="links">
                <p>Don't have an account yet?</p>
                <a href="registerstruc.php">Sign Up</a>
            </div>
        </form>
    </div>
</body>
</html>
