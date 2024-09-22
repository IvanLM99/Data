<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finalproject";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch keywords and their counts from the database
$sql = "SELECT keyword, COUNT(*) as count FROM Keywords GROUP BY keyword";
$result = $conn->query($sql);

// Create an array to hold JSON data
$keywordsArray = array();
while ($row = $result->fetch_assoc()) {
    $keywordData = array(
        "word" => htmlspecialchars($row['keyword']),
        "count" => $row['count']
    );
    array_push($keywordsArray, $keywordData);
}

// Create JSON structure
$jsonStructure = array("keywords" => $keywordsArray);

// Set headers for JSON file download
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="keywords.json"');

// Output the JSON data
echo json_encode($jsonStructure, JSON_PRETTY_PRINT);

// Close the database connection
$conn->close();
?>
