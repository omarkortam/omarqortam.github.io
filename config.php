<?php
GLOBAL $config;

$config=array(
    "api_key"=>"56745dda23f8811da585",
    "debug"=>false, //WARNING, Setting this to true will show live errors on your website
    "server_url"=>"http://mirrors.cpabuild.com/api.php",
    "default"=>"install", //After install, change this to "default"=>"uri" where uri is your private CPABuild URI
	"cache_time"=>86400 //Time in seconds to store cache HTML files. 86400=1 Day, 3600=1 Hour, 0=Disabled (Not recommended)
);
