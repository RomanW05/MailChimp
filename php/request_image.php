<?php
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

// Escape special characters, if any
$hashed = mysqli_real_escape_string($conn, $_GET['hashed']);
$picture_number = mysqli_real_escape_string($conn, $_GET['newsletter_number']);

// Fetch the image. The "newsletter_number" is a mixed of the newsletter edition
// and the image associated to the section of the newsletter itself
list($newsletter_number, $image) = explode("_", $picture_number);
$picture = 'https://www.example.com/hidden_files/images/'. $picture_number. '.jpg';
$contents = file_get_contents($picture);
header('Content-type: image/jpeg');
echo $contents;

//Get ip location
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

// Fetch ip thanks to ip-api.com
$post = [
    '' => "{$ip}"];
$ch = curl_init("http://ip-api.com/php/{$ip}");  // Make the query

// Set options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);  // Search the endpoint: ip

// Execute the query
$result = curl_exec($ch);
$result = unserialize($result);
$country = $result["country"];
$city = $result["city"];

// Close the connection, release resources used
curl_close($ch);

// Detect OS
$user_agent = $_SERVER['HTTP_USER_AGENT'];  
function get_OS() { 
    global $user_agent;
    $os_platform  = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i'      =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
                    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}


// Detect browser
function get_browser() {
    global $user_agent;
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i'      => 'Internet Explorer',
        '/firefox/i'   => 'Firefox',
        '/safari/i'    => 'Safari',
        '/chrome/i'    => 'Chrome',
        '/edge/i'      => 'Edge',
        '/opera/i'     => 'Opera',
        '/netscape/i'  => 'Netscape',
        '/maxthon/i'   => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i'    => 'Handheld Browser'
                     );

    foreach ($browser_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $browser = $value;

    return $browser;
}

$date = date("Y-m-d-h-i");
$user_os = get_OS();
$user_browser = get_browser();

$sql = "INSERT INTO subscribers (hashed, newsletter_number, time_stamp, OS, browser, country, city) VALUES('$hashed', '$picture_number', '$date', '$user_os', '$user_browser', '$country', '$city')";
$result = $conn->query($sql);

$conn->close();

?>
