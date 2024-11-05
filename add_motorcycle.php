<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $added_by = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO motorcycles (model, brand, price, added_by) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$model, $brand, $price, $added_by])) {
        echo "Motorcycle added successfully!";
    } else {
        echo "Error: Could not add motorcycle.";
    }
}
?>

<!-- Add Motorcycle Form -->
<form method="POST" action="">
    Model: <input type="text" name="model" required><br>
    Brand: <input type="text" name="brand" required><br>
    Price: <input type="number" name="price" step="0.01" required><br>
    <button type="submit">Add Motorcycle</button>
</form>
