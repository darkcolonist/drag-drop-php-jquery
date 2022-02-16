<?php 
// include_once("db_connect.php");
require_once("config.php");
require_once("helpers.php");

if(!empty($_FILES)){ 
  $url = $config["url"];
  $uploadDir = $config["uploadDir"];
  
  $fileName = $_FILES['file']['name'];
  
  $fileparts = explode(".", $fileName);
  
  if(count($fileparts) < 2)
    helpers::jsonreturn(array(
      "code" => 400,
      "message" => "file must have an extension"
    ));
  
  $fileExtension = ".".$fileparts[count($fileparts)-1];

  array_pop($fileparts);
  $filepartscombined = implode("-",$fileparts);

  $formattedFileName = helpers::randhash(rand(4,20))."_"
    .helpers::cleanstring($filepartscombined)
    .$fileExtension;
  $uploadedFile = $uploadDir.$formattedFileName;
  
  // below is a debug message CODE 100
  // helpers::jsonreturn(array(
  //   "code" => 100,
  //   "message" => $uploadedFile
  // ));
  
  if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadedFile)) {
    helpers::jsonreturn(array(
      "code" => 200,
      "message" => "file successfully uploaded",
      "file" => $formattedFileName,
      "url" => $url,
      "timestamp" => helpers::getdate()
    ));
  }else{
    helpers::jsonreturn(array(
      "code" => 500,
      "message" => "upload failed"
    ));    
  }
  
}else{
  helpers::jsonreturn(array(
    "code" => 400,
    "message" => "nothing uploaded"
  ));
}
?>