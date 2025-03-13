<?php $page_id = 1; // Example page ID ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nested Comment System with AJAX</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    padding: 20px;
}

h2 {
    color: #333;
}

form {
    background: #fff;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    max-width: 500px;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background: #007bff;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background: #0056b3;
}

#comment-section {
    max-width: 600px;
}

ul {
    list-style-type: none;
    padding-left: 0;
}

li {
    background: #fff;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
}

.reply-btn {
    color: #007bff;
    background: none;
    border: none;
    cursor: pointer;
    margin-top: 5px;
}

.reply-btn:hover {
    text-decoration: underline;
}

.reply-form {
    display: none;
    margin-top: 10px;
    padding-left: 20px;
}

    </style>
    <script>
        function submitComment(form) {
            $.ajax({
                type: "POST",
                url: "submit_comment.php",
                data: $(form).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        alert("Comment added successfully!");
                        $(form).find("input[type=text], input[type=email], textarea").val(""); // Clear fields
                        loadComments(); // Refresh comments
                    } else {
                        alert("Error: " + response.message);
                    }
                }
            });
            return false; // Prevent form from reloading the page
        }

        function loadComments() {
            $.ajax({
                url: "fetch_comments.php?page_id=<?php echo $page_id; ?>",
                success: function(data) {
                    $("#comment-section").html(data);
                }
            });
        }

        $(document).ready(function() {
            loadComments(); // Load comments on page load
        });

        function reply(commentId) {
            document.getElementById('reply-form-' + commentId).style.display = 'block';
        }
    </script>
</head>
<body>

<h2>Leave a Comment</h2>
<form onsubmit="return submitComment(this);">
    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="comment" placeholder="Your Comment" required></textarea>
    <button type="submit">Submit</button>
</form>

<h2>Comments</h2>
<div id="comment-section"></div>

</body>
</html>
