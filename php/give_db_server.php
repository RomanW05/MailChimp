<?php
//fetch the info and check whether the password matches or not
$passwordy = 'YOUR_PHP_PASSWORD';
$password = htmlspecialchars($_GET["pass"]);
if ($passwordy === $password){
	$db_username = htmlspecialchars($_GET["db_username"]);
	$db_password = htmlspecialchars($_GET["db_password"]);
	$db_databe_name = htmlspecialchars($_GET["db_databe_name"]);
	$db_host = htmlspecialchars($_GET["db_host"]);

	// Connect to database and exit if it fails
	$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	//select the hash and compare if it is there, if it isn't, add it to the db
	$sql = "SELECT id, name, email, subscribed, hashed, coupon FROM subscribers";
	// $sql->bind_param("s", $hashed);

	$result = $conn->query($sql);
	foreach($result as $elem){
		echo '[' .$elem['id']. ',' .$elem['name']. ',' .$elem['email']. ',' .$elem['subscribed']. ',' .$elem['hashed']. ',' .$elem['coupon']. ']';
	}
	if ($result->num_rows > 0) {
		echo $result;
	}
	$conn->close();
}
?>
