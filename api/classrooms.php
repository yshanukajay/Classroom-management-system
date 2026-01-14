<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $data['action'] ?? '';

    if ($action === 'create') {
        $name = $mysqli->real_escape_string($data['name']);
        $type = $mysqli->real_escape_string($data['type']);
        $capacity = (int) $data['capacity'];
        $facilities = $mysqli->real_escape_string($data['facilities']);
        $status = 'Active';

        $stmt = $mysqli->prepare("INSERT INTO classrooms (name, type, capacity, facilities, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $name, $type, $capacity, $facilities, $status);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Classroom added successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $mysqli->error]);
        }
    } elseif ($action === 'update') {
        $id = (int) $data['id'];
        $name = $mysqli->real_escape_string($data['name']);
        $type = $mysqli->real_escape_string($data['type']);
        $capacity = (int) $data['capacity'];
        $facilities = $mysqli->real_escape_string($data['facilities']);
        $status = $mysqli->real_escape_string($data['status']);

        $stmt = $mysqli->prepare("UPDATE classrooms SET name=?, type=?, capacity=?, facilities=?, status=? WHERE id=?");
        $stmt->bind_param("ssissi", $name, $type, $capacity, $facilities, $status, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Classroom updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $mysqli->error]);
        }
    } elseif ($action === 'delete') {
        $id = (int) $data['id'];

        // Check for allocations first
        $check = $mysqli->query("SELECT COUNT(*) as count FROM allocations WHERE classroom_id = $id");
        if ($check->fetch_assoc()['count'] > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Cannot delete: Classroom has active allocations.']);
            exit;
        }

        $stmt = $mysqli->prepare("DELETE FROM classrooms WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Classroom deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $mysqli->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
    }
}
?>