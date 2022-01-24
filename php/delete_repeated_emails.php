<?php
echo 'start <br>';
$db_username = 'domaiyxq';
$db_password = 'QPDub04CXAW3';
$db_databe_name = 'domaiyxq_lovelydoggos';
$db_host = 'server282.web-hosting.com';

$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

echo 'connected <br>';

$sql = "SELECT id, email FROM subscribers";

$result = $conn->query($sql);

echo 'sent query <br>';

$ids = array();
$repeated_email = array();
while($row = $result->fetch_assoc()) {
	$email = $row["email"];
	$guy_id = $row["id"];
    // echo "id: " . $row["id"]. " - Email: " . $row["email"]. "<br>";
    //$sql1 = "SELECT id FROM subscribers WHERE email='$email'";
    //$result1 = $conn->query($sql1);
    //echo $result1;

    //if ($result1->num_rows > 0){
    //	$repeated_email = $result2->fetch_assoc();
    //	echo $repeated_email;
    //}
    if (in_array($email, $ids)){
    	array_push($repeated_email, $guy_id);
    } else {
    	array_push($ids, $row["email"]);
    }

}
$conn->close();



$sql = "SELECT id, email FROM subscribers";

$result = $conn->query($sql);


foreach ($repeated_email as $elem){
	$conn = new mysqli($db_host, $db_username, $db_password, $db_databe_name);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "DELETE FROM subscribers WHERE id ='$elem'";
	$result = $conn->query($sql);

	echo $result;
	$conn->close();

}












// for ($id = 1; $id = $result; $id++){
// 	$sql = "SELECT email FROM subscribers WHERE id='$id'";
// 	$idresult = $conn->query($sql);
// 	if ($idresult > 1){
// 		$emails = $result->fetch_assoc();
// 		echo $emails;
// 	}
// }
	// $emails = $result->fetch_assoc();






// $result = mysqli_query($conn, $query);
// echo $result;

// if ($result->num_rows > 0) {
//   // output data of each row
//   while($row = $result->fetch_assoc()) {
//     echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Hashed: " . $row["hashed"]. "<br>";
//   }
// } else {
//   echo "0 results";
// }
// $conn->close();

?>