<?php
  Route::set('', function () {
      View::make('index');
  });
  
  Route::set('users/read', function () {
      View::make('users/read');
  });

  Route::set('users/insert', function () {
      View::make('users/insert');
  });

  Route::set('users/update', function () {
    View::make('users/update');
});

  Route::set('study_detail/read', function () {
      View::make('study_detail/read');
  });

  Route::set('study_detail/insert', function () {
      View::make('study_detail/insert');
  });

  Route::set('study_detail/update', function () {
    View::make('study_detail/update');
});

  Route::set('project_category/read', function () {
      View::make('project_category/read');
  });

  Route::set('project_category/insert', function () {
      View::make('project_category/insert');
  });

  Route::set('project_category/update', function () {
    View::make('project_category/update');
});
