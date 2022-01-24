<?php
//fetch the info and check whether the password matches or not
$passwordy = 'jksda890u234k,gc.hn34q98rweruhqd9jywreiuiuy89014790qweurewuygeriudijmergijresuiuigjhiowoyj73247yjq498923li92p0jhdghui9eiwpwomxnsdsuedu8923humwf08uk8s2shmwef9u329mhwezgyerk34s98yndgekf.df,dfh3dgxm8932uhmrfmx.rhmui23490u234675jhfxgbseuhrfgig34yjrggheretj84320u8erhfdruhwfc.rfgcuyd478tj8t7fg54cc34789ihfryh5tdy7itduohg78jhwduhkwdgjngeklk9009le0932ilsei9032isei2390iei0329kd2389ujsjkembhnmhbcuhbecuyrybxcyry7gd3479yj39534789r34789fheruiofh89eruhdf89d2khfsufh23td20o2oltiit98f3yhfkuzqaok,dohnrcgyq78t342rflefogh342899gskssg';
$password = htmlspecialchars($_GET["pass"]);
if ($passwordy === $password){
	$db_username = htmlspecialchars($_GET["db_username"]);
	$db_password = htmlspecialchars($_GET["db_password"]);
	$db_databe_name = htmlspecialchars($_GET["db_databe_name"]);
	$db_host = htmlspecialchars($_GET["db_host"]);

	// Create connection
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
