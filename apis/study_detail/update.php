<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require __DIR__ . '/../dbconnection.php';
require_once __DIR__ . '/../messages.php';

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$table_study = $_SERVER['T_STUDY'];

$msg['message'] = '';
$message = new Messages();

$study_time     = $data->study_time;
$project_category = $data->project_category;
$study_detail   = $data->study_detail;
$study_date     = $data->study_date;
$update_query = "UPDATE `$table_study` SET 
                  study_time = :study_time, 
                  project_category = :project_category, 
                  study_detail = :study_detail,
                  study_date = :study_date,
                  user_id = :user_id,
                  create_date = :create_date
                  WHERE id = :id";

// 変更対象が存在するかどうかを検索
if (isset($data->id)) {
  $post_id  = $data->id;
  $get_post = "SELECT * FROM `$table_study` WHERE id=:post_id";
  $get_stmt = $conn->prepare($get_post);
  $get_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
  $get_stmt->execute();
  if($get_stmt->rowCount() > 0) {
    $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
    $post_study_time     = isset($study_time)      ? $study_time      : $row['study_time'];
    $post_project_category = isset($project_category)  ? $project_category  : $row['project_category'];
    $post_study_detail   = isset($study_detail)    ? $study_detail    : $row['study_detail'];
    $post_study_date     = isset($study_date)      ? $study_date      : $row['study_date'];
    $post_user_id        = isset($user_id) ? $user_id : $row['user_id'];
    $post_create_date    = $row['create_date']; 

    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindValue(':id',             $post_id, PDO::PARAM_INT);
    $update_stmt->bindValue(':study_time',     $post_study_time, PDO::PARAM_INT);
    $update_stmt->bindValue(':project_category', htmlspecialchars(strip_tags($post_project_category)),PDO::PARAM_STR);
    $update_stmt->bindValue(':study_detail',   htmlspecialchars(strip_tags($post_study_detail)),PDO::PARAM_STR);
    $update_stmt->bindValue(':study_date',     htmlspecialchars(strip_tags($post_study_date)), PDO::PARAM_STR);
    $update_stmt->bindValue(':user_id',        $post_user_id, PDO::PARAM_INT);
    $update_stmt->bindValue(':create_date',    $post_create_date, PDO::PARAM_STR);
  }
  $msg['message'] = $update_stmt->execute() ? $message->Success() : $message->Failure(); 

} else {
  $msg['message'] = $message->InvlidId();
}

echo json_encode($msg);
