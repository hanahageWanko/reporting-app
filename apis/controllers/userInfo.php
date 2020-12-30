<?php
require_once __DIR__ . '/../headers/userInfo.php';

require __DIR__.'/../middlewares/auth.php';

$allHeaders = getallheaders();
$db = Database::dbConnection();
$auth = new Auth($db, $allHeaders);
if($auth->isAuth()) {
  echo json_encode($auth->isAuth());
} else {
  echo json_encode(Validate::resultMessage(0, 401, 'Unauthorized'));
}

