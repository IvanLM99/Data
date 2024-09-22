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

// Check if there are superfluous words to delete in Keywords
$sqlDeleteSuperfluous = "DELETE FROM Keywords WHERE keyword IN (SELECT word FROM Superfluous WHERE word IN (SELECT keyword FROM Keywords))";
if ($conn->query($sqlDeleteSuperfluous) === TRUE) {
    // Check if there are changes to update in Keywords
    $sqlUpdateKeywords = "UPDATE Keywords AS k INNER JOIN Changes AS c ON k.keyword = c.original SET k.keyword = c.final";
    if ($conn->query($sqlUpdateKeywords) === TRUE) {
        echo "success";
    } else {
        echo "Error updating Keywords table: " . $conn->error;
    }
} else {
    echo "No superfluous words to delete or error deleting: " . $conn->error;
}

$conn->close();
?>
