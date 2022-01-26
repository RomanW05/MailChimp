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

// Escape special characters, if any
$hashed = mysqli_real_escape_string($conn, $_GET['hashed']);

// Resubscribe the user
$sql = "UPDATE subscribers SET subscribed=1 WHERE hashed='$hashed'";
$result = $conn->query($sql);
$conn->close();

$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Display a message to the user saying he/she has been resubscribed
$sql = "SELECT name FROM subscribers WHERE hashed='$hashed'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while($row = $result->fetch_assoc()) {
    echo "You have been successfully subscribed again to YOUR_COMPANY newsletter " .$row['name']. '.';
  }
}

$conn->close();

?>
