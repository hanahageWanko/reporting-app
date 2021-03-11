<?php

  // users
  Route::set('users/read', function () {
      View::make('users/read');
  });

  Route::set('users/insert', function () {
      View::make('users/insert');
  });

  Route::set('users/update', function () {
      View::make('users/update');
  });

  Route::set('users/delete', function () {
      View::make('users/delete');
  });


  // study_detail
  Route::set('studydetail/read', function () {
      View::make('studydetail/read');
  });

  Route::set('studydetail/insert', function () {
      View::make('studydetail/insert');
  });

  Route::set('studydetail/update', function () {
      View::make('studydetail/update');
  });

  Route::set('studydetail/delete', function () {
      View::make('studydetail/delete');
  });

  // project_category
  Route::set('projectcategory/read', function () {
      View::make('projectcategory/read');
  });

  Route::set('projectcategory/insert', function () {
      View::make('projectcategory/insert');
  });

  Route::set('projectcategory/update', function () {
      View::make('projectcategory/update');
  });

  Route::set('projectcategory/delete', function () {
      View::make('projectcategory/delete');
  });

  Route::set('userinfo', function () {
      View::make('userinfo');
  });

  Route::set('login', function () {
      View::make('login');
  });

  Route::set('logout', function () {
      View::make('logout');
  });
