<?php
// Database connection
$host = 'localhost';
$db = 'feedback_system';
$user = 'root'; // replace with your DB username
$pass = ''; // replace with your DB password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$feedback_text = isset($_POST['feedback_text']) ? $_POST['feedback_text'] : '';

// Insert the feedback into the database
$sql = "INSERT INTO feedback (rating, feedback_text) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $rating, $feedback_text);

// Prepare the response message
$message = '';
$messageClass = '';

if ($stmt->execute()) {
    $message = "Office1<br>Thank you for your feedback! UEInsight!"; // Combined message
    $messageClass = "success-message"; // Class for success message
} else {
    $message = "Error: " . $stmt->error; // Error message
    $messageClass = "error-message"; // Class for error message
}

// Output the message with inline styles for full-screen design
echo "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Feedback Submitted</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #f8d7da, #f5c6cb); /* Gradient background */
            font-family: 'Arial', sans-serif;
            color: #c82333; /* Dark red text */
        }
        .message {
            background-color: #ffffff; /* White background for the message */
            border: 1px solid #f5c6cb; /* Border color matching background */
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            font-size: 28px; /* Larger font size */
            font-weight: bold; /* Bold font */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transition for hover effect */
        }
        .message:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3); /* Deeper shadow on hover */
        }
    </style>
</head>
<body>
    <div class='message'>$message</div>
</body>
</html>
";

// Close the connection
$stmt->close();
$conn->close();
?>
