<?php
  Route::set('', function () {
      View::make('index');
  });
  
  Route::set('users/read', function () {
      View::make('users/read');
  });

