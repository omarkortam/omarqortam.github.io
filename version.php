<?php
GLOBAL $config, $_CWD;
$_CWD = dirname(__FILE__);
include $_CWD . "/config.php";
include $_CWD . "/functions.php";

//First get our own file
$_local_version=file_get_contents($_CWD . "/version.txt");
if(!$_local_version){
	die("<span style='color:red;font-weight: bold;'>Failed to retrieve local version. Aborting.</span>");
}
else{
	echo "Local version hash: ".htmlspecialchars($_local_version)."<br>";
}

$ctx = stream_context_create(array('http' =>
	array(
		'timeout' => 1,  //1 second timeout
	)
                             ));

//Attempt to get HTML from main server
$_server_url = "http://cpabuild.com/public/package/version.php";
$_current_version_html = file_get_contents($_server_url, false, $ctx);

if(!$_current_version_html){
	die("<span style='color:red;font-weight: bold;'>Failed to retrieve version from server. Aborting.</span>");
}
else{
	echo "Current version hash on server: ".htmlspecialchars($_current_version_html)."<br>";
}

if(trim($_current_version_html) != trim($_local_version)){
	echo "<span style='color:red;font-weight: bold;'>Version is not current.</span> Please visit <a href='update.php'>/update.php</a> to update (needs to be enabled first).";
}
else{
	echo "<span style='color:green;font-weight: bold;'>Version is current.</span>";
}