<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not authenticated.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Fetch tasks for the logged-in user, ordered by creation date
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        echo json_encode($tasks);
        break;

    case 'POST':
        $task = $data['task'] ?? '';
        $due_datetime = $data['due_datetime'] ?? null;

        if (empty($task)) {
            http_response_code(400);
            echo json_encode(['error' => 'Task cannot be empty.']);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO tasks (user_id, task, due_datetime) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $task, $due_datetime);
        if ($stmt->execute()) {
            echo json_encode(['id' => $stmt->insert_id, 'message' => 'Task added.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add task.']);
        }
        break;

    case 'PUT':
        $task_id = $data['id'] ?? 0;
        $is_completed = $data['is_completed'] ?? 0;
        $stmt = $conn->prepare("UPDATE tasks SET is_completed = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $is_completed, $task_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Task updated.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update task.']);
        }
        break;

    case 'DELETE':
        $task_id = $data['id'] ?? 0;
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $task_id, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Task deleted.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete task.']);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method not supported.']);
        break;
}

$conn->close();
?>
