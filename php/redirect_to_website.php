<?php
$db_username = 'domaiyxq';
$db_password = 'QPDub04CXAW3';
$db_databe_name = 'domaiyxq_lovelydoggos';
$db_host = 'server282.web-hosting.com';
$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$date = date("Y-m-d-h-i");
$hashed = mysqli_real_escape_string($conn, $_GET['hashed']);
$newsletter = mysqli_real_escape_string($conn, $_GET['newsletter']);
$sql = "INSERT INTO click_banner (hashed, newsletter, time_stamp) VALUES('$hashed', '$newsletter', '$date')";
$result = $conn->query($sql);
$conn->close();

header( 'Location: http://www.lovelydoggos.com');
?>