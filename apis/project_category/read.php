<?php
  require_once __DIR__ . '/../headers/read.php';
  require_once __DIR__ . '/../functions.php';

  $db = new CreateDBinstance();
  $conn = $db->dbInstanceConnection();

  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];
  if(isset($_GET['id'])) {
    $post_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
      'options' => [
        'default' => 'all_posts',
        'min_range' => 1
      ]
    ]);
  } else {
    $post_id = 'all_posts';
  }

  $sql = is_numeric($post_id)
         ? "SELECT * FROM `$table_project_category` WHERE id ='$post_id'"
         : "SELECT * FROM `$table_project_category`";

  $stmt = $conn->prepare($sql);

  $stmt->execute();

  if($stmt->rowCount() > 0) {
    $post_array = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $post_data = [
        'id'              => $row['id'],
        'name'      => $row['name'], 
        'delete_flg'  => $row['delete_flg'],
        'create_date'    => $row['create_date'],
        'update_date'      => $row['update_date']
      ];
      array_push($post_array, $post_data);
    }
    echo json_encode($post_array);
  } else {
    echo json_encode(resultMessage(0, 400, 'No post found'));
  }

?>