<?php
require_once __DIR__ . '/../headers/insert.php';


if ($_SERVER["REQUEST_METHOD"] != "POST"):
  echo json_encode(Validate::resultMessage(0, 405, 'Method Not Allowed'));
  return;
endif;

$data = json_decode(file_get_contents("php://input"));

if (!Validate::dataValidate($data)) {
    $fields = ['fields' => ['email','password']];
    echo json_encode(Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
    return;
}

$email = trim($data->email);
$password  =trim($data->password);
$table_users = $_SERVER['T_USER'];

if (!Validate::mailFormat($email, 'Invalid Email Address!')) {
    return;
}
if (!Validate::lessThanStr(htmlspecialchars($password), 8, 'Your password must be at least 8 characters long!')) {
    return;
}


try {
    $fetchUserByEmail = "SELECT * FROM $table_users WHERE `email`=:email";
    $checkItem = Database::select($fetchUserByEmail, [':email' => $email]);

    if ($checkItem->rowCount() === 0) {
        echo json_encode(Validate::resultMessage(0, 400, 'Invlid ID'));
    };

    $row = $checkItem->fetch(PDO::FETCH_ASSOC);
    $check_password = password_verify($password, $row['password']);
    if ($check_password) {
        $jwt = new JwtHandler();
        $token = $jwt->_jwt_encode_data(
            'http://127.0.0.1:8080/apis/',
            array("user_id" => $row['id'])
        );
        echo json_encode(
            [
          'success' => 1,
          'message' => 'You have successfully logged in.',
          'token' => $token
        ]
        );
    } else {
        echo json_encode(Validate::resultMessage(0, 422, 'Invalid Password'));
    }
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
}
