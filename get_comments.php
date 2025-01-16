<?php
require 'db_config.php';

if (isset($_GET['video_id'])) {
    $video_id = trim($_GET['video_id']); // Ensure it's a string
    $stmt = $con->prepare("SELECT name, email, message FROM reactions WHERE video_id = ?");
    $stmt->bind_param('s', $video_id); // Bind video_id as a string
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];

    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    echo json_encode($comments);
    $stmt->close();
}
?>
