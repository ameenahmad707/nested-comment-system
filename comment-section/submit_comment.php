<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $page_id = intval($_POST['page_id']);
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : NULL;
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $comment = $conn->real_escape_string($_POST['comment']);

    $sql = "INSERT INTO comments (page_id, parent_id, name, email, comment) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $page_id, $parent_id, $name, $email, $comment);

    if ($stmt->execute()) {
        // Send email notification
        $to = $email;
        $subject = "New Comment Notification";
        $message = "Hello $name, your comment has been posted successfully!";
        $headers = "From: no-reply@yourwebsite.com";

        mail($to, $subject, $message, $headers);

        echo json_encode(["success" => true, "message" => "Comment added successfully!", "comment_id" => $stmt->insert_id]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
