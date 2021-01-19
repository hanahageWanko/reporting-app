<?php
  require_once __DIR__ . '/../../headers/insert.php';

  Session::redirect(isset($_SESSION["login"]), '/login');

  if (!Validate::requestType("POST")) {
      exit();
  }

  $data = json_decode(file_get_contents("php://input"));
  if (!Validate::dataValidate($data)) {
      $fields = ['fields' => ['study_time', 'project_id', 'study_detail', 'study_date', 'user_id']];
      echo json_encode(Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
      return;
  }

  if (!Validate::moreThanStr($study_detail, 400, 'The project category name can be up to 400 characters.')) {
      return;
  }

  $table_study     = $_SERVER['T_STUDY'];
  $table_users     = $_SERVER['T_USER'];
  $study_time      = $data->study_time;
  $project_id      = $data->project_id;
  $study_detail    = $data->study_detail;
  $study_date      = $data->study_date;
  $user_id         = $data->user_id;
  $query = "INSERT INTO `$table_study` (study_time, project_id, study_detail, study_date, user_id) VALUES(:study_time, :project_id, :study_detail, :study_date, :user_id)";
  $checkUserQuery = "SELECT `id` FROM $table_users WHERE `id`=:user_id";

try {
    $checkUser = Database::select($checkUserQuery, [':user_id' => $user_id]);
    // 存在しないユーザの場合、エラーにする
    if ($checkUser->rowCount() === 0) {
        echo json_encode(Validate::resultMessage(0, 422, 'This user does not exist.'));
        return;
    }
    $insert = Database::post($query, ['study_time'=>$study_time, 'project_id'=>$project_id, 'study_detail'=>$study_detail, 'study_date'=>$study_date, 'user_id'=>$user_id]);
    if ($insert) {
        echo json_encode(Validate::resultMessage(0, 201, 'Data Inserted Successfully'));
    }
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
    return;
}
