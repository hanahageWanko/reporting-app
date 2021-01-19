<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once "env.php";
session_start();

class ClassLoader {
  // class ファイルがあるディレクトリのリスト
  private static $dirs;
  public static function loadClass($class) {
    foreach (self::directories() as $directory) {
      $file_name = "{$directory}/{$class}.php";
      if (is_file($file_name)) {
        require_once $file_name;
        return true;
      }
    }
  }

  private static function directories() {
    if(empty(self::$dirs)) {
      $base = __DIR__;
      self::$dirs = [
        $base . '/classes',
      ];
    }
    return self::$dirs;
  }
}
spl_autoload_register(['ClassLoader', 'loadClass']);

require_once "Routes.php";

$checkRoute = new Route();
$checkRoute->run();

?>