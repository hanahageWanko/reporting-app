<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: POST");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, passwordization, X-Requested-With");
  require __DIR__ . '/../dbconnection.php';
  require_once __DIR__ . '/../messages.php';
?>