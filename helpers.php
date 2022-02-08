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
}