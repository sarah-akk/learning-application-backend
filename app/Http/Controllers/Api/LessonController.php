<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;


class LessonController extends Controller
{
    public function lessonList(Request $request){
     try{
         $courseId = $request->id;
         $result = Lesson::Where('course_id','=',$courseId)->select(
             'id' , 'name' , 'thumbnail' , 'description' , 'video'
         )->get();

         return response()->json([
             'code'=>200,
             'data'=>$result,
             'msg'=>"success"
         ],200);
     }
     catch (\Throwable $e){
         return response()->json([
             'code'=>500,
             'msg'=>$e->getMessage()
         ],500);
     }
    }

    public function lessonDetail(Request $request){
        try{
            $lessonId = $request->id;
            $result = Lesson::Where('id','=',$lessonId)->select(
                'id'  , 'video'
            )->first();

            return response()->json([
                'code'=>200,
                'data'=>$result->video,
                'msg'=>"success"
            ],200);
        }
        catch (\Throwable $e){
            return response()->json([
                'code'=>500,
                'msg'=>$e->getMessage()
            ],500);
        }
    }
}
