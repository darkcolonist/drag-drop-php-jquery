<?php 
class helpers{
  static function jsonreturn(Array $args){
    header("Content-type:application/json");
    die(json_encode($args));
  }

  static function getdate($ts = null){
    $format = "Y-m-d H:i:s O";

    if($ts != null)
      return date($format, $ts);
    else
      return date($format);
  }

  static function cleanstring($string){
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
  }

  static function randhash($length = 8){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    // return substr(md5(uniqid()),0,$length);
  }

  static function createfilename($fileparts){
    $maxfilelen = 32;
    $fileExtension = ".".strtolower($fileparts[count($fileparts)-1]);

    array_pop($fileparts);
    $filepartscombined = implode("-",$fileparts);

    $hashlength = rand(4,20);
    $remainingfilelen = $maxfilelen - $hashlength;
    $cleaned = helpers::cleanstring($filepartscombined);
    $cleanedlen = strlen($cleaned);

    if(($cleanedlen + $hashlength) < $maxfilelen){
      $hashlength = $maxfilelen - $cleanedlen;
    }else{
      $cleaned = substr($cleaned, -1 * $remainingfilelen);
    }

    $formattedFileName = helpers::randhash($hashlength)."_"
      .$cleaned
      .$fileExtension;

    return $formattedFileName;
  }
}