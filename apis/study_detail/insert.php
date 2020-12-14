<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: POST");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, passwordization, X-Requested-With");
  
  require __DIR__ . '/../database.php';
  $db_connection = new Database();
  $conn = $db_connection->dbConnection();

  $data = json_decode(file_get_contents("php://input"));

  $msg['message'] = '';
  $message = [
    'success' => 'Data Inserted Successfully',
    'failure' => 'Data not Inserted',
    'empty_field' => 'Oops! empty field detected. Please fill all the fields',
    'missing_field' => 'Please fill all the fields',
  ];

  $table_study     = $_SERVER['T_STUDY'];
  $study_time      = $data->study_time;
  $study_category  = $data->study_category;
  $study_detail    = $data->study_detail;
  $study_date      = $data->study_date;
  $user_id         = $data->user_id;
  $insert_query = "INSERT INTO `$table_study` (study_time, study_category, study_detail, study_date, user_id) VALUES(:study_time, :study_category, :study_detail, :study_date, :user_id)";



if (isset($study_time) && isset($study_category) && isset($study_detail) && isset($study_date)) {
    if (!empty($study_time) && !empty($study_category) && !empty($study_detail) && !empty($study_date)) {
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bindValue(':study_time'    , $study_time, PDO::PARAM_STR);
        $insert_stmt->bindValue(':study_category', htmlspecialchars(strip_tags($study_category)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':study_detail'  , htmlspecialchars(strip_tags($study_detail)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':study_date'    , htmlspecialchars(strip_tags($study_date)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':user_id'       , $user_id, PDO::PARAM_INT);

        $msg['message'] = $insert_stmt->execute() ? $message['success'] : $message['failure'];
    } else {
        $msg['message'] = $message['empty_field'];
    }
} else {
    $msg['message'] = $message['missing_field'];
}
//ECHO DATA IN JSON FORMAT

echo json_encode($msg);