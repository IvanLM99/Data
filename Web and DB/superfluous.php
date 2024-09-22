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

// Check if the POST data is set and not empty (for inserting superfluous word)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['word'])) {
    $superfluousWord = $_POST['word'];

    if (isset($_POST['superfluous_word_url'])) {
        // Insert into the 'Url' table
        $sql = "INSERT INTO Url (url) VALUES ('$superfluousWord')";
    } elseif (isset($_POST['superfluous_word_key'])) {
        // Insert into the 'Keywords' table
        $sql = "INSERT INTO Keywords (keyword) VALUES ('$superfluousWord')";
    } else {
        // Insert into the 'Superfluous' table
        $sql = "INSERT INTO Superfluous (word) VALUES ('$superfluousWord')";
    }

    if ($conn->query($sql) === TRUE) {
        // Fetch and construct the updated table HTML
        $sql = "SELECT word FROM Superfluous";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $tableHTML = "<table><thead><tr><th>Superfluous Word</th></tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                $tableHTML .= "<tr><td>" . $row['word'] . "</td></tr>";
            }
            $tableHTML .= "</tbody></table>";
            echo $tableHTML; // Return the updated table HTML
        } else {
            echo "<p>No superfluous words found</p>";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Fetch and display the existing superfluous words for the specific table
    // Modify the fetch logic based on the specific table requirement
    $sql = "SELECT word FROM Superfluous";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $tableHTML = "<table><thead><tr><th>Superfluous Word</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            $tableHTML .= "<tr><td>" . $row['word'] . "</td></tr>";
        }
        $tableHTML .= "</tbody></table>";
        echo $tableHTML; // Return the table HTML
    } else {
        echo "<p>No superfluous words found</p>";
    }
}

$conn->close();
?>
