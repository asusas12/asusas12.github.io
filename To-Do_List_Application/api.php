<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

switch ($method) {

    // READ - Get all tasks
    case 'GET':
        $result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        echo json_encode($tasks);
        break;

    // CREATE - Add new task
    case 'POST':
        $title = $conn->real_escape_string($input['title']);
        $description = $conn->real_escape_string($input['description'] ?? '');

        if (empty($title)) {
            echo json_encode(["error" => "Title is required"]);
            break;
        }

        $sql = "INSERT INTO tasks (title, description) VALUES ('$title', '$description')";
        if ($conn->query($sql)) {
            echo json_encode(["success" => true, "id" => $conn->insert_id, "message" => "Task added successfully"]);
        } else {
            echo json_encode(["error" => "Failed to add task"]);
        }
        break;

    // UPDATE - Edit task
    case 'PUT':
        $id = intval($input['id']);
        $title = $conn->real_escape_string($input['title']);
        $description = $conn->real_escape_string($input['description'] ?? '');
        $status = $conn->real_escape_string($input['status']);

        if (empty($title)) {
            echo json_encode(["error" => "Title is required"]);
            break;
        }

        $sql = "UPDATE tasks SET title='$title', description='$description', status='$status' WHERE id=$id";
        if ($conn->query($sql)) {
            echo json_encode(["success" => true, "message" => "Task updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update task"]);
        }
        break;

    // DELETE - Remove task
    case 'DELETE':
        $id = intval($input['id']);
        $sql = "DELETE FROM tasks WHERE id=$id";
        if ($conn->query($sql)) {
            echo json_encode(["success" => true, "message" => "Task deleted successfully"]);
        } else {
            echo json_encode(["error" => "Failed to delete task"]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>
