<?php
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $video_id = trim($_POST['video_id']); 

    // Validate the inputs
    if ($name && $email && filter_var($email, FILTER_VALIDATE_EMAIL) && $message && $video_id) {
        $stmt = $con->prepare("INSERT INTO reactions (name, email, message, video_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $email, $message, $video_id);  

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input. Fields: ' . json_encode($_POST)]);
    }
}
?>
