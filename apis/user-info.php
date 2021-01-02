<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__ . '/classes/dbconnection.php';
require __DIR__.'/middlewares/auth.php';
require_once __DIR__ . '/classes/validate.php';

$allHeaders = getallheaders();
$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();
$auth = new Auth($conn, $allHeaders);
if($auth->isAuth()) {
  echo json_encode($auth->isAuth());
} else {
  echo json_encode(validate\Validate::resultMessage(0, 401, 'Unauthorized'));
}

