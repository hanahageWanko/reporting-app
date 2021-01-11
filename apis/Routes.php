<?php
  Route::set('', function () {
      View::make('index');
  });
  
  Route::set('users/read', function () {
      View::make('users/read');
  });

  Route::set('study_detail/read', function () {
    View::make('study_detail/read');
  });

  Route::set('project_category/read', function () {
    View::make('project_category/read');
  });