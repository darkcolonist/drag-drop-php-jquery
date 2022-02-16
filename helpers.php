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

  static function randhash($length){
    return substr(md5(uniqid()),0,$length);
  }
}