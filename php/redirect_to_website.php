<?php
// Credentials
$db_username = 'DATABASE_USERNAME';
$db_password = 'DATABASE_PASSWORD';
$db_databe_name = 'DATABASE_NAME';
$db_host = 'DATABASE_HOST';

// Connect to database and exit if it fails
$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add data to the database
$date = date("Y-m-d-h-i");
$hashed = mysqli_real_escape_string($conn, $_GET['hashed']);
$newsletter = mysqli_real_escape_string($conn, $_GET['newsletter']);
$sql = "INSERT INTO click_banner (hashed, newsletter_number, time_stamp) VALUES('$hashed', '$newsletter', '$date')";
$result = $conn->query($sql);
$conn->close();

// link to redirect to
header( 'Location: http://www.example.com');

?>
