<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finalproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch URLs with 'scraped' status as 'Y' and 'splitted' status as 'N'
$sql = "SELECT * FROM Url WHERE scraped = 'Y' AND splitted = 'N'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $title = $row['title']; // Get the title from the database
        $url_id = $row['id'];

        // Split the title into keywords (assuming title words separated by space)
        $keywords = explode(' ', $title);

        // Insert keywords into the Keywords table
        foreach ($keywords as $keyword) {
            $insert_keyword_sql = "INSERT INTO Keywords (url_id, keyword) VALUES ($url_id, '$keyword')";
            if ($conn->query($insert_keyword_sql) !== TRUE) {
                echo "Error inserting keyword: " . $conn->error;
            }
        }

        // Update 'splitted' status to 'Y' in the Url table
        $update_sql = "UPDATE Url SET splitted = 'Y' WHERE id = $url_id";
        if ($conn->query($update_sql) !== TRUE) {
            echo "Error updating record: " . $conn->error;
        }
    }
} else {
    echo "No URLs to split";
}

$conn->close();
?>
