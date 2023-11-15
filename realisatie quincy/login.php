<?php
session_start();
function loginUser()
{
    include "db.php";
    $query = $pdo->prepare("SELECT * FROM User WHERE email = :email");
    $query->bindparam(":email", $_POST["email"]);
//    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if (empty($user)) {
        echo "<div class='error'>Wachtwoord / E-mail is incorrect.</div>";
    } else {
        $simple_string = $_POST["password"];
        $ciphering = "AES-128-CTR";
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = $user["gb_id"];
        $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
        if ($user["password"] === $encryption) {
            $_SESSION['loggedIn'] = true;
            header("Location: ./index.php");
        } else {
            echo "<div class='error'>Wachtwoord / E-mail is incorrect.</div>";
        }
    }
}
if (isUserLoggedIn()) {
    echo "User is logged in!";
} else {
    echo "User is not logged in!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<form method="POST" action="">
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" name="login-submit" value="Login">
</form>
</body>

