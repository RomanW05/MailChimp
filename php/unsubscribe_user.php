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

// Unsubscribe user
$sql = "UPDATE subscribers SET subscribed=0 WHERE hashed='$hashed'";
$result = $conn->query($sql);
$conn->close();

$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Display message to user
$sql = "SELECT name, email FROM subscribers WHERE hashed='$hashed'";
$result = $conn->query($sql);

$email = '';
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $email = $row["email"];
    echo "It is a petty to see you go " . $row["name"]. ". You are welcome to comeback anytime you want.
    <br>
    You have been successfully unsubscribed from YOUR_COMPANY newsletter.
    <br><br>
    If you clicked 'unsubscribe' by mistake and want to be part of our family click <a href='https://www.example.com/hidden_files/resubscribe.php?hashed=" . $hashed. "'>Subscribe</a>";
  }
}
$conn->close();

$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Update database for analytics
$date = date("Y-m-d-h-i");
$sql = "INSERT INTO unsubscribed_time (hashed, email, time_stamp) VALUES('$hashed', '$email', '$date')";
$result = $conn->query($sql);
$conn->close();

?>
