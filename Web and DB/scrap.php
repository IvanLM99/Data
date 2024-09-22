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

// Fetch URLs to scrape
$sql = "SELECT * FROM Url WHERE scraped = 'N'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $url = $row['url'];
        
        // Use scraping techniques to get title content from $url
        $title = getTitle($url); // Function to get the title

        // Update 'title' and change status to 'Y' in the database
        $url_id = $row['id'];

        $update_sql = "UPDATE Url SET title = '$title', scraped = 'Y' WHERE id = $url_id";
        if ($conn->query($update_sql) !== TRUE) {
            echo "Error updating record: " . $conn->error;
        }
    }
} else {
    echo "No URLs to scrape";
}

$conn->close();

// Function to get the title of the URL (Replace this with your actual scraping function)
function getTitle($url) {
    $doc = new DOMDocument();
    @$doc->loadHTMLFile($url);
    return $doc->getElementsByTagName('title')->item(0)->nodeValue;
}
?>
