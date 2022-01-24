<?php
$passwordy = 'jksda890u234k,gc.hn34q98rweruhqd9jsfdgywreiuiuy8901479adsf0qweuy5464ewuygeriudijmergijresuiuigjhiowoyj73247yjq498923li92p0jhdghui9eiwpwomxnsdsuedu8923humwf08uk8s2shmwef9u329mhwezgyerk34s98yndgekf.df,dfh3dgxm8932uhmrfmx.rhmui23490u234675jhfxgbseuhrfgig34yjrggheretj84320u8erhfdruhwfc.rfgcuyd478tj8t7fg54cc34789ihfryh5tdy7itduohg78jhwduhkwdgjngeklk9009le0932ilsei9032isei2390iei0329kd2389ujsjkembhnmhbcuhbecuyrybxcyry7gd3479yj39534789r34789fheruiofh89eruhdf89d2khfsufh23td20o2oltiit98f3yhfkuzqaok,dohnrcgyq78t342rflefogh342899gskssg';
$password = htmlspecialchars($_GET["pass"]);

if ($passwordy === $password){
	$date = date("Y-m-d-h-i");
	$db_username = 'domaiyxq';
	$db_password = 'QPDub04CXAW3';
	$db_databe_name = 'domaiyxq_lovelydoggos';
	$db_host = 'server282.web-hosting.com';

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