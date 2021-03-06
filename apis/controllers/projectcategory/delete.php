<?php
  require_once __DIR__ . '/../../headers/delete.php';

  Session::redirect(isset($_SESSION["login"]), '/login');

  if (!Validate::requestType("DELETE")) {
      exit();
  }
  
  $data = json_decode(file_get_contents("php://input"));

  $table_project_category = $_SERVER['T_PROJECT_CATEGORY'];

  $postId = $data->id;
  $checkPost = "SELECT id FROM `$table_project_category` WHERE id=:id";
  $deletePost = "DELETE FROM `$table_project_category` WHERE id=:id";

  // 削除対象が存在するかどうかを検索
  if (!isset($postId) || empty($postId)) {
      echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
      return;
  }

  try {
      $checkItem = Database::select($checkPost, [':id' => $postId]);
      if ($checkItem->rowCount() === 0) {
          echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
          return;
      }
      Database::post($deletePost, [':id' => $postId]);
      echo json_encode(Validate::resultMessage(0, 200, 'Post Deleted Successfuly'));
  } catch (PDOException $e) {
      echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
  }
