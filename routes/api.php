<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'Api'] , function (){


    Route::post ('/register','AuthController@register_user');
    Route::post ('/login ','AuthController@login_user');

    Route::group(['middleware'=>['auth:sanctum']],function (){

      Route::any('/courseList','CourseController@courseList');
        Route::any('/courseDetail','CourseController@courseDetail');
        Route::any('/checkout','PayController@checkout');
        Route::any('/lessonList','LessonController@lessonList');
        Route::any('/RecommendedCourseList','CourseController@RecommendedCourseList');
        Route::any('/searchCourseList','CourseController@searchCourseList');
        Route::any('/lessonDetail','LessonController@lessonDetail');
        Route::any('/courseAuthor','CourseController@courseAuthor');
        Route::any('/courseListAuthor','CourseController@courseListAuthor');

    });

});




