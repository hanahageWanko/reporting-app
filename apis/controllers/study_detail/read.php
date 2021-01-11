<?php
  require_once __DIR__ . '/../../headers/read.php';
  require_once __DIR__ . '/../../classes/validate.php';

  if ($_SERVER["REQUEST_METHOD"] != "GET"):
    echo json_encode(validate\Validate::resultMessage(0, 405, 'Method Not Allowed'));
    return;
  endif;

  $table_study            = $_SERVER['T_STUDY'];
  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  $table_users            = $_SERVER['T_USER'];

  if(isset($_GET['user_id'])) {
    $postId = filter_var($_GET['user_id'], FILTER_VALIDATE_INT, [
      'options' => [
        'default' => 'all_posts',
        'min_range' => 1
      ]
    ]);
  } else {
    $postId = 'all_posts';
  }

  $sql = is_numeric($postId)
        ? "SELECT s.id,
                  s.study_time,
                  s.project_id,
                  s.study_detail,
                  s.study_date,
                  s.user_id,
                  s.create_date,
                  p.name AS project_category,
                  u.user_name  
            FROM $table_study AS s
            LEFT OUTER JOIN $table_project_category AS p 
            ON s.project_id = p.id
            LEFT OUTER JOIN $table_users AS u
            ON s.user_id = u.id
            WHERE s.user_id = $postId;"
        : "SELECT s.id,
                  s.study_time,
                  s.project_id,
                  s.study_detail,
                  s.study_date,
                  s.user_id,
                  s.create_date,
                  p.name AS project_category,
                  u.user_name  
            FROM $table_study AS s
            LEFT OUTER JOIN $table_project_category AS p 
            ON s.project_id = p.id
            LEFT OUTER JOIN $table_users AS u
            ON s.user_id = u.id;";

  $fetchItem = ['id', 'study_time', 'project_id', 'study_detail', 'study_date', 'user_id', 'create_date', 'project_id', 'user_name'];
  $stmt = Database::fetch($sql, $fetchItem);
  if($stmt) {
    echo json_encode($stmt);
  } else {
    echo json_encode(validate\Validate::resultMessage(0, 400, 'No post found'));
  }

?>