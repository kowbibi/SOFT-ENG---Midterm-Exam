<?php
require 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, address, age, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$first_name, $last_name, $address, $age, $email, $password])) {
        echo "Registration successful!";
    } else {
        echo "Error: Could not register.";
    }
}
?>

<!-- Registration Form -->
<form method="POST" action="">
    First Name: <input type="text" name="first_name" required><br>
    Last Name: <input type="text" name="last_name" required><br>
    Address: <input type="text" name="address" required><br>
    Age: <input type="number" name="age" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>
