<?php
GLOBAL $config,$_CWD,$pretty_url_mode;
$_CWD=dirname(__FILE__);
include $_CWD."/config.php";
include $_CWD."/functions.php";
$pretty_url_mode=(isset($_GET['pretty']) && $_GET['pretty']==1) ? 1 : 0;
$debug_mode=($config['debug']===true);

$_request=getRequest();
if($debug_mode){
	debugMessage("Dumping get variables: ");
	var_dump($_GET);
	debugMessage("Dumping request: ");
	var_dump($_request);
}

if($_request=="")$_request=$config['default'];


if($_request=="homepage"){
	include $_CWD."/homepage.php";
	die;
}
if($_request=="install" && file_exists($_CWD."/install.php")){
	include $_CWD."/install.php";
	die;
}

//Attempt to read cache file
$cache_file_name=$config['api_key']."_"."$_request".".html";
$_cache_file=$_CWD."/cache/".$cache_file_name;

if(file_exists($_cache_file)){
	if($debug_mode){
		debugMessage("Found cache file $_cache_file - opening");
	}

    include $_cache_file;
	$_cache_file_creation_unix=filemtime($_cache_file);
	if($_cache_file_creation_unix && $_cache_file_creation_unix-time()>(int)$config['cache_time']){
		//File is more than a day old (or cache time setting)! Delete so it will update!
		unlink($_cache_file);
	}
    die;
}
$ctx = stream_context_create(array('http'=>
	array(
		'timeout' => 1,  //1 second timeout
	)
));

//Attempt to get HTML from main server
$_server_url=$config['server_url']."?".http_build_query(array(
        "request"=>$_request,
        "api"=>$config['api_key'],
		"pretty_url"=>$pretty_url_mode
    ));
if($debug_mode){
	debugMessage("Using server url - $_server_url");
}
$_html_contents=@file_get_contents($_server_url,false,$ctx);
if($_html_contents){
    echo $_html_contents;
    cacheContents($_html_contents,$_cache_file);
    die;
}
else{
	include $_CWD."/404.php";
	die;
}