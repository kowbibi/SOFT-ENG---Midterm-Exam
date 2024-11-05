<?php
require_once 'dbConfig.php';

function insertNewUser($pdo, $username, $plain_password, $staff_id, $employment_type, $contact_number) {
    $checkUserSql = "SELECT * FROM users WHERE username = ?";
    $checkUserStmt = $pdo->prepare($checkUserSql);
    $checkUserStmt->execute([$username]);

    if ($checkUserStmt->rowCount() == 0) {
        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, staff_id, employment_type, contact_number) VALUES(?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$username, $hashed_password, $staff_id, $employment_type, $contact_number]);
    } else {
        $_SESSION['message'] = "User already exists!";
        return false;
    }
}

function getHashedPasswordByUsername($pdo, $username) {
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['password'] : false;
}

function loginUser($pdo, $username, $entered_password) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
        if (password_verify($entered_password, $user['password'])) {
            $_SESSION['username'] = $username;
            return true;
        } else {
            $_SESSION['message'] = "Invalid password.";
            return false;
        }
    } else {
        $_SESSION['message'] = "Username not found.";
        return false;
    }
}

function getAllUsers($pdo) {
    $sql = "SELECT * FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUserByID($pdo, $user_id) {
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function insertMotorcycle($pdo, $model, $brand, $price, $added_by) {
    $sql = "INSERT INTO motorcycles (model, brand, price, added_by) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$model, $brand, $price, $added_by]);
}

function updateMotorcycle($pdo, $model, $brand, $price, $updated_by, $motorcycle_id) {
    $sql = "UPDATE motorcycles SET model = ?, brand = ?, price = ?, updated_by = ?, last_updated = NOW() WHERE motorcycle_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$model, $brand, $price, $updated_by, $motorcycle_id]);
}

function deleteMotorcycle($pdo, $motorcycle_id) {
    $sql = "DELETE FROM motorcycles WHERE motorcycle_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$motorcycle_id]);
}

function getAllMotorcycles($pdo) {
    $sql = "SELECT motorcycles.*, users.username AS added_by_user FROM motorcycles JOIN users ON motorcycles.added_by = users.user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getMotorcycleByID($pdo, $motorcycle_id) {
    $sql = "SELECT motorcycles.*, users.username AS added_by_user FROM motorcycles JOIN users ON motorcycles.added_by = users.user_id WHERE motorcycle_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$motorcycle_id]);
    return $stmt->fetch();
}
?>
