<?php

require_once __DIR__ . '/../headers/update.php';
require_once __DIR__ . '/../classes/validate.php';

if ($_SERVER["REQUEST_METHOD"] != "PUT"):
  echo json_encode(validate\Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$db = new CreateDBinstance();
$conn = $db->dbInstanceConnection();
$data = $db->setContent();

$table_study = $_SERVER['T_STUDY'];
$update_query = "UPDATE `$table_study` SET 
                  study_time = :study_time, 
                  project_id = :project_id, 
                  study_detail = :study_detail,
                  study_date = :study_date,
                  user_id = :user_id,
                  create_date = :create_date
                  WHERE id = :id";

// 変更対象が存在するかどうかを検索
if (isset($data->id)) {
  try {
    $post_id  = $data->id;
    $get_post = "SELECT * FROM `$table_study` WHERE id=:post_id";
    $get_stmt = $conn->prepare($get_post);
                $get_stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
                $get_stmt->execute();
    if ($get_stmt->rowCount() > 0) {
      if (400 < strlen($study_detail)):
        echo json_encode(validate\Validate::resultMessage(0, 422, 'The study details can be up to 400 characters.'));
        return;
      endif;
      $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
      $post_study_time     = updateBindValue($row, $data, 'study_time');
      $post_project_id     = updateBindValue($row, $data, 'project_id');
      $post_study_detail   = updateBindValue($row, $data, 'study_detail');
      $post_study_date     = updateBindValue($row, $data, 'study_date');
      $post_user_id        = updateBindValue($row, $data, 'user_id');
      $post_create_date    = $row['create_date'];

      $update_stmt = $conn->prepare($update_query);
      $update_stmt->bindValue(':id', $post_id, PDO::PARAM_INT);
      $update_stmt->bindValue(':study_time', $post_study_time, PDO::PARAM_INT);
      $update_stmt->bindValue(':project_id', $post_project_id, PDO::PARAM_INT);
      $update_stmt->bindValue(':study_detail', htmlspecialchars(strip_tags($post_study_detail)), PDO::PARAM_STR);
      $update_stmt->bindValue(':study_date', htmlspecialchars(strip_tags($post_study_date)), PDO::PARAM_STR);
      $update_stmt->bindValue(':user_id', $post_user_id, PDO::PARAM_INT);
      $update_stmt->bindValue(':create_date', $post_create_date, PDO::PARAM_STR);
      $update_stmt->execute();
      echo json_encode(validate\Validate::resultMessage(0, 200, 'Data updated successfully'));
    } 
  } catch (PDOException $e) {
    echo json_encode(validate\Validate::resultMessage(0, 500, $e->getMessage()));
  }
} else {
  echo json_encode(validate\Validate::resultMessage(0, 400, 'Invlid ID'));
}
