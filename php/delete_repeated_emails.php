<?php
echo 'start <br>';

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
echo 'connected <br>';

// Fetch all ids and emails
$sql = "SELECT id, email FROM subscribers";
$result = $conn->query($sql);
echo 'sent query <br>';

// Create arrays to work with
$unique_emails = array();
$repeated_emails = array();

// Iterate through database and catch ids from repeated emails
while($row = $result->fetch_assoc()) {
    $email = $row["email"];  // Single email from database
    $guy_id = $row["id"];  // Single id from database
    if (in_array($email, $unique_emails)){
        array_push($repeated_emails, $guy_id);  // email already in list
    } else {
        array_push($unique_emails, $row["email"]);  // email not in list
    }
}

// iterate over $repeated_emails which is a list of ids and delete them
foreach ($repeated_emails as $elem){
    $sql = "DELETE FROM subscribers WHERE id ='$elem'";
    $result = $conn->query($sql);
    echo $result;
}

$conn->close();

?>
