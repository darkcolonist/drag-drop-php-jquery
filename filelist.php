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
  $uploads[] = array(
    "url" => $config["url"].$fileval,
    "timestamp" => $filedsdate
  );
}

helpers::jsonreturn(array(
  "code" => 200,
  "limit" => $config["filelistlimit"],
  "uploads" => $uploads
));