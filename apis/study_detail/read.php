<?php
   require_once __DIR__ . '/../headers/read.php';
   require_once __DIR__ . '/../functions.php';

  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();

  $table_study            = $_SERVER['T_STUDY'];
  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  $table_users            = $_SERVER['T_USER'];

  if(isset($_GET['user_id'])) {
    $post_id = filter_var($_GET['user_id'], FILTER_VALIDATE_INT, [
      'options' => [
        'default' => 'all_posts',
        'min_range' => 1
      ]
    ]);
  } else {
    $post_id = 'all_posts';
  }

  $sql = is_numeric($post_id)
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
            WHERE s.user_id = $post_id;"
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

  $stmt = $conn->prepare($sql);

  $stmt->execute();

  if($stmt->rowCount() > 0) {
    $post_array = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $post_data = [
        'id'               => $row['id'],
        'study_time'       => $row['study_time'], 
        'project_id'       => $row['project_id'],
        'study_detail'     => $row['study_detail'],
        'study_date'       => $row['study_date'],
        'user_id'          => $row['user_id'],
        'create_date'      => $row['create_date'],
        'project_category' => $row['project_category'],
        'user_name'        => $row['user_name']
      ];
      array_push($post_array, $post_data);
    }
    echo json_encode($post_array);
    
  } else {
    echo json_encode(resultMessage(0, 400, 'No post found'));
  }

?>