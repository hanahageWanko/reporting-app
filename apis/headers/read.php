<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Allow-Credentials: true");
  header("Content-Type: application/json; charset=UTF-8");
  require __DIR__ . '/../dbconnection.php';
  require_once __DIR__ . '/../messages.php';
?>