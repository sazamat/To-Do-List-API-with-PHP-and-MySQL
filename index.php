<?php
header("Content-Type: application/json");

// Include the database connection
require_once 'config/connection.php';

// Get the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Retrieve tasks
    $result = $conn->query("SELECT * FROM todos");
    $todos = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
    echo json_encode($todos);
} elseif ($method === 'POST') {
    // Add a new task
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input === null) {
        echo json_encode(["status" => "error", "message" => "JSON decoding failed"]);
        exit;
    }

    if (isset($input['task'])) {
        $task = $conn->real_escape_string($input['task']);
        $conn->query("INSERT INTO todos (task) VALUES ('$task')");
        echo json_encode(["status" => "success", "message" => "Task added"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input: 'task' field is missing"]);
    }
} elseif ($method === 'PUT') {
    // Update a task
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input === null) {
        echo json_encode(["status" => "error", "message" => "JSON decoding failed"]);
        exit;
    }

    if (isset($input['id']) && isset($input['task'])) {
        $id = (int) $input['id'];
        $task = $conn->real_escape_string($input['task']);
        $conn->query("UPDATE todos SET task='$task' WHERE id=$id");
        echo json_encode(["status" => "success", "message" => "Task updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input: 'id' or 'task' field is missing"]);
    }
} elseif ($method === 'DELETE') {
    // Delete a task
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input === null) {
        echo json_encode(["status" => "error", "message" => "JSON decoding failed"]);
        exit;
    }

    if (isset($input['id'])) {
        $id = (int) $input['id'];
        $conn->query("DELETE FROM todos WHERE id=$id");
        echo json_encode(["status" => "success", "message" => "Task deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input: 'id' field is missing"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
