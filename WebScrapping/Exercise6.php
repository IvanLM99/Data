<?php 
// FUNCTION curl 
function curl($url) 
{ 
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $info = curl_exec($ch); 
    curl_close($ch); 
    return $info; 
} 

// Function to connect to the database
function connectToDatabase()
{
    $servername = "localhost"; // Change to your database server name
    $username = "root"; // Change to your database username
    $password = ""; // Change to your database password
    $dbname = "exercise6"; // Change to your database name

    $con = mysqli_connect($servername, $username, $password, $dbname);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $con;
}

// Function to insert URL and title into PAGES table and split title into keywords
function processWebpage($url, $con)
{
    // Fetch webpage content
    $content = curl($url);

    // Extract title from the webpage
    if (strpos($url, 'marca.com') !== false) {
        preg_match('/title">(.*?)<\/title>/is', $content, $matches);
    } else {
        preg_match('/<title>(.*?)<\/title>/is', $content, $matches);
    }
    $title = isset($matches[1]) ? $matches[1] : '';

    // Insert URL and title into PAGES table
    $sql = "INSERT INTO PAGES (url, title) VALUES ('$url', '$title')";
    mysqli_query($con, $sql);
    $pageId = mysqli_insert_id($con);

    // Split title into individual words (keywords)
    $keywords = preg_split("/\s+/", $title);

    // Insert keywords into KEYWORDS table and relate them to the page in PAGE_KEYWORDS table
    foreach ($keywords as $keyword) {
        $keyword = mysqli_real_escape_string($con, $keyword);
        $sql = "INSERT IGNORE INTO KEYWORDS (keyword) VALUES ('$keyword')";
        mysqli_query($con, $sql);
        $keywordId = mysqli_insert_id($con);

        // Insert URL and keyword into PAGE_KEYWORDS table
        $sql = "INSERT INTO PAGE_KEYWORDS (url, keyword) VALUES ('$url', '$keyword')";
        mysqli_query($con, $sql);
    }
}


// MAIN PROGRAM 
$urls = [
    "https://www.marca.com/",
    "https://as.com/",
    "https://www.sport.es/es/",
    "https://www.mundodeportivo.com/",
    "https://www.estadiodeportivo.com/"
];

// Connect to the database
$con = connectToDatabase();

// Process each URL
foreach ($urls as $url) {
    processWebpage($url, $con);
}

mysqli_close($con); // Close the database connection
?>