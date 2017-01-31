<?php
GLOBAL $config;
if($config['debug']===true){
    ini_set('display_errors', 1);
}
function getRequest(){
    $keys=array_keys($_GET);
    if(count($keys)==0){
        return "";
    }
    if(isset($_GET['u'])){
        return sanitizeRequest($_GET['u']);
    }
    return sanitizeRequest($keys[0]);

}
function sanitizeRequest($request){
    $request=preg_replace('/[^A-Za-z0-9-_~]/','',$request);
    return ($request);
}
function cacheContents($contents,$file){
    GLOBAL $_CWD,$config;
    $success=file_put_contents($file,$contents);
    if(!$success){
        chmod($_CWD."/cache", 0777); //Advanced users may want to remove this line!
        $success=file_put_contents($file,$contents);
    }
    if(!$success){
        debugMessage("CACHING ERROR! Run chmod 777 $_CWD/cache to enable write permissions.");
    }
}
function debugMessage($message){
    GLOBAL $config;
    if($config['debug']===true){
        echo "<p>DEBUG MESSAGE: ".$message."</p>\n<br>\n";
    }
}