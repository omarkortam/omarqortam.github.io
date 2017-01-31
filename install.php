<?php
GLOBAL $config, $_CWD;
function changeStatus($id, $status)
{
	echo "<script>document.getElementById(\"$id\").className += \" status-$status\";</script>";
}
function mod_rewrite_support(){
	if(!function_exists("apache_get_modules"))
		return false;
	return in_array("mod_rewrite",apache_get_modules());
}
if(empty($_CWD)){
	die("Script must be run from index");
}
$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$current_url=substr($current_url,0,strrpos($current_url,"/")+1);

?>
<html>
<head>
	<title>Installing CPABuild</title>
	<style>
		div.status{
			display: list-item;
			margin-left: 1.3em;
			margin-bottom:10px;
			list-style-type: circle;
		}
		ol li{margin-top:6px;}
		.status-error{color:#9c0000}
		.status-success{color:green}
	</style>
</head>
<body>
<h1>Installing CPABuild Deployment Package</h1>
<hr>
<h3 id="cache">Cache Folder</h3>
<div class='status status-normal'>Checking for /cache folder...</div>
<?php
$cache_exists = file_exists($_CWD . "/cache");
if ($cache_exists) {
	echo "<div class='status status-success'>Cache folder exists!</div>";
	changeStatus("cache", "success");
} else {
	echo "<div class='status status-error'>Cache folder does not exist! Attempting to create...</div>";
	mkdir($_CWD . "/cache/");
	file_put_contents($_CWD . "/cache/install_text.txt", "Hello world!");
	if (file_exists($_CWD . "/cache/install_text.txt")) {
		echo "<div class='status status-success'>Created cache test file!</div>";
		changeStatus("cache", "success");
	} else {
		echo "<div class='status status-error'>Failed to create writeable cache. This will slow down your website!
<br>
<strong>IMPORTANT: To fix, please add public permissions on the folder you uploaded to (permissions code is 777). Detected folder is $_CWD - Then run this script again!</strong>
</div>";
		changeStatus("cache", "error");
	}
}
?>
<hr>
<h3 id="htaccess">HTACCESS</h3>
<p>
	Pretty URL test: <a href='<?php echo $current_url?>movies' target='_blank'><?php echo $current_url?>movies</a>
</p>
<p>
	Non-Pretty URL test: <a href='<?php echo $current_url?>?movies' target='_blank'><?php echo $current_url?>?movies</a> (Notice the added ? question mark)
</p>
<div class='status status-normal'>Checking for .htaccess file...</div>
<?php
$cache_exists = file_exists($_CWD . "/.htaccess");
if ($cache_exists) {
	echo "<div class='status status-success'>Htaccess exists! Checking if it's working...</div>";
	if(isset($_GET['pretty']) && $_GET['pretty']==1){
		echo "<div class='status status-success'>Htaccess running! Pretty url format should work!</div>";
		changeStatus("htaccess", "success");
	}
	else{
		echo "<div class='status status-error'>Htaccess is not running.</div>";
		changeStatus("htaccess", "error");
	}

} else {
	echo "<div class='status status-normal'>Checking for mod_rewrite support (will the server play nice?)</div>";
	if(!mod_rewrite_support()){
		echo "<div class='status status-error'>Mod Rewrite not found. htaccess probably wont work, but might! <strong>If you get a 403 error, delete the .htaccess file from your server.</strong></div>";
		changeStatus("htaccess", "error");
	}
	file_put_contents($_CWD . "/.htaccess", file_get_contents($_CWD . "/HTACCESS"));
	if(file_exists($_CWD . "/.htaccess")){
		echo "<div class='status status-success'>Created .htaccess file. Refresh this page to run a test. If you get an error 403, delete .htaccess file from your server (may have to view hidden files).</div>";
		changeStatus("htaccess", "error");
		unlink($_CWD . "/HTACCESS");
	}
	else{
		echo "<div class='status status-error'>Server rejected file creation. PHP does not have permission to write files. Make the directory have public permissions or just use the non-pretty format.</div>";
		changeStatus("htaccess", "error");
	}
}
?>
<hr>
<h1>Next Steps</h1>
<ol>
	<li>The sections above might ask you to fix something and re-run the script. Do those first. The Cache section is more important than the htaccess section.</li>
	<li>Try a private URL. View your private URLS <a href="https://members.cpabuild.com/privateURL" target="_blank">here</a>. If your private URI is "movies", your link will look like this: <a href='<?php echo $current_url?>?movies' target='_blank'><?php echo $current_url?>?movies</a></li>
	<li>Are you getting a <strong>403 Forbidden error</strong>? Delete the .htaccess (you may have to enable "hidden" files in your file manager). </li>
	<li>Change your homepage (currently this page). Edit the file <strong>config.php</strong> and change the line <pre>"default"=>"install",</pre> to <pre>"default"=>"movies",</pre> Where <strong>movies</strong> is the private URI (you can use any private uri as a homepage).</li>
	<li>When you are satisfied, delete the <strong>install.php</strong> file. If you ever need to run the installation again, just re-download the package from CPABuild.</li>
</ol>
</body>
</html>