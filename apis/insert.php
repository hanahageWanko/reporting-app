<?php
  // SET HEADER
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: POST");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, passwordization, X-Requested-With");
  
  // INCLUDING DATABASE AND MAKING OBJECT
  require './database.php';
  $db_connection = new Database();
  $conn = $db_connection->dbConnection();

  // GET DATA FORM REQUEST[ php raw data ]
  $data = json_decode(file_get_contents("php://input"));

  //CREATE MESSAGE ARRAY AND SET EMPTY
  $msg['message'] = '';
  $message = [
    'success' => 'Data Inserted Successfully',
    'failure' => 'Data not Inserted',
    'empty_field' => 'Oops! empty field detected. Please fill all the fields',
    'missing_field' => 'Please fill all the fields | user_name, email, password',
  ];

  // VALUES & QUERY VARIABLE
  $table_users = $_SERVER['T_USER'];
  $user_name  = $data->user_name;
  $email   = $data->email;
  $password = $data->password;
  $insert_query = "INSERT INTO `$table_users` (user_name, email, password) VALUES(:user_name, :email, :password)";

// CHECK IF RECEIVED DATA FROM THE REQUEST
if (isset($user_name) && isset($email) && isset($password)) {
    // CHECK DATA VALUE IS EMPTY OR NOT
    if (!empty($user_name) && !empty($email) && !empty($password)) {
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':user_name', htmlspecialchars(strip_tags($user_name)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':email', htmlspecialchars(strip_tags($email)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':password', htmlspecialchars(strip_tags($password)), PDO::PARAM_STR);
      
        $msg['message'] = $insert_stmt->execute() ? $message['success'] : $message['failure'];
    } else {
        $msg['message'] = $message['empty_field'];
    }
} else {
    $msg['message'] = $message['missing_field'];
}
//ECHO DATA IN JSON FORMAT

echo json_encode($msg);
