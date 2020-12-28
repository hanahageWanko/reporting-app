<?php
function updateBindValue($row, $data, $value) {
  return !empty($data->$value) ? $data->$value : $row[$value];
}

function resultMessage($success, $status, $message, $extra = [])
{
    return array_merge([
  'success' => $success,
  'status' => $status,
  'message' => $message
], $extra);
}

