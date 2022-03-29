<?php
require_once("config.php");
require_once("helpers.php");

$files = scandir($config["uploadDir"]);
$files = array_diff($files, $config["ignorefilelist"]);

$files = array_reverse($files);
$chunks = array_chunk($files, $config["filelistlimit"]);

if(!empty($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0){
  if($_GET["page"] > count($chunks)-1){
    helpers::jsonreturn(array(
      "code" => 200,
      "limit" => $config["filelistlimit"],
      "uploads" => array()
    ));    
  }
  $files = $chunks[$_GET["page"]];
}else{
  $files = $chunks[0];
  $files = array_reverse($files);
}


// $files = glob($config["uploadDir"]."*");

$uploads = [];
foreach ($files as $filekey => $fileval) {
  $filets = filectime($config["uploadDir"].$fileval);
  $filedsdate = helpers::getdate(filectime($config["uploadDir"].$fileval));
  
  $expires = $config["uploadexpires"] - (strtotime("now") - strtotime($filedsdate));
  if($expires < 0) $expires = 0;

  //$other = [];
  if($config["uploadexpires"] !== 0 && $expires === 0){
    if($config["deleteexpired"])
      deleteFile($config["uploadDir"].$fileval);
  }else{
    $uploads[] = array(
      "url" => $config["url"].$fileval,
      "timestamp" => $filedsdate,
      "expires" => $expires,
      //"other" => $other
    );
  }
}

function deleteFile($file){
  unlink($file);
  return $file;
}

helpers::jsonreturn(array(
  "code" => 200,
  "limit" => $config["filelistlimit"],
  "uploads" => $uploads
));