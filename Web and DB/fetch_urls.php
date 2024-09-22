<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finalproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Url";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['url'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['scraped'] . "</td>";
        echo "<td>" . $row['splitted'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No URLs inserted yet</td></tr>";
}

$conn->close();
?>
