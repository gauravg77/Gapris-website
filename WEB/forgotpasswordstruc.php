
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../Assets/stylee.css">
</head>
<body>
    <div class="container">
        <h1 class="form-title">Recover Password</h1>
        <form method="POST" action="forgotpassword.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>
            <input type="submit" class="btn" value="Submit">
        </form>
        <p class="links">
            <a href="loginstruc.php">Back to Login</a>
        </p>
    </div>
</body>
</html>
