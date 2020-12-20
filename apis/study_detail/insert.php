<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: POST");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, passwordization, X-Requested-With");
  require __DIR__ . '/../dbconnection.php';
  require_once __DIR__ . '/../messages.php';
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  $msg['message'] = '';
  $message = new Messages();

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

        $msg['message'] = $insert_stmt->execute() ? $message->Success() : $message->Failure();
    } else {
        $msg['message'] = $message->EmptyField();
    }
} else {
    $msg['message'] = $message->MissingField();
}

echo json_encode($msg);