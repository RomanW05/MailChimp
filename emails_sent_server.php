<?php
$passwordy = 'YOUR_PHP_PASSWORD';
$password = htmlspecialchars($_GET["pass"]);

if ($passwordy === $password){
	$date = date("Y-m-d-h-i");
	$db_username = 'DATABASE_USERNAME';
	$db_password = 'DATABASE_PASSWORD';
	$db_databe_name = 'DATABASE_NAME';
	$db_host = 'DATABASE_HOST';

	$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	$ambassadors_total = mysqli_real_escape_string($conn, $_GET['ambassadors_total']);
	$newsletter_number = mysqli_real_escape_string($conn, $_GET['newsletter_number']);

	$sql = "INSERT INTO newsletters_sent (ambassadors_total, newsletter_number, time_stamp) VALUES('$ambassadors_total', '$newsletter_number', '$date')";
	$result = $conn->query($sql);

	echo $result;
	echo ' done';

	$conn->close();
}
?>
