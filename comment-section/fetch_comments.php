<?php
include 'db.php';

$page_id = intval($_GET['page_id']); 

function fetchComments($conn, $page_id, $parent_id = NULL, $depth = 0) {
    $sql = "SELECT * FROM comments WHERE page_id = ? AND parent_id " . 
           ($parent_id === NULL ? "IS NULL" : "= ?") . " ORDER BY created_at ASC";

    $stmt = $conn->prepare($sql);
    if ($parent_id === NULL) {
        $stmt->bind_param("i", $page_id);
    } else {
        $stmt->bind_param("ii", $page_id, $parent_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<ul style="margin-left: ' . ($depth * 20) . 'px;">';
        while ($row = $result->fetch_assoc()) {
            echo "<li><b>{$row['name']}:</b> {$row['comment']}
                  <button onclick='reply({$row['id']})'>Reply</button>
                  <div id='reply-form-{$row['id']}' style='display:none;'>
                      <form onsubmit='return submitComment(this);'>
                          <input type='hidden' name='page_id' value='{$page_id}'>
                          <input type='hidden' name='parent_id' value='{$row['id']}'>
                          <input type='text' name='name' placeholder='Your Name' required>
                          <input type='email' name='email' placeholder='Your Email' required>
                          <textarea name='comment' placeholder='Your Reply' required></textarea>
                          <button type='submit'>Submit Reply</button>
                      </form>
                  </div>
                  </li>";

            // Recursively fetch replies
            fetchComments($conn, $page_id, $row['id'], $depth + 1);
        }
        echo '</ul>';
    }
}

fetchComments($conn, $page_id);
$conn->close();
?>
