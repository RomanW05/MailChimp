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
$sql = "UPDATE subscribers SET subscribed=1 WHERE hashed='$hashed'";
$result = $conn->query($sql);
$conn->close();

$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT name FROM subscribers WHERE hashed='$hashed'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "You have been successfully subscribed again to Lovely Doggos newsletter " .$row['name']. '.';
  }
}
$conn->close();
?>

<body>
</body>
</html>