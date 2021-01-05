<?php
  Route::set('', function () {
      View::make('index');
  });
  
  Route::set('about-us', function () {
      View::make('AboutUs');
  });

  Route::set('contact-us', function () {
      View::make('ContactUs');
  });
