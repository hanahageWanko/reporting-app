<?php
  require_once __DIR__ . '/../headers/insert.php';
  require_once __DIR__ . '/../functions.php';
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  $msg['message'] = '';

  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  $name = $data->name;
	$insert_query = "INSERT INTO `$table_project_category` (name) VALUES(:name)";
	
if(isset($name)) {  
    if (!empty($name)) {
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $msg['message'] = $insert_stmt->execute() ? resultMessage(0, 201, 'Data Inserted Successfully') : resultMessage(0, 400, 'Data not Inserted');
    } else {
        $msg['message'] = resultMessage(0, 422, 'Empty field detected. Please fill all the fields');
    }
} else {
    $fields = ['fields' => ['name']];
    $msg['message'] = resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields);
}

echo json_encode($msg);