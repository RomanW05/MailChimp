<?php
$db_username = 'domaiyxq';
$db_password = 'QPDub04CXAW3';
$db_databe_name = 'domaiyxq_lovelydoggos';
$db_host = 'server282.web-hosting.com';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Escape special characters, if any
$hashed = mysqli_real_escape_string($conn, $_GET['hashed']);
$sql = "UPDATE subscribers SET subscribed=0 WHERE hashed='$hashed'";
$result = $conn->query($sql);
$conn->close();

$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT name, email FROM subscribers WHERE hashed='$hashed'";
$result = $conn->query($sql);

$email = '';
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $email = $row["email"];
    echo "It is a petty to see you go " . $row["name"]. ". You are welcome to comeback anytime you want.
    <br>
    You have been successfully unsubscribed from Lovely Doggos newsletter.
    <br><br>
    If you clicked 'unsubscribe' by mistake and want to be part of our family click <a href='https://www.domainredirectme.com/lovelydoggos/update_db/resubscribe.php?hashed=" . $hashed. "'>Subscribe</a>";
  }
}
$conn->close();

$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$date = date("Y-m-d-h-i");
$sql = "INSERT INTO unsubscribed_time (hashed, email, time_stamp) VALUES('$hashed', '$email', '$date')";
$result = $conn->query($sql);
$conn->close();
?>

<body>
</body>
</html>