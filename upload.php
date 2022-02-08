<?php 
// include_once("db_connect.php");
if(!empty($_FILES)){ 
    $url = "http://dev.test/dragdrop/uploads/";
    $uploadDir = "uploads/";

    $fileName = $_FILES['file']['name'];

    $uploadedFile = $uploadDir.$fileName;    
    if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadedFile)) {
        $Date2 = date('Ymd_His') . "_" . substr(md5(uniqid()),0,4);
        $DotPos = strpos($uploadedFile, '.');
        $Extension = substr($uploadedFile, $DotPos, strlen($uploadedFile) - $DotPos);
        $newfile = $Date2 . $Extension;
        rename($uploadedFile, $uploadDir . $newfile);

        // $mysqlInsert = "INSERT INTO uploads (file_name, upload_time)VALUES('".$fileName."','".date("Y-m-d H:i:s")."')";
		// mysqli_query($conn, $mysqlInsert);
        
        echo json_encode(array(
            "code" => 200,
            "message" => "file successfully uploaded",
            "file" => $newfile,
            "url" => $url,
            "timestamp" => date("Y-m-d H:i:s")
        ));
    }else{
        echo json_encode(array(
        "code" => 500,
        "message" => "upload failed"
    ));    
    }

}else{
    echo json_encode(array(
        "code" => 400,
        "message" => "nothing uploaded"
    ));
}
?>
