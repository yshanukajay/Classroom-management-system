<?php
// Update path to auth
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch allocations
    // Optional filters: classroom_id, day_of_week
    $sql = "SELECT a.*, c.name as classroom_name, c.capacity 
            FROM allocations a 
            JOIN classrooms c ON a.classroom_id = c.id
            ORDER BY a.day_of_week, a.start_time";

    $result = $mysqli->query($sql);
    $allocations = [];
    while ($row = $result->fetch_assoc()) {
        $allocations[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $allocations]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new allocation
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        $data = $_POST;
    }

    $classroom_id = $data['classroom_id'] ?? null;
    $course_name = $data['course_name'] ?? '';
    $instructor = $data['instructor'] ?? '';
    $day_of_week = $data['day_of_week'] ?? '';
    $start_time = $data['start_time'] ?? '';
    $end_time = $data['end_time'] ?? '';

    if (!$classroom_id || !$day_of_week || !$start_time || !$end_time) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    // Conflict Detection
    // Check if any booking exists for same room, same day, and time overlaps
    $stmt = $mysqli->prepare("SELECT count(*) as count FROM allocations 
        WHERE classroom_id = ? 
        AND day_of_week = ? 
        AND (
            (start_time < ? AND end_time > ?)
        )");

    // Logic: (ExistStart < NewEnd) AND (ExistEnd > NewStart)
    $stmt->bind_param("isss", $classroom_id, $day_of_week, $end_time, $start_time);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Conflict detected! Room is already booked for this time slot.']);
        exit;
    }

    // Insert
    $insert = $mysqli->prepare("INSERT INTO allocations (classroom_id, course_name, instructor, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("isssss", $classroom_id, $course_name, $instructor, $day_of_week, $start_time, $end_time);

    if ($insert->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Allocation created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $mysqli->error]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Parse input for DELETE
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
        exit;
    }

    $stmt = $mysqli->prepare("DELETE FROM allocations WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Allocation cancelled.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $mysqli->error]);
    }
    exit;
}
?>