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

// Create XML content
$xml = new SimpleXMLElement('<keywords></keywords>');
while ($row = $result->fetch_assoc()) {
    $keyword = $xml->addChild('keyword');
    $keyword->addChild('word', htmlspecialchars($row['keyword']));
    $keyword->addChild('count', $row['count']);
}

// Set headers for file download
header('Content-Type: application/xml');
header('Content-Disposition: attachment; filename="keywords.xml"');

// Output the XML data
echo $xml->asXML();

// Close the database connection
$conn->close();
?>
