<?php
require_once '../Includes/dbConnect.php';

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['signup']))
{
    $name=trim($_POST['name']);
    $email=trim($_POST['email']);
    $password=$_POST['password'];
    $confirmpassword=$_POST['confirmpassword'];
    $created_at = date('Y-m-d H:i:s');
if(empty($name)){
    $errors['name']="Name is required";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors['email']="Email is invalid";
}
if(strlen($password)<8){
    $errors['password']="password must be 8 characters";
}
if($password!==$confirmpassword){
    $errors['confirmpassword']="Password doesnt match";
}
  // Check for existing email
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->execute([':email' => $email]);
  if ($stmt->fetch()) {
      $errors[] = "Email is already registered.";
  }

  
//if no error then execute the code below for REGISTER AND DIRECT LOGIN TO PAGE
if (empty($errors)) {
    try {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, :created_at)");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => $created_at
        ]);

        // Redirect to index.php on successful registration
        header("Location: index.php");
        exit(); // Ensure the script stops executing after redirection
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
}


}
?>

