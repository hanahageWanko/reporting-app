<?php
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Allow-Credentials: true");
  header("Content-Type: application/json; charset=UTF-8");
  require __DIR__ . '/../dbconnection.php';
  require_once __DIR__ . '/../messages.php';

  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();
  $message = new Messages();

  $table_study = $_SERVER['T_STUDY'];
  if(isset($_GET['id'])) {
    $post_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
      'options' => [
        'default' => 'all_posts',
        'min_range' => 1
      ]
    ]);
  } else {
    $_post_id = 'all_posts';
  }

  $sql = is_numeric($post_id)
         ? "SELECT * FROM `$table_study` WHERE id ='$post_id'"
         : "SELECT * FROM `$table_study`";

  $stmt = $conn->prepare($sql);

  $stmt->execute();

  if($stmt->rowCount() > 0) {
    $post_array = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $post_data = [
        'id'              => $row['id'],
        'study_time'      => $row['study_time'], 
        'study_category'  => $row['study_category'],
        'study_detail'    => $row['study_detail'],
        'study_date'      => $row['study_date'],
        'user_id'         => $row['user_id'],
        'create_date'     => $row['create_date']
      ];
      array_push($post_array, $post_data);
    }
    echo json_encode($post_array);
  } else {
    echo json_encode(['message' => $message->NoPostFound]);
  }

?>