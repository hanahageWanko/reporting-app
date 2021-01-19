<?php
class Session {
  public static function redirect($session, $pass) {
    if(!$session) {
      header("Location: $pass");
      exit();
    }
  }
}