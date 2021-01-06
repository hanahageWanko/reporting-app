<?php
  require_once __DIR__ . '/../headers/insert.php';
  require_once __DIR__ . '/../classes/validate.php';

  if ($_SERVER["REQUEST_METHOD"] != "POST"):
    echo json_encode(validate\Validate::resultMessage(0, 405, 'Method Not Allowed'));
    return;
  endif;

  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $data = $db->setContent();

  if (!isset($study_time)
    || !isset($project_id)
    || !isset($study_detail)
    || !isset($study_date)
    || empty($study_time)
    || empty($project_id)
    || empty(trim($study_detail))
    || empty($study_date)
    ):
    $fields = ['fields' => ['study_time', 'project_id', 'study_detail', 'study_date', 'user_id']];
    echo json_encode(validate\Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
    return;
  endif;


  $table_study     = $_SERVER['T_STUDY'];
  $study_time      = $data->study_time;
  $project_id      = $data->project_id;
  $study_detail    = $data->study_detail;
  $study_date      = $data->study_date;
  $user_id         = $data->user_id;
  $insert_query = "INSERT INTO `$table_study` (study_time, project_id, study_detail, study_date, user_id) VALUES(:study_time, :project_id, :study_detail, :study_date, :user_id)";

if (400 < strlen($study_detail)):
    echo json_encode(validate\Validate::resultMessage(0, 422, 'The study details can be up to 400 characters.'));
    return;
endif;

try {
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bindValue(':study_time', $study_time, PDO::PARAM_STR);
    $insert_stmt->bindValue(':project_id', $project_id, PDO::PARAM_INT);
    $insert_stmt->bindValue(':study_detail', htmlspecialchars(strip_tags($study_detail)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':study_date', htmlspecialchars(strip_tags($study_date)), PDO::PARAM_STR);
    $insert_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $insert_stmt->execute();
    echo json_encode(validate\Validate::resultMessage(0, 201, 'Data Inserted Successfully'));
} catch (PDOException $e) {
    echo json_encode(validate\Validate::resultMessage(0, 500, $e->getMessage()));
    return;
}
