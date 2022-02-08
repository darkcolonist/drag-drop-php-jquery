<?php
require_once("config.php");
require_once("helpers.php");

$files = scandir($config["uploadDir"]);
$files = array_diff($files, $config["ignorefilelist"]);
$files = array_slice($files, $config["filelistlimit"] * -1);

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
  "uploads" => $uploads
));