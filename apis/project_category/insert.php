<?php
  require_once __DIR__ . '/../headers/insert.php';
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  $msg['message'] = '';
  $message = new Messages();

  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  $name = $data->name;
	$insert_query = "INSERT INTO `$table_project_category` (name) VALUES(:name)";
	
if(isset($name)) {  
    if (!empty($name)) {
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $msg['message'] = $insert_stmt->execute() ? $message->Success() : $message->Failure();
    } else {
        $msg['message'] = $message->EmptyField();
    }
} else {
    $msg['message'] = $message->MissingField();
}

echo json_encode($msg);