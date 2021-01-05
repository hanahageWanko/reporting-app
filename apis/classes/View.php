<?php
class View {
  public static function make($view) {
    if(Route::isRouteValid()) {
        require_once( __DIR__ . "/../controllers/$view.php" );
        require_once( __DIR__ . "/../views/$view.php" );
        return 1;
    }
  }
}
?>