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

// Create Excel file (CSV format) content
$csvFileName = "keywords.csv";
$csvFile = fopen($csvFileName, 'w');

// Add headers
fputcsv($csvFile, ["Keyword", "Count"]);

// Add data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($csvFile, [$row['keyword'], $row['count']]);
}

fclose($csvFile);

// Set headers for file download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="keywords.xls"');

// Output the XLS data
readfile($csvFileName);

// Remove the temporary CSV file
unlink($csvFileName);

// Close the database connection
$conn->close();
?>
