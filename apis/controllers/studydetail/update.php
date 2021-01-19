<?php
require_once __DIR__ . '/../../headers/update.php';

Session::redirect(isset($_SESSION["login"]), '/login');

  if (!Validate::requestType("PUT")) {
      exit();
  }

$data = json_decode(file_get_contents("php://input"));

$table_study = $_SERVER['T_STUDY'];
$updateQuery = "UPDATE `$table_study`
                SET 
                  study_time = :study_time, 
                  project_id = :project_id, 
                  study_detail = :study_detail,
                  study_date = :study_date,
                  user_id = :user_id,
                  create_date = :create_date
                WHERE id = :id";

// 変更対象が存在するかどうかを検索
if (!isset($data->id) || empty($data->id)) {
    echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
    return;
}

$postId  = $data->id;
$getPost = "SELECT * FROM `$table_study` WHERE id=:id";

try {
    $checkItem = Database::select($getPost, [':id' => $postId]);
    if ($checkItem->rowCount() === 0) {
        echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
        return;
    }

    if (!Validate::moreThanStr($study_detail, 400, 'The project category name can be up to 400 characters.')) {
        return;
    }

    $row = $checkItem->fetch(PDO::FETCH_ASSOC);
    $postStudyTime     = Database::updateBindValue($row, $data, 'study_time');
    $postProjectId     = Database::updateBindValue($row, $data, 'project_id');
    $postStudyDetail   = Database::updateBindValue($row, $data, 'study_detail');
    $postStudyDate     = Database::updateBindValue($row, $data, 'study_date');
    $postUserId        = Database::updateBindValue($row, $data, 'user_id');
    $postCreateDate    = $row['create_date'];

    $postItem = [
    ':id' => $postId,
    ':study_time' => $postStudyTime,
    ':project_id' => $posteProjectId,
    ':study_detail' => $postStudyDetail,
    ':study_date' => $postStudyDate,
    ':user_id' => $postUserId,
    ':create_date' => $postCreateDate
  ];

    Database::post($updateQuery, $postItem);
    echo json_encode(Validate::resultMessage(0, 200, 'Data updated successfully'));
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
}
