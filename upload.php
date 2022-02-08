<?php 
// include_once("db_connect.php");
require_once("./config.php");

function jsonreturn($returnParams){
  header("Content-type:text/json");
  die(json_encode($returnParams));
}

if(!empty($_FILES)){ 
  $url = $config["url"];
  $uploadDir = $config["uploadDir"];
  
  $fileName = $_FILES['file']['name'];
  
  $datestr = date('Ymd_His') . "_" . substr(md5(uniqid()),0,4);
  
  $fileparts = explode(".", $fileName);
  
  if(count($fileparts) < 2)
    jsonreturn(array(
      "code" => 400,
      "message" => "file must have an extension"
    ));
  
  $fileExtension = ".".$fileparts[count($fileparts)-1];
  $formattedFileName = $datestr.$fileExtension;
  $uploadedFile = $uploadDir.$formattedFileName;
  
  // below is a debug message CODE 100
  // jsonreturn(array(
  //   "code" => 100,
  //   "message" => $uploadedFile
  // ));
  
  if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadedFile)) {
    jsonreturn(array(
      "code" => 200,
      "message" => "file successfully uploaded",
      "file" => $formattedFileName,
      "url" => $url,
      "timestamp" => date("Y-m-d H:i:s")
    ));
  }else{
    jsonreturn(array(
      "code" => 500,
      "message" => "upload failed"
    ));    
  }
  
}else{
  jsonreturn(array(
    "code" => 400,
    "message" => "nothing uploaded"
  ));
}
?>