<?php
  require_once __DIR__ . '/../headers/insert.php';
  require_once __DIR__ . '/../functions.php';
  
  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  $msg['message'] = '';

  $table_study     = $_SERVER['T_STUDY'];
  $study_time      = $data->study_time;
  $project_id  = $data->project_id;
  $study_detail    = $data->study_detail;
  $study_date      = $data->study_date;
  $user_id         = $data->user_id;
  $insert_query = "INSERT INTO `$table_study` (study_time, project_id, study_detail, study_date, user_id) VALUES(:study_time, :project_id, :study_detail, :study_date, :user_id)";



if (isset($study_time) && isset($project_id) && isset($study_detail) && isset($study_date)) {
    if (!empty($study_time) && !empty($project_id) && !empty($study_detail) && !empty($study_date)) {
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bindValue(':study_time'   , $study_time, PDO::PARAM_STR);
        $insert_stmt->bindValue(':project_id'   , $project_id, PDO::PARAM_INT);
        $insert_stmt->bindValue(':study_detail' , htmlspecialchars(strip_tags($study_detail)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':study_date'   , htmlspecialchars(strip_tags($study_date)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':user_id'      , $user_id, PDO::PARAM_INT);

        $msg['message'] = $insert_stmt->execute() ?  resultMessage(0, 201, 'Data Inserted Successfully') : resultMessage(0, 400, 'Data not Inserted');
    } else {
        $msg['message'] = resultMessage(0, 422, 'Empty field detected. Please fill all the fields');
    }
} else {
    $fields = ['fields' => ['study_time', 'project_id', 'study_detail', 'study_date', 'user_id']];
    $msg['message'] = resultMessage(0, 422, 'Please fill all the fields', $fields);
}

echo json_encode($msg);