<?php
require_once __DIR__ . '/../headers/insert.php';

$table_users = $_SERVER['T_USER'];
$data = json_decode(file_get_contents("php://input"));

if(!$data) {
  echo json_encode(Validate::resultMessage(0, 403, 'Certification failed. Please log in again.'));
  exit();
} 

if (isset($_SESSION["login"])) {
    session_regenerate_id(true);
    exit();
}

if (!Validate::dataValidate($data)) {
    $fields = ['fields' => ['email','password']];
    echo json_encode(Validate::resultMessage(0, 422, 'Please Fill in all Required Fields!', $fields));
    exit();
}

$email = trim($data->email);
$password  =trim($data->password);

if (!Validate::mailFormat($email, 'Invalid Email Address!')) {
    return;
}
if (!Validate::lessThanStr($password, 8, 'Your password must be at least 8 characters long!')) {
    return;
}


try {
    $fetchUserByEmail = "SELECT * FROM $table_users WHERE `email`=:email";
    $checkItem = Database::select($fetchUserByEmail, [':email' => $email]);

    if ($checkItem->rowCount() === 0) {
        echo json_encode(Validate::resultMessage(0, 400, 'Invlid email'));
    };

    $row = $checkItem->fetch(PDO::FETCH_ASSOC);
    $check_password = password_verify($password, $row['password']);
    if ($check_password) {
        $_SESSION["login"] = $row['user_name'];
        session_regenerate_id(true);
        echo json_encode(Validate::resultMessage(0, 200, 'Pass login!'));
        exit();
    } else {
        echo json_encode(Validate::resultMessage(0, 422, 'Invalid Password'));
    }
} catch (PDOException $e) {
    echo json_encode(Validate::resultMessage(0, 500, $e->getMessage()));
}
