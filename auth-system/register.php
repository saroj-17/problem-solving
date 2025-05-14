<?php

require 'db.php'; 


$username = $_POST['username'] ?? ''; 
$password = $_POST['password'] ??'';

//password hashing 
if($username && $password) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    try{
        $stmt = $pdo->prepare("INSERT INTO users (username,password) VALUES (?,?)");
        $stmt->execute(([$username, $hashed]));
        echo "Registered Sucessfully !<a href ='login_form.html'>Login</a>";
    }
    catch(PDOException $e) {
       echo "Username already exists or database error!"; 

    }
}else{
    echo "Please fill in both username and pasword field";
}
?>