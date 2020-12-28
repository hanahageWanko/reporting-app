<?php
  require_once __DIR__ . '/../headers/insert.php';
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  $returnData = [];
  $message = new Messages();
  
  if ($_SERVER["REQUEST_METHOD"] != "POST"):
    echo json_encode($message->Message(0, 404, 'Page Not Found!'));
    return;
  endif;

  if (!isset($data->user_name)
    || !isset($data->email)
    || !isset($data->password)
    || empty(trim($data->user_name))
    || empty(trim($data->email))
    || empty(trim($data->password))
    ):
    $fields = ['fields' => ['user_name', 'email', 'password']];
    echo json_encode($message->Message(0, 422, 'Please Fill in all Required Fields!', $fields));
    return;
  endif;


$table_users = $_SERVER['T_USER'];
$user_name   = trim($data->user_name);
$email       = trim($data->email);
$password    = trim($data->password);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)):
    echo json_encode($message->Message(0, 422, 'Invalid Email Address!'));
    return;
endif;

if (strlen($password) < 8):
    echo json_encode($message->Message(0, 422, 'Your password must be at least 8 characters long!'));
    return;
endif;

if (strlen($user_name) < 3):
    echo json_encode($message->Message(0, 422, 'Your name must be at least 3 characters long!'));
    return;
endif;

try {
    $check_email = "SELECT `email` FROM $table_users WHERE `email`=:email";
    $check_email_stmt = $conn->prepare($check_email);
    $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $check_email_stmt->execute();

    if ($check_email_stmt->rowCount()):
      echo json_encode($message->Message(0, 422, 'This E-mail already in use!'));
			return;
		else:
      $insert_query = "INSERT INTO `$table_users` (user_name, email, password) VALUES(:user_name, :email, :password)";
			$insert_stmt = $conn->prepare($insert_query);
			$insert_stmt->bindValue(':user_name', htmlspecialchars(strip_tags($user_name)), PDO::PARAM_STR);
			$insert_stmt->bindValue(':email', htmlspecialchars(strip_tags($email)), PDO::PARAM_STR);
			$insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
			$insert_stmt->execute();
			echo json_encode($message->Message(1, 201, 'You have successfully registered.'));
    endif;
} catch (PDOException $e) {
    echo json_encode($message->Message(0, 500, $e->getMessage()));
    return;
}
