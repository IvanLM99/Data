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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["url"])) {
    $url = $_POST["url"];

    // Default values for 'scrapped' and 'splitted'
    $scrapped = 'N';
    $splitted = 'N';

    // Prepare and bind the statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Url (url, scraped, splitted) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $url_param, $scrapped_param, $splitted_param);

    // Set parameters and execute the statement
    $url_param = $url;
    $scrapped_param = $scrapped;
    $splitted_param = $splitted;

    if ($stmt->execute()) {
        echo "success"; // Signal successful insertion
    } else {
        echo "Error: " . $stmt->error; // Output the specific error
    }

    $stmt->close(); // Close the statement
}

$conn->close(); // Close the connection
?>
