<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use function Laravel\Prompts\search;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function courseList()
    {

        $result = Course::select('name', 'thumbnail', 'lesson_num', 'price', 'id')->get();

        return response()->json([
            'code' => 200,
            'data' => $result,
            'message' => 'my list course is here'
        ], 200);
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function RecommendedCourseList()
    {

        $result = Course::select('name', 'thumbnail', 'lesson_num', 'price', 'id')->where('Recommended','=',1)
        ->get();

        return response()->json([
            'code' => 200,
            'data' => $result,
            'message' => 'my recommended course is here'
        ], 200);
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function searchCourseList(Request $request)
    {

        // Retrieve search query
        $search = $request->search;

        // Perform search
        $result = Course::select('name', 'thumbnail', 'lesson_num')
            ->where('name', "like", '%' . $search . '%')
            ->get();

        // Check if any results found
        if ($result->isEmpty()) {
            return response()->json([
                'code' => 404,
                'message' => 'No courses found matching the search criteria',
            ], 404);
        }

        // Return successful response with course data
        return response()->json([
            'code' => 200,
            'data' => $result,
            'message' => 'Recommended courses found',
        ], 200);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function courseDetail(Request $request)
    {
        $id = $request->id;

        $result = Course::where('id','=',$id)->select(
            'name',
            'user_token',
            'id',
            'thumbnail',
            'description',
            'video_length',
            'lesson_num',
            'price',
            'dowmloadable_res',

        )->first();

        return response()->json([
            'code' => 200,
            'data' => $result,
            'message' => 'my  course Details is here'
        ], 200);
    }

    ////////////////////////////////////////////////////////////////////////////////
    ///
    public function courseAuthor(Request $request){

        $token=$request->token;
        $result=DB::table('admin_users')->where('token','=',$token)
            ->select('token','username as name' , 'avatar' ,'description' , 'job' , 'download')
            ->first();

        if(!empty($result))
            $result->avatar='/uploads/'.$result->avatar;


        return response()->json([
            'code' => 200,
            'data' => $result ?? json_decode('{}'),
            'message' => 'the Author info : '
        ], 200);
    }
//////////////////////////////////////////////////////////////////////////////////////////////

    public function courseListAuthor(Request $request){

        $token=$request->token;

        $result=Course::where('user_token','=',$token)
            ->select('name' ,'thumbnail' , 'lesson_num' , 'price' , 'id')
            ->get();


        return response()->json([
            'code' => 200,
            'data' => $result ?? json_decode('{}'),
            'message' => 'the Author info : '
        ], 200);
    }

}
