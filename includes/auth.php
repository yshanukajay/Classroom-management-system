<?php
session_start();
// Updated path to config
require_once __DIR__ . '/../config/db.php';

function login($username, $password)
{
    global $mysqli;

    // Prepare statement to prevent SQL injection
    $stmt = $mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Check hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true;
        }
    }
    return false;
}

function register($username, $password, $full_name)
{
    global $mysqli;

    // Check if username exists
    $check = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        return "Username already exists.";
    }

    // Hash password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'staff'; // Default role

    $stmt = $mysqli->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hash, $full_name, $role);

    if ($stmt->execute()) {
        return true;
    }
    return "Error: " . $mysqli->error;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: /classroom_allocation_management_system/login.php");
        exit;
    }
}

function logout()
{
    session_destroy();
    header("Location: /classroom_allocation_management_system/login.php");
    exit;
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
?>