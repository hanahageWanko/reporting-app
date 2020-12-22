<?php
function updateBindValue($row, $data, $value) {
  return !empty($data->$value) ? $data->$value : $row[$value];
}
