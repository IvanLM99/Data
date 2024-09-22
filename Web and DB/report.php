<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finalproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch keyword counts
$sql = "SELECT keyword, COUNT(*) AS count FROM Keywords GROUP BY keyword";
$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Sort the data by count in ascending order
usort($data, function($a, $b) {
    return $a['count'] - $b['count'];
});

$conn->close();

echo json_encode($data);
?>
