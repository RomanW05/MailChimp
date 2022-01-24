<?php
//fetch the info and check whether the password matches or not
$passwordy = 'CUSTOM_PASSWORD_PHP';
$password = htmlspecialchars($_GET["pass"]);
if ($passwordy === $password){
	$db_username = htmlspecialchars($_GET["db_username"]);
	$db_password = htmlspecialchars($_GET["db_password"]);
	$db_databe_name = htmlspecialchars($_GET["db_databe_name"]);
	$db_host = htmlspecialchars($_GET["db_host"]);
	$name = htmlspecialchars($_GET["name"]);
	$email = htmlspecialchars($_GET["email"]);
	$subscribed = htmlspecialchars($_GET["subscribed"]);
	$hashed = htmlspecialchars($_GET["hashed"]);

	// Create connection
	$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
	if ($conn->connect_error) {
	  die("Connection failed: " . $conn->connect_error);
	}

	//select the hash and compare if it is there, if it isn't, add it to the db
	$sql = "SELECT id, name, email, subscribed, hashed FROM subscribers WHERE email='$email'";
	// $sql->bind_param("s", $hashed);

	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo 'already added';
	} else {
	  $sql = "INSERT INTO subscribers (name, email, subscribed, hashed) VALUES('$name', '$email', '$subscribed', '$hashed')";
	  $result = $conn->query($sql);
	  echo $result;
	  // echo "Successfully added $name into the database";
	}
	$conn->close();
}
?>
