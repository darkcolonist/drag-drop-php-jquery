<?php 
require_once("config.php");
require_once("helpers.php");

helpers::jsonreturn(array(
  "code" => 200,
  "ignorepreview" => $config["ignorepreview"],
  "includepreview" => $config["includepreview"],
));
?>