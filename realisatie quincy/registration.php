<?php
include "db.php";

if (isset($_POST["login-submit"])) {
    $FirstName = $_POST['Firstname'];
    $LastName = $_POST['Lastname'];
    $DateOfBirth = $_POST['DateOfBirth'];
    $Nationality = $_POST['Nationality'];
    $TeamID = $_POST['TeamID'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert new user
    $addUserStmt = $pdo->prepare("INSERT INTO user (FirstName, LastName, DateOfBirth, Nationality, TeamID, email, password) 
                                VALUES (:FirstName, :LastName, :DateOfBirth, :Nationality, :TeamID, :email, :password)");
    $addUserStmt->bindParam(':FirstName', $FirstName);
    $addUserStmt->bindParam(':LastName', $LastName);
    $addUserStmt->bindParam(':DateOfBirth', $DateOfBirth);
    $addUserStmt->bindParam(':Nationality', $Nationality);
    $addUserStmt->bindParam(':TeamID', $TeamID);
    $addUserStmt->bindParam(':email', $email);
    $addUserStmt->bindParam(':password', $password);
    $addUserStmt->execute();

    // Get last inserted ID
    $userID = $pdo->lastInsertId();

    // Encrypt password
    $ciphering = "AES-128-CTR";
    $options = 0;
    $encryption_iv = '1234567891011121';
    $encryption_key = $userID;
    $encryptedPassword = openssl_encrypt($_POST["password"], $ciphering, $encryption_key, $options, $encryption_iv);

    // Update user with encrypted password
    $updateUserStmt = $pdo->prepare("UPDATE user SET password = :updatedPWD WHERE id = :updatedID");
    $updateUserStmt->bindParam(':updatedPWD', $encryptedPassword);
    $updateUserStmt->bindParam(':updatedID', $userID);
    $updateUserStmt->execute();

    echo "success";
}else{
    echo "error";
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
    <label for="Firstname">First Name</label>
    <input type="text" id="Firstname" name="Firstname" required><br><br>
    <label for="Lastname">Last Name</label>
    <input type="text" id="Lastname" name="Lastname" required><br><br>
    <label for="DateOfBirth">Date of birth</label>
    <input type="date" id="DateOfBirth" name="DateOfBirth" required><br><br>
    <label for="Nationality">Nationality</label>
    <input type="text" id="Nationality" name="Nationality" required><br><br>
    <label for="TeamID">Team ID</label>
    <input type="text" id="TeamID" name="TeamID" required><br><br>
    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" name="login-submit" value="Login">
</form>
</body>
</html>
