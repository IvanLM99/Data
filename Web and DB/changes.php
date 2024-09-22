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

// Check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch data for GET request and send it back
    $sql = "SELECT * FROM changes"; // Assuming your table name is 'changes'
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Output the rows as required
            echo "<tr><td>" . $row["original"] . "</td><td>" . $row["final"] . "</td></tr>";
        }
    } else {
        echo "0 results";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the POST data is set and not empty (for inserting change words)
    if (isset($_POST['original']) && isset($_POST['final'])) {
        $originalWord = $_POST['original'];
        $finalWord = $_POST['final'];

        // Check if either word is empty before insertion
        if (empty($originalWord) || empty($finalWord)) {
            echo "Empty words cannot be inserted";
        } else {
            // Prepare and bind the INSERT statement
            $stmt = $conn->prepare("INSERT INTO changes (original, final) VALUES (?, ?)");
            $stmt->bind_param("ss", $originalWord, $finalWord);

            // Execute the statement
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    } else {
        echo "Invalid request";
    }
} else {
    echo "Invalid request";
}

// Close connection
$conn->close();
?>
