<?php
  // SET HEADER
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: POST");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  


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
    'missing_field' => 'Please fill all the fields | title, body, author',
  ];

  // VALUES & QUERY VARIABLE
  $title  = $data->title;
  $body   = $data->body;
  $author = $data->author;
  $insert_query = "INSERT INTO `posts`(title, body, author) VALUES(:title, :body, :author)";

// CHECK IF RECEIVED DATA FROM THE REQUEST
if (isset($title) && isset($body) && isset($author)) {
    // CHECK DATA VALUE IS EMPTY OR NOT
    if (!empty($title) && !empty($body) && !empty($author)) {
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':title', htmlspecialchars(strip_tags($data->title)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':body', htmlspecialchars(strip_tags($data->body)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':author', htmlspecialchars(strip_tags($data->author)), PDO::PARAM_STR);
      
        $msg['message'] = $insert_stmt->execute() ? $message['success'] : $message['failure'];
    } else {
        $msg['message'] = $message['empty_field'];
    }
} else {
    $msg['message'] = $message['missing_field'];
}
//ECHO DATA IN JSON FORMAT

echo  json_encode($msg);
