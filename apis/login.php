<?php
require_once __DIR__ . '/headers/insert.php';
require __DIR__.'/classes/jwtHandler.php';
require_once __DIR__ . '/classes/validate.php';


if ($_SERVER["REQUEST_METHOD"] != "POST"):
  echo json_encode(validate\Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();

if (!isset($data->email)
  || !isset($data->password)
  || empty(trim($data->email))
  || empty(trim($data->password))
  ):
  $fields = ['fields' => ['email','password']];
  echo json_encode(validate\Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
  return;
endif;

$email = trim($data->email);
$password  =trim($data->password);
$table_users = $_SERVER['T_USER'];

if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
  echo json_encode(validate\Validate::resultMessage(0,422,'Invalid Email Address!'));
  return;
endif;

if(strlen($password) < 8):
  echo json_encode(validate\Validate::resultMessage(0,422,'Your password must be at least 8 characters long!'));
  return;
endif;

try{

  $fetch_user_by_email = "SELECT * FROM $table_users WHERE `email`=:email";
  $query_stmt = $conn->prepare($fetch_user_by_email);
  $query_stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $query_stmt->execute();

  if ($query_stmt->rowCount()):
    $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
    $check_password = password_verify($password, $row['password']);
    if($check_password):
      $jwt = new JwtHandler();
      $token = $jwt->_jwt_encode_data(
        'http://127.0.0.1:8080/apis/',
        array("user_id" => $row['id'])
      );
      // echo json_encode(validate\Validate::resultMessage(1, 200, 'You have successfully logged in!', $token)); 
      echo json_encode(
        [
          'success' => 1,
          'message' => 'You have successfully logged in.',
          'token' => $token
        ]
      );
    else:
      echo json_encode(validate\Validate::resultMessage(0, 422, 'Invalid Password'));  
    endif;
  else:
    echo json_encode(validate\Validate::resultMessage(0, 400, 'Invalid address.'));
  endif;

} catch (PDOException $e) {
  echo json_encode(validate\Validate::resultMessage(0, 500, $e->getMessage()));
}


