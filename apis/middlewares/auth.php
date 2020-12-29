<?php
  require __DIR__ . '/../classes/JwtHandler.php';
  require __DIR__ . '../classes/dbconnection.php';

  class Auth extends JwtHandler
  {
      protected $db;
      protected $headers;
      protected $token;
      protected $db_name;

      public function _construct($db, $headers)
      {
          parent::__construct();
          $this->db = $db;
          $this->headers = $headers;
      }

      public function isAuth()
      {
          if (
              array_key_exists('Authorization', $this->headers)
              && !empty(trim($this->headers['Authorization']))
            ) {
              $this->token = explode(" ", trim($this->headers['Authorization']));
              if (isset($this->token[1]) && !empty(trim($this->token[1]))) {
                  $data = $this->_jwt_decode_data($this->token[1]);
                  if (isset($data['auth']) && isset($data['data']->user_id) && $data['auth']) {
                      $user = $this->fetchUser($data['data']->user_id);
                      return $user;
                  } else {
                      return null;
                  }
              } else {
                  return null;
              }
          } else {
              return null;
          }
      }
      protected function fetchUser($user_id)
      {
        $table_users = $_SERVER['T_USER'];
              try {
              $insert_query = "SELECT `name`, `email` FROM $table_users WHERE `id`=:id";
              $query_stmt = $this->db->prepare($insert_query);
              $query_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
              $query_stmt->exeucte();

              if ($query_stmt->rowCount()) {
                  $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                  return [
                    'success' => 1,
                    'statuc'  => 200,
                    'user'    => $row
                  ];
              } else {
                  return null;
              }
          } catch (PDOException $e) {
              return null;
          }
      }
  }
